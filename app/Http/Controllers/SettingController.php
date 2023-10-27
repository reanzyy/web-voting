<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $query = Setting::query();
        $setting = $query->first();

        return view('pages.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::find(1);

        if (!$setting) {
            abort(404);
        }

        $data = $request->validate([
            'school_name' => 'required|max:255',
            'headmaster_name' => 'required|max:255',
            'deputy_headmaster_name' => 'required|max:255',
            'start_date' => 'date|required',
            'end_date' => 'date|required',
        ], [
            'school_name.required' => 'Nama sekolah harus diisi',
            'headmaster_name.required' => 'Kepala sekolah harus diisi',
            'deputy_headmaster_name.required' => 'Wakil kepala sekolah harus diisi',
            'start_date.required' => 'Tanggal mulai harus diisi',
            'end_date.required' => 'Tanggal akhir harus diisi',
            'headmaster_name.max' => 'Maksimal 255 karakter',
            'deputy_headmaster_name.max' => 'Maksimal 255 karakter',
        ]);

        $schoolData = [
            'school_name' => $data['school_name'],
            'headmaster_name' => $data['headmaster_name'],
            'deputy_headmaster_name' => $data['deputy_headmaster_name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ];

        if ($setting) {
            Setting::first()->update($schoolData);
        } else {
            Setting::create($schoolData);
        }

        return redirect()->back()->withSuccess("Pengaturan berhasil diubah!");
    }
}