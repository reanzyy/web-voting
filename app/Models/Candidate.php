<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'sequence',
        'chairman',
        'deputy_chairman',
        'photo_chairman',
        'photo_deputy_chairman'
    ];

    public function visions()
    {
        return $this->hasMany(Vision::class);
    }

    public function missions()
    {
        return $this->hasMany(Mission::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
