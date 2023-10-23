<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vision;
use Illuminate\Http\Request;

class VisionController extends Controller
{
    public function index($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            abort(404);
        }


        $visions = Vision::where('candidate_id', '=', $id)
            ->orderBy('sequence', 'ASC')
            ->get();
        return view("pages.candidates.visions.index", compact('visions', 'candidate'));
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
                'description.required' => 'Visi yang dinilai harus diisi!',
                'description.max' => 'Maksimal 255 karakter!',
                'description.min' => 'Minimal 5 karakter!',
                'sequence.required' => 'Urutan harus diisi!',
                'sequence.between' => 'Urutan harus berupa angka antara 1 sampai 100!',
            ]
        );

        $visions = new Vision;
        $visions->candidate_id = $request->candidate_id;
        $visions->sequence = $request->sequence;
        $visions->description = $request->description;
        $visions->save();

        return redirect()->route('candidates.visions.index', $candidate->id)->withSuccess('Visi berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id_candidate, $id_visions, Request $request)
    {
        $candidate = Candidate::find($id_candidate);
        $visions = Vision::find($id_visions);

        if (!$candidate && !$visions) {
            abort(404);
        }

        $request->validate(
            [
                'candidate_id' => 'required',
                'sequence' => ['required', 'numeric', 'between:1,100'],
                'description' => 'required|max:255|min:5',
            ],
            [
                'candidate_id' => 'Kandidat harus diisi!',
                'description.required' => 'Visi yang dinilai harus diisi!',
                'description.max' => 'Maksimal 255 karakter!',
                'description.min' => 'Minimal 5 karakter!',
                'sequence.required' => 'Urutan harus diisi!',
                'sequence.between' => 'Urutan harus berupa angka antara 1 sampai 100!',
            ]
        );

        $visions->candidate_id = $request->candidate_id;
        $visions->sequence = $request->sequence;
        $visions->description = $request->description;
        $visions->save();

        return redirect()->route('candidates.visions.index', $candidate->id)->withSuccess('Visi berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_candidate, $id_visions)
    {
        $candidate = Candidate::find($id_candidate);
        $vision = Vision::find($id_visions);

        if (!$candidate && !$vision) {
            abort(404);
        }

        $vision->delete();

        return redirect()->route('candidates.visions.index', $candidate->id)->withSuccess('Visi berhasil dihapus!');
    }
}
