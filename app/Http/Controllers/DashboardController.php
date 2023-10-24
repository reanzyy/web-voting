<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Student;
use App\Models\User;
use App\Models\Vote;

class DashboardController extends Controller
{
    public function index()
    {
        $operator = User::count();
        $candidate = Candidate::count();
        $vote_in = Vote::count();
        $student = Student::count();
        $votes = $student - $vote_in;

        $candidates = Candidate::orderBy('sequence', 'ASC')
            ->get();

        $chartData = [
            'labels' => [],
            'counts' => [],
        ];

        foreach ($candidates as $data) {
            $count = Vote::where('candidate_id', $data->id)
                ->count();

            $chartData['labels'][] = "Paslon " . $data->sequence;
            $chartData['counts'][] = $count;
        }

        return view(
            'pages.dashboard',
            [
                'chartData' => $chartData,
                'operator' => $operator,
                'candidate' => $candidate,
                'votes' => $votes,
                'vote_in' => $vote_in,
            ]
        );
    }
}
