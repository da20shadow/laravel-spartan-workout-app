<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercises extends Model
{
    use HasFactory;

    protected $fillable = [
        'push_ups',
        'sit_ups',
        'bench_dips',
        'squats',
        'pull_ups',
        'hammer_curl',
        'barbel_curl',
        'user_id',
    ];
}
