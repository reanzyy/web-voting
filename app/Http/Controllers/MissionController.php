<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function index($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            abort(404);
        }


        $missions = Mission::where('candidate_id', '=', $id)
            ->orderBy('sequence', 'ASC')
            ->get();
        return view("pages.candidates.missions.index", compact('missions', 'candidate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            abort(404);
        }

        $request->validate(
            [
                'candidate_id' => 'required',
                'sequence' => ['required', 'numeric', 'between:1,100'],
                'description' => 'required|max:255|min:5',
            ],
            [
                'candidate_id.required' => 'Kandidat harus diisi!',
                'description.required' => 'Misi yang dinilai harus diisi!',
                'description.max' => 'Maksimal 255 karakter!',
                'description.min' => 'Minimal 5 karakter!',
                'sequence.required' => 'Urutan harus diisi!',
                'sequence.between' => 'Urutan harus berupa angka antara 1 sampai 100.',
            ]
        );

        $missions = new Mission;
        $missions->candidate_id = $request->candidate_id;
        $missions->sequence = $request->sequence;
        $missions->description = $request->description;
        $missions->save();

        return redirect()->route('candidates.missions.index', $candidate->id)->withSuccess('Misi berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id_candidate, $id_missions, Request $request)
    {
        $candidate = Candidate::find($id_candidate);
        $missions = Mission::find($id_missions);

        if (!$candidate && !$missions) {
            abort(404);
        }

        $request->validate(
            [
                'candidate_id' => 'required',
                'sequence' => ['required', 'numeric', 'between:1,100'],
                'description' => 'required|max:255|min:5',
            ],
            [
                'candidate_id.required' => 'Kandidat harus diisi!',
                'description.required' => 'Misi yang dinilai harus diisi!',
                'description.max' => 'Maksimal 255 karakter!',
                'description.min' => 'Minimal 5 karakter!',
                'sequence.required' => 'Urutan harus diisi!',
                'sequence.between' => 'Urutan harus berupa angka antara 1 sampai 100.',
            ]
        );

        $missions->candidate_id = $request->candidate_id;
        $missions->sequence = $request->sequence;
        $missions->description = $request->description;
        $missions->save();

        return redirect()->route('candidates.missions.index', $candidate->id)->withSuccess('Misi berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_candidate, $id_missions)
    {
        $candidate = Candidate::find($id_candidate);
        $missions = Mission::find($id_missions);

        if (!$candidate && !$missions) {
            abort(404);
        }

        $missions->delete();

        return redirect()->route('candidates.missions.index', $candidate->id)->withSuccess('Misi berhasil dihapus!');
    }
}
