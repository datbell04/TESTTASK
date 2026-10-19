<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Submission extends Model
{
    /**
     * MongoDB connection declared in config/database.php.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'submissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'exam_id',
        'student_id',
        'answers',
        'score',
        'submitted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'exam_id' => 'integer',
            'student_id' => 'integer',
        ];
    }
}
