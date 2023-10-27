<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SchoolYearController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolYear::query();
        $school_years = $query->orderBy('is_active', 'desc')->get();

        return view('pages.schoolYears.index', compact('school_years'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:school_years,name',
            'is_active' => 'required|boolean',
        ];

        $messages = [
            'name.required' => 'Tahun pelajaran harus diisi!',
            'name.max' => 'Maksimal 255 karakter!',
            'name.unique' => 'Tahun pelajaran telah digunakan!',
            'is_active.required' => 'Status harus diisi!',
            'is_active.boolean' => 'Status hanya boleh diisi Aktif / Tidak Aktif!',
        ];

        if ($request->is_active) {
            SchoolYear::where('is_active', true)->update(['is_active' => false]);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('school-years.index')->withErrors($validator, 'schoolYearErrors')->withInput();
        } else {
            $school_year = new SchoolYear;
            $school_year->name = $request->name;
            $school_year->is_active = $request->is_active;
            $school_year->save();

            return redirect()->route('school-years.index')->withSuccess('Tahun pelajaran berhasil ditambahkan!');
        }
    }

    public function update($id, Request $request)
    {
        $schoolYear = SchoolYear::find($id);
        abort_if(!$schoolYear, 404, 'Tahun Pelajaran tidak ditemukan');

        $rules = [
            'name' => 'required|max:255|unique:school_years,name',
            'is_active' => 'required|boolean',
        ];

        $messages = [
            'name.required' => 'Tahun pelajaran harus diisi!',
            'name.max' => 'Maksimal 255 karakter!',
            'name.unique' => 'Tahun pelajaran telah digunakan!',
            'is_active.required' => 'Status harus diisi!',
            'is_active.boolean' => 'Status hanya boleh diisi Aktif / Tidak Aktif!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('school-years.index')->withErrors($validator, 'schoolYearErrors')->withInput();
        }

        $schoolYear->name = $request->name;

        if ($request->is_active && !$schoolYear->is_active) {
            SchoolYear::where('id', '!=', $schoolYear->id)->update(['is_active' => false]);
        }

        $schoolYear->is_active = $request->is_active;
        $schoolYear->save();

        return redirect()->route('school-years.index')->withSuccess('Tahun Pelajaran berhasil diedit.');
    }


    public function destroy(SchoolYear $schoolYear, $id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        abort_if(!$schoolYear, 400, 'Tahun Pelajaran tidak ditemukan');

        $schoolYear->delete();

        return redirect()->route('school-years.index')->withSucces('Tahun Pelajaran berhasil dihapus.');
    }
}
