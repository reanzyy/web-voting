<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $data = $request->validate([
            'name' => 'required|unique:school_years',
            'is_active' => 'required|boolean',
        ],  [
            'name.required' => 'Tahun pelajaran harus diisi!',
            'name.unique' => 'Tahun pelajaran telah digunakan!',
            'is_active.required' => 'Status harus diisi!',
            'is_active.boolean' => 'Status hanya boleh diisi aktif / tidak aktif!',
        ]);

        DB::beginTransaction();

        try {
            if ($data['is_active']) {
                SchoolYear::where('is_active', true)->update(['is_active' => false]);
            }

            SchoolYear::create($data);

            DB::commit();

            return redirect()->route('school-years.index')->withSuccess('Tahun Pelajaran berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function update($id, Request $request)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        abort_if(!$schoolYear, 400, 'Tahun Pelajaran tidak ditemukan');

        $data = $request->validate([
            'name' => 'required',
            'is_active' => 'required|boolean',
        ],  [
            'name.required' => 'Tahun pelajaran harus diisi!',
            'name.unique' => 'Tahun pelajaran telah digunakan!',
            'is_active.required' => 'Status harus diisi!',
            'is_active.boolean' => 'Status hanya boleh diisi aktif / tidak aktif!',
        ]);

        DB::beginTransaction();

        try {
            if ($data['is_active']) {
                SchoolYear::where('is_active', true)->where('id', '!=', $schoolYear->id)->update(['is_active' => false]);
            }

            $schoolYear->update($data);

            DB::commit();

            return redirect()->route('school-years.index')->withSuccess('Tahun Pelajaran berhasil diedit.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function destroy(SchoolYear $schoolYear, $id)
    {
        $schoolYear = SchoolYear::findOrFail($id);
        abort_if(!$schoolYear, 400, 'Tahun Pelajaran tidak ditemukan');

        $schoolYear->delete();

        return redirect()->route('school-years.index')->withSucces('Tahun Pelajaran berhasil dihapus.');
    }
}
