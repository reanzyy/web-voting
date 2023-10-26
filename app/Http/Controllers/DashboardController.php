<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Classroom;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = $request->input('selected_year', Carbon::now()->format('Y'));

        $schoolYears = SchoolYear::all();
        $defaultYearId = SchoolYear::where('is_active', true)->value('id');
        $candidatesQuery = Candidate::query();
        $classroomsQuery = Classroom::query();
        $studentsQuery = Student::query();
        $votesQuery = Vote::query();
        if ($request->has('year')) {
            $selectedYear = $request->year;
        } else {
            $selectedYear = $defaultYearId;
        }

        $candidates = $candidatesQuery->where('school_year_id', $selectedYear)->get();
        $studentYear = $studentsQuery->whereRelation('classroom', 'school_year_id', $selectedYear)->get();
        $classroomsYear = $classroomsQuery->where('school_year_id', $selectedYear)->get();
        $votesYear = $votesQuery->whereRelation('candidate', 'school_year_id', $selectedYear)->get();

        $classrooms = $classroomsYear->count();
        $students = $studentYear->count();
        $vote_in = $votesYear->count();
        $votes = $students - $vote_in;

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
                'schoolYears' => $schoolYears,
                'defaultYearId' => $defaultYearId,
                'chartData' => $chartData,
                'classrooms' => $classrooms,
                'students' => $students,
                'votes' => $votes,
                'vote_in' => $vote_in,
            ]
        );
    }
}
