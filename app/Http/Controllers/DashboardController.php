<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->input('selected_year', Carbon::now()->format('Y'));
        $classrooms = Classroom::count();
        $students = Student::count();
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
                ->whereYear('created_at', $selectedYear)
                ->count();

            $chartData['labels'][] = "Paslon " . $data->sequence;
            $chartData['counts'][] = $count;
        }

        return view(
            'pages.dashboard',
            [
                'chartData' => $chartData,
                'classrooms' => $classrooms,
                'students' => $students,
                'votes' => $votes,
                'vote_in' => $vote_in,
            ]
        );
    }
}
