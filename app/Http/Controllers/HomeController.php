<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeatmapSet;
use App\Models\Beatmap;
use App\Models\User;

class HomeController extends Controller
{
	public function index()
	{
		$latestMaps = BeatmapSet::latest()->take(4)->get();

		$stats = [
			'users' => User::count(),
			'beatmap_sets' => BeatmapSet::count(),
			'beatmaps' => Beatmap::count(),
		];

		return view('welcome', compact('latestMaps', 'stats'));
	}
}
