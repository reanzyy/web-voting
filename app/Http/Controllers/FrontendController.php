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

            $chartData['labels'][] = "Pasangan " . $data->sequence;
            $chartData['hoverLabels'][] = $data->chairman . " - " . $data->deputy_chairman;
            $chartData['counts'][] = $count;
        }

        $backgroundColor = [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)',
        ];
        $borderColor = [
            'rgb(255, 99, 132)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)'
        ];

        return view('index', [
            'chartData' => $chartData,
            'candidates' => $candidates,
            'backgroundColor' => $backgroundColor,
            'borderColor' => $borderColor,
        ]);
    }
}
