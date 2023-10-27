<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\SchoolYear;
use App\Models\Vote;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $defaultYearId = SchoolYear::where('is_active', true)->value('id');
        $selectedYear = $defaultYearId;
        $candidatesQuery = Candidate::query();
        $candidates = $candidatesQuery->where('school_year_id', $selectedYear)->get();

        $chartData = [
            'labels' => [],
            'counts' => [],
        ];

        foreach ($candidates as $data) {
            $count = Vote::where('candidate_id', $data->id)
                ->count();

            $chartData['labels'][] = $data->chairman . " - "  . $data->deputy_chairman;
            $chartData['counts'][] = $count;
        }
        return view('index', [
            'chartData' => $chartData,
            'candidates' => $candidates,
        ]);
    }
}
