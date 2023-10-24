<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // $selectedYear = $request->input('selected_year', Carbon::now()->format('Y'));

        $query = DB::table('votes')
            // ->whereYear('created_at', $selectedYear)
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

        return view('pages.dashboard', [
            'chartData' => $chartData,
        ]);
    }
}
