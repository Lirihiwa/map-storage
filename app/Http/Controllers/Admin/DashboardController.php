<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BeatmapSet;
use Carbon\Carbon;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$days = $request->input('days', 7);
		$startDate = Carbon::now()->subDays($days);

		$newUsers = User::where('created_at', '>=', $startDate)->count();
		$newBeatmapSets = BeatmapSet::where('created_at', '>=', $startDate)->count();

		$totalUsers = User::count();
		$totalBeatmapSets = BeatmapSet::count();

		$recentUsers = User::latest()->take(5)->get();
		$recentBeatmapSets = BeatmapSet::latest()->take(5)->get();

		return view('admin.dashboard', compact(
			'newUsers',
			'newBeatmapSets',
			'totalUsers',
			'totalBeatmapSets',
			'days',
			'recentUsers',
			'recentBeatmapSets'
		));
	}
}
