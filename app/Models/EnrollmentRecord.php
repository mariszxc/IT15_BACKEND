<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'student_number',
        'student_name',
        'batch',
        'submitted_at',
        'submitted',
        'pending',
        'approved',
        'enrollment_status',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'submitted' => 'boolean',
            'pending' => 'boolean',
            'approved' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
