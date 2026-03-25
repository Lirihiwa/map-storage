<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeatmapSet extends Model
{
	protected $fillable = [
		'user_id',
        'title',
        'artist',
        'creator',
        'source',
        'tags',
        'bpm',
        'bg_path',
        'audio_path',
        'status',
        'file_path',
	];

    public function user() {
		return $this->belongsTo(User::class);
	}

	public function beatmaps() {
		return $this->hasMany(Beatmap::class);
	}
}
