<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Vote;

class AnalyticController extends Controller
{
    public function index()
    {
        $candidates = Candidate::orderBy('sequence', 'ASC')
            ->get();

        $data = [];

        foreach ($candidates as $candidate) {

            $count = Vote::where('candidate_id', $candidate->id)
                ->count();

            $data[] = [
                'id' => $candidate->id,
                'sequence' => $candidate->sequence,
                'chairman' => $candidate->chairman,
                'deputy_chairman' => $candidate->deputy_chairman,
                'count' => $count,
            ];
        }

        return $this->sendSuccess($data, 'successfully load candidates', 200);
    }
}