<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::orderBy('sequence', 'ASC')
            ->get();

        $data = [];

        foreach ($candidates as $candidate) {
            $data[] = [
                'id' => $candidate->id,
                'sequence' => $candidate->sequence,
                'chairman' => $candidate->chairman,
                'deputy_chairman' => $candidate->deputy_chairman,
                'photo_chairman' => Storage::disk('public')->url($candidate->photo_chairman),
                'photo_deputy_chairman' => Storage::disk('public')->url($candidate->photo_deputy_chairman),
            ];
        }

        return $this->sendSuccess($data, 'successfully load candidates', 200);
    }
}