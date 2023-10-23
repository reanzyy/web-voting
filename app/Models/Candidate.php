<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'candidate_id'
    ];

    public function visions()
    {
        return $this->hasMany(Vision::class);
    }

    public function missions()
    {
        return $this->hasMany(Mission::class);
    }
}
