<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeatmapSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeatmapController extends Controller
{
	public function index(Request $request)
	{
		$data = BeatmapSet::with('user')->latest();

		if ($request->has('status')) {
			$data = $data->where('status', $request->status);
		}

		$beatmapSets = $data->paginate(20);
		return view('admin.beatmaps.index', compact('beatmapSets'));
	}

	public function updateStatus(Request $request, BeatmapSet $beatmapSet)
	{
		$request->validate([
			'status' => 'required|in:pending,ranked,loved,graveyard,work in progress,qualified',
		]);

		$beatmapSet->update(['status' => $request->status]);
		return back()->with('success', "Статус карты {$beatmapSet->title} успешно обновлен на {$request->status}");
	}

	public function destroy(BeatmapSet $beatmapSet)
	{
		$filePath = $beatmapSet->file_path;
		$bgPath = $beatmapSet->bg_path;
		$audioPath = $beatmapSet->audio_path;

		if ($filePath && Storage::disk('public')->exists($filePath)) {
			Storage::disk('public')->delete($filePath);
		}

		if ($bgPath && Storage::disk('public')->exists($bgPath)) {
			Storage::disk('public')->delete($bgPath);
		}

		if ($audioPath && Storage::disk('public')->exists($audioPath)) {
			Storage::disk('public')->delete($audioPath);
		}

		$beatmapSet->delete();
		return back()->with('success', 'Карта полностью удалена');
	}
}
