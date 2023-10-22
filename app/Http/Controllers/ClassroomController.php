<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::orderBy('name', 'ASC')
            ->get();

        return view('pages.classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('pages.classrooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255'
        ], [
            'name.required' => 'Nama kelas harus diisi!'
        ]);

        $classrooms = new Classroom;
        $classrooms->name = $request->name;
        $classrooms->save();

        return redirect()->route('classrooms.index')->withSuccess('Kelas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            abort(404);
        }

        return view('pages.classrooms.edit', compact('classroom'));
    }

    public function update($id, Request $request)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|max:255'
        ], [
            'name.required' => 'Nama kelas harus diisi!'
        ]);

        $classroom->name = $request->name;
        $classroom->save();

        return redirect()->route('classrooms.index')->withSuccess('Kelas berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            abort(404);
        }

        $classroom->delete();

        return redirect()->back()->withSuccess('Kelas berhasil ditambahkan!');
    }
}