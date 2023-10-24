<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Student;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = User::count();
        $candidate = Candidate::count();
        $vote_in = Vote::count();
        $student = Student::count();
        $vote = $student - $vote_in;
        return view('pages.dashboard', compact('admin','candidate','vote_in','vote'));
    }
}
