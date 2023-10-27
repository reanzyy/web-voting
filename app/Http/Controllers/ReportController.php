<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Libraries\Report;
use App\Models\Candidate;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $schoolYears = SchoolYear::all();
        $defaultYearId = SchoolYear::where('is_active', true)->value('id');

        $query = Candidate::query();

        if ($request->has('year')) {
            $query->where('school_year_id', $request->year);
        } else {
            $query->where('school_year_id', $defaultYearId);
        }

        $candidates = $query->get();

        $emptyCandidate = $candidates->count() == 0 && $candidates->count() == 1;

        return view('pages.reports.index', compact('candidates', 'schoolYears', 'defaultYearId', 'emptyCandidate'));
    }

    public function report()
    {
        $pdf = new Report('P', 'mm', 'A4');
        $pdf->SetTitle('Hasil Pemilihan');
        $pdf->SetAuthor('adrian');

        $defaultYearId = SchoolYear::where('is_active', true)->pluck('id');

        $candidates = DB::table('candidates as c')
            ->select('c.id as candidate_id', 'c.sequence as candidate_sequence', 'c.chairman as candidate_chairman', 'c.deputy_chairman as candidate_deputy_chairman', 'c.school_year_id as school_year')
            ->selectRaw('COUNT(DISTINCT v.id) as vote_count')
            ->leftJoin('votes as v', 'c.id', '=', 'v.candidate_id')
            ->where('c.school_year_id', $defaultYearId)
            ->groupBy('c.id', 'c.sequence', 'c.chairman', 'c.deputy_chairman', 'c.school_year_id')
            ->orderByDesc('vote_count')
            ->get();

        if ($candidates->count() == 1) {
            return redirect()->back()->withError('Kandidat harus lebih dari satu!');
        } elseif ($candidates->count() == 0) {
            return redirect()->back()->withError('Tidak ada kandidate yang aktif!');
        }

        $pdf->Report($candidates);

        $pdf->Output('I', 'PDF');

        exit;
    }
}
