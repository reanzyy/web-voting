<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Student;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $admin = User::count();
        $candidate = Candidate::count();
        $vote_in = Vote::count();
        $student = Student::count();
        $votes = $student - $vote_in;

        $query = DB::table('votes')
            ->select('candidate_id', DB::raw('count(*) as count'))
            ->groupBy('candidate_id')
            ->get();

        $chartData = [
            'labels' => [],
            'counts' => [],
        ];

        foreach ($query as $vote) {
            $chartData['labels'][] = "Paslon " . $vote->candidate_id;
            $chartData['counts'][] = $vote->count;
        }

        // dd($chartData);

        return view(
            'pages.dashboard',
            [
                'chartData' => $chartData,
                'admin' => $admin,
                'candidate' => $candidate,
                'votes' => $votes,
                'vote_in' => $vote_in,
            ]
        );
    }
}