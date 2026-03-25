<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beatmap extends Model
{
	protected $fillable = [
		'beatmap_set_id',
		'difficulty_name',
		'mode',
		'star_rating',
		'ar',
		'od',
		'cs',
		'hp',
		'total_length',
	];
	
    public function beatmapSet() {
		return $this->belongsTo(BeatmapSet::class);
	}
}
