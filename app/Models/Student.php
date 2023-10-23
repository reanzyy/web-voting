<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'identity',
        'name',
        'gender',
        'status'
    ];

    public function hasVoted()
    {
        return Vote::where('student_id', $this->id)->exists();
    }
}
