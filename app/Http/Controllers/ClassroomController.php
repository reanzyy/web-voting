<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('classrooms', 'name')->where(function ($query) use ($schoolYears) {
                    return $query->where('school_year_id', $schoolYears->id);
                }),
            ]
        ];

        $messages = [
            'name.unique' => 'Kelas sudah digunakan!',
            'name.required' => 'Kelas harus diisi!',
            'name.max' => 'Maksimal 255 karakter!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('classrooms.index')->withErrors($validator, 'classroomErrors')->withInput();
        } else {
            $classrooms = new Classroom;
            $classrooms->school_year_id = $schoolYears->id;
            $classrooms->name = $request->name;
            $classrooms->save();

            return redirect()->route('classrooms.index')->withSuccess('Kelas berhasil ditambahkan!');
        }
    }

    public function update($id, Request $request)
    {
        $classroom = SchoolYear::find($id);
        abort_if(!$classroom, 404, 'Kelas tidak ditemukan');
        $schoolYears = SchoolYear::where('is_active', true)->first();

        $rules = [
            'name' => 'required|max:255'
        ];

        $messages = [
            'name.required' => 'Kelas harus diisi!',
            'name.max' => 'Maksimal 255 karakter!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('classrooms.index')->withErrors($validator, 'classroomErrors')->withInput();
        } else {
            $classroom->school_year_id = $schoolYears->id;
            $classroom->name = $request->name;
            $classroom->save();

            return redirect()->route('classrooms.index')->withSuccess('Kelas berhasil diedit!');
        }
    }

    public function destroy($id)
    {
        $classroom = SchoolYear::find($id);
        abort_if(!$classroom, 404, 'Kelas tidak ditemukan');

        $classroom->delete();

        return redirect()->back()->withSuccess('Kelas berhasil ditambahkan!');
    }
}
