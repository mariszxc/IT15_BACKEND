<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'is_school_day',
        'is_holiday',
        'event',
        'attendance_count',
        'attendance_rate',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_school_day' => 'boolean',
            'is_holiday' => 'boolean',
            'attendance_rate' => 'decimal:2',
        ];
    }
}
