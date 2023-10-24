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
        $vote = $student - $vote_in;

        $query = DB::table('votes')
            ->select('candidate_id', DB::raw('count(*) as count'))
            ->groupBy('candidate_id')
            ->get();

        $chartData = [
            'labels' => [],
            'counts' => [],
        ];

        foreach ($query as $vote) {
            $chartData['labels'][] = "Kandidat " . $vote->candidate_id;
            $chartData['counts'][] = $vote->count;
        }

        return view('pages.dashboard', compact('admin','candidate','vote_in','vote', 'chartData'));
    }
}
