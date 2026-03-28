<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\BeatmapSet;
use App\Models\Beatmap;
use ZipArchive;

class BeatmapController extends Controller
{
	public function create()
	{
		return view('beatmaps.upload');
	}

	public function store(Request $request): \Illuminate\Http\RedirectResponse
	{
		$request->validate([
			'osz_file' => 'required|file|extensions:osz|max:102400',
		]);

		$file = $request->file('osz_file');
		$zip = new ZipArchive;

		if ($zip->open($file->getRealPath()) === TRUE) {
			$tempPath = storage_path('app/temp/' . uniqid());
			$zip->extractTo($tempPath);

			$files = scandir($tempPath);
			$osuFiles = array_filter($files, fn($f) => str_ends_with($f, '.osu'));

			if (count($osuFiles) === 0) {
				return back()->withErrors(['osz_file' => 'Отправленный .osz файл не содержит .osu файлов.']);
			}

			$data = $this->parseOsuFile($tempPath . DIRECTORY_SEPARATOR . reset($osuFiles));

			$bgFilename = $data['Background'] ?? null;
			$audioFilename = $data['General']['AudioFilename'] ?? null;

			$bgPath = null;
			$audioPath = null;

			if (!Storage::disk('public')->exists('backgrounds')) {
				Storage::disk('public')->makeDirectory('backgrounds');
			}

			if (!Storage::disk('public')->exists('audio')) {
				Storage::disk('public')->makeDirectory('audio');
			}

			if ($bgFilename && file_exists($tempPath . DIRECTORY_SEPARATOR . $bgFilename)) {
				$extantion = pathinfo($bgFilename, PATHINFO_EXTENSION);
				$bgPath = 'backgrounds/' . uniqid() . '.' . $extantion;
				File::copy($tempPath . DIRECTORY_SEPARATOR . $bgFilename, storage_path('app/public/' . $bgPath));
			}

			if ($audioFilename && file_exists($tempPath . DIRECTORY_SEPARATOR . $audioFilename)) {
				$extantion = pathinfo($audioFilename, PATHINFO_EXTENSION);
				$audioPath = 'audio/' . uniqid() . '.' . $extantion;
				File::copy($tempPath . DIRECTORY_SEPARATOR . $audioFilename, storage_path('app/public/' . $audioPath));
			}

			$set = BeatmapSet::create([
				'user_id' => auth()->id(),
				'title' => $data['Metadata']['Title'] ?? 'Unknown Title',
				'artist' => $data['Metadata']['Artist'] ?? 'Unknown Artist',
				'creator' => $data['Metadata']['Creator'] ?? 'Unknown Creator',
				'source' => $data['Metadata']['Source'] ?? null,
				'tags' => $data['Metadata']['Tags'] ?? null,
				'bpm' => $data['BPM'] ?? 0,
				'bg_path' => $bgPath,
				'audio_path' => $audioPath,
				'status' => 'pending',
				'file_path' => $file->store('beatmaps', 'public'),
			]);

			foreach ($osuFiles as $osuFile) {
				$diffMeta = $this->parseOsuFile($tempPath . DIRECTORY_SEPARATOR . $osuFile);

				$set->beatmaps()->create([
					'difficulty_name' => $diffMeta['Metadata']['Version'] ?? 'Unknown Difficulty',
					'mode' => (int) $diffMeta['General']['Mode'] ?? 0,
					'ar' => $diffMeta['Difficulty']['ApproachRate'] ?? 0,
					'od' => $diffMeta['Difficulty']['OverallDifficulty'] ?? 0,
					'cs' => $diffMeta['Difficulty']['CircleSize'] ?? 0,
					'hp' => $diffMeta['Difficulty']['HPDrainRate'] ?? 0,
					'star_rating' => 0,
					'total_length' => 0,
				]);
			}

			$this->recursiveRemDir($tempPath);
			$zip->close();

			return redirect()->route('beatmaps.index')->with('success', 'Карта успешно загружена!');
		}

		return back()->withErrors(['osz_file' => 'Не удалось открыть файл.']);
	}

	public function index()
	{
		$beatmapSets = BeatmapSet::with('beatmaps')
			->latest()
			->paginate(12);

		return view('beatmaps.index', compact('beatmapSets'));
	}

	public function show(BeatmapSet $beatmapSet)
	{
		$beatmapSet->load('beatmaps');
		return view('beatmaps.show', compact('beatmapSet'));
	}

	public function download(BeatmapSet $beatmapSet)
	{
		$fullPath = storage_path('app/public/' . $beatmapSet->file_path);

		if (!$beatmapSet->file_path || !file_exists($fullPath)) {
			abort(404, 'Файл карты не найден.');
		}

		// Символы, недопустимые в названии файла
		$cleanArtist = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '', $beatmapSet->artist);
		$cleanTitle = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '', $beatmapSet->title);

		$safeName = "{$cleanArtist} - {$cleanTitle}.osz";

		return response()->download($fullPath, $safeName, [
			'Content-Type' => 'application/octet-stream',
			'Content-Disposition' => 'attachment; filename="' . $safeName . '"',
		]);
	}

	// Извлечение метаинформации из файла .osu
	private function parseOsuFile($filePath)
	{
		$content = file_get_contents($filePath);
		$lines = explode("\n", $content);
		$data = [
			'General' => [],
			'Metadata' => [],
			'Difficulty' => [],
			'Events' => [],
			'BPM' => 0,
		];

		$currentSection = '';

		foreach ($lines as $line) {
			$line = trim($line);

			// Пропуск пустых строк и комментариев
			if (empty($line) || str_starts_with($line, '//')) {
				continue;
			}

			// Извлечение названия секции
			if (str_starts_with($line, '[') && str_ends_with($line, ']')) {
				$currentSection = str_replace(['[', ']'], '', $line);
				continue;
			}

			// Извлечение ключ-значение
			if (str_contains($line, ':')) {
				[$key, $value] = explode(':', $line, 2);
				$data[$currentSection][trim($key)] = trim($value);
			}

			// Извлечение bg из секции Events
			if ($currentSection === 'Events') {
				$parts = explode(',', $line);

				if (trim($parts[0]) === '0') {
					if (isset($parts[2])) {
						$bgFilename = trim($parts[2], '" ');

						if (str_contains($bgFilename, '.')) {
							$data['Background'] = $bgFilename;
						}
					}
				}
			}

			// Извлечение BPM из секции TimingPoints
			if ($currentSection === 'TimingPoints' && !isset($data['BPM_Done'])) {
				$parts = explode(',', $line);
				if (count($parts) > 1) {
					$beatLength = floatval($parts[1]);
					if ($beatLength > 0) {
						$data['BPM'] = round(60000 / $beatLength, 2);
						$data['BPM_Done'] = true; // Флаг, чтобы не перезаписывать BPM при последующих TimingPoints
					}
				}
			}
		}

		return $data;
	}

	// Рекурсивное удаление директории
	private function recursiveRemDir($dir)
	{
		if (is_dir($dir)) {
			$objs = scandir($dir);

			foreach ($objs as $obj) {
				if ($obj != "." && $obj != "..") {
					if (is_dir($dir . DIRECTORY_SEPARATOR . $obj)) {
						$this->recursiveRemDir($dir . DIRECTORY_SEPARATOR . $obj);
					} else {
						unlink($dir . DIRECTORY_SEPARATOR . $obj);
					}
				}
			}

			rmdir($dir);
		}
	}
}
