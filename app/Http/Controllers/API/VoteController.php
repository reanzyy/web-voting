<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                'student_id' => 'required|exists:students,identity',
                'candidate_id' => 'required|exists:students,id'
            ],
            [
                'student_id.required' => 'NIS harus diisi!',
                'student_id.unique' => 'Siswa ini sudah memilih!',
                'student_id.exists' => 'Siswa ini tidak terdaftar!',
                'candidate_id.required' => 'Kandidat harus diisi!',
                'candidate_id.exists' => 'Kandidat ini tidak terdaftar!',
            ]
        );

        $student = Student::where('identity', $request->student_id)
            ->first();

        if ($student->hasVoted()) {
            return $this->sendError('siswa sudah melakukan voting', 400);
        }

        $student->status = "Sudah Memilih";
        $student->save();

        $vote = new Vote;
        $vote->student_id = $student->id;
        $vote->candidate_id = $request->candidate_id;
        $vote->save();

        return $this->sendSuccess([
            'id' => $vote->id,
            'student_id' => $vote->student_id,
            'candidate_id' => $vote->candidate_id
        ], 'successfully create vote', 201);
    }
}