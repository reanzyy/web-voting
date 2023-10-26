<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\SchoolYear;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $schoolYears = SchoolYear::all();
        $defaultYearId = SchoolYear::where('is_active', true)->value('id');
        $candidates = Candidate::query();
        if ($request->has('year')) {
            $candidates->where('school_year_id', $request->year);
        } else {
            $candidates->where('school_year_id', $defaultYearId);
        }
        $candidates = $candidates->get();

        return view('pages.candidates.index', compact('candidates', 'schoolYears', 'defaultYearId'));
    }

    public function create()
    {
        return view('pages.candidates.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'sequence' => 'required|int',
                'chairman' => 'required|max:255',
                'deputy_chairman' => 'required|max:255',
                'photo_chairman' => 'required|image|max:2048',
                'photo_deputy_chairman' => 'required|image|max:2048'
            ],
            [
                'sequence.required' => 'Nomer urut harus diisi!',
                'sequence.int' => 'Nomer urut harus berisi angka!',
                'chairman.required' => 'Calon ketua harus diisi!',
                'chairman.max' => 'Maksimal 255 karakter!',
                'deputy_chairman.required' => 'Calon wakil ketua harus diisi!',
                'deputy_chairman.max' => 'Maksimal 255 karakter!',
                'photo_chairman.required' => 'Foto calon ketua harus diisi!',
                'photo_deputy_chairman.required' => 'Foto calon wakil ketua harus diisi!',
                'photo_chairman.image' => 'Harus berformat foto!',
                'photo_deputy_chairman.image' => 'Harus berformat foto!',
                'photo_chairman.max' => 'Foto maksimal 2mb!',
                'photo_deputy_chairman.max' => 'Foto maksimal 2mb!'
            ]
        );

        $schoolYears = SchoolYear::where('is_active', true)->first();
        $candidate = new Candidate;

        if ($request->hasFile('photo_chairman') && !empty($request->photo_chairman)) {
            $file = $request->file('photo_chairman');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/photos', $fileName);

            $candidate->photo_chairman = "photos/{$fileName}";
        }

        if ($request->hasFile('photo_deputy_chairman') && !empty($request->photo_deputy_chairman)) {
            $file = $request->file('photo_deputy_chairman');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/photos', $fileName);

            $candidate->photo_deputy_chairman = "photos/{$fileName}";
        }

        $candidate->school_year_id = $schoolYears->id;
        $candidate->sequence = $request->sequence;
        $candidate->chairman = $request->chairman;
        $candidate->deputy_chairman = $request->deputy_chairman;
        $candidate->save();

        return redirect()->route('candidates.index')->withSuccess('Kandidat berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            abort(404);
        }

        return view('pages.candidates.edit', compact('candidate'));
    }

    public function update($id, Request $request)
    {
        $candidate = Candidate::find($id);
        $schoolYears = SchoolYear::where('is_active', true)->first();

        if (!$candidate) {
            abort(404);
        }

        $request->validate(
            [
                'sequence' => 'required|int',
                'chairman' => 'required|max:255',
                'deputy_chairman' => 'required|max:255',
                'photo_chairman' => 'nullable|image|max:2048',
                'photo_deputy_chairman' => 'nullable|image|max:2048'
            ],
            [
                'sequence.required' => 'Nomer urut harus diisi!',
                'sequence.int' => 'Nomer urut harus berisi angka!',
                'chairman.required' => 'Calon ketua harus diisi!',
                'chairman.max' => 'Maksimal 255 karakter!',
                'deputy_chairman.required' => 'Calon wakil ketua harus diisi!',
                'deputy_chairman.max' => 'Maksimal 255 karakter!',
                'photo_chairman.image' => 'Harus berformat foto!',
                'photo_deputy_chairman.image' => 'Harus berformat foto!',
                'photo_chairman.max' => 'Foto maksimal 2mb!',
                'photo_deputy_chairman.max' => 'Foto maksimal 2mb!'
            ]
        );

        if ($request->hasFile('photo_chairman') && !empty($request->photo_chairman)) {

            if ($candidate->photo_chairman) {
                Storage::delete('public/' . $candidate->photo_chairman);
            }

            $file = $request->file('photo_chairman');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/photos', $fileName);

            $candidate->photo_chairman = "photos/{$fileName}";
        }

        if ($request->hasFile('photo_deputy_chairman') && !empty($request->photo_deputy_chairman)) {

            if ($candidate->photo_deputy_chairman) {
                Storage::delete('public/' . $candidate->photo_deputy_chairman);
            }

            $file = $request->file('photo_deputy_chairman');
            $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/photos', $fileName);

            $candidate->photo_deputy_chairman = "photos/{$fileName}";
        }

        $candidate->school_year_id = $schoolYears->id;
        $candidate->sequence = $request->sequence;
        $candidate->chairman = $request->chairman;
        $candidate->deputy_chairman = $request->deputy_chairman;
        $candidate->save();

        return redirect()->route('candidates.index')->withSuccess('Kandidat berhasil diubah!');
    }

    public function destroy($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            abort(404);
        }

        if ($candidate->photo_chairman && $candidate->photo_deputy_chairman) {
            Storage::delete('public/' . $candidate->photo_chairman);
            Storage::delete('public/' . $candidate->photo_deputy_chairman);
        }

        $candidate->delete();

        return redirect()->route('candidates.index')->withSuccess('Kandidat berhasil dihapus!');
    }
}
