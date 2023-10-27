<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index(Request $request)
    {
        $schoolYears = SchoolYear::all();
        $defaultYearId = SchoolYear::where('is_active', true)->value('id');
        $classrooms = Classroom::query();
        if ($request->has('year')) {
            $classrooms->where('school_year_id', $request->year);
        } else {
            $classrooms->where('school_year_id', $defaultYearId);
        }
        $classrooms = $classrooms->get();

        return view('pages.classrooms.index', compact('classrooms', 'schoolYears', 'defaultYearId'));
    }

    public function store(Request $request)
    {
        $schoolYears = SchoolYear::where('is_active', true)->first();
        $request->validate([
            'name' => 'required|max:255'
        ], [
            'name.required' => 'Nama kelas harus diisi!',
            'name.max' => 'Maksimal 255 karakter!'
        ]);

        $classroom = new Classroom;
        $classroom->school_year_id = $schoolYears->id;
        $classroom->name = $request->name;
        $classroom->save();

        return redirect()->route('classrooms.index')->withSuccess('Kelas berhasil ditambahkan!');
    }

    public function update($id, Request $request)
    {
        $classroom = Classroom::find($id);
        $schoolYears = SchoolYear::where('is_active', true)->first();

        if (!$classroom) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|max:255'
        ], [
            'name.required' => 'Nama kelas harus diisi!',
            'name.max' => 'Maksimal 255 karakter!'
        ]);

        $classroom->school_year_id = $schoolYears->id;
        $classroom->name = $request->name;
        $classroom->save();

        return redirect()->route('classrooms.index')->withSuccess('Kelas berhasil diubah!');
    }

    public function destroy($id)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            abort(404);
        }

        $classroom->delete();

        return redirect()->back()->withSuccess('Kelas berhasil dihapus!');
    }
}