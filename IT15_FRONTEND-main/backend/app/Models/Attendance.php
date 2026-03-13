<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['school_day', 'attendance_rate'];

    protected $casts = [
        'school_day' => 'date',
        'attendance_rate' => 'decimal:2',
    ];
}
