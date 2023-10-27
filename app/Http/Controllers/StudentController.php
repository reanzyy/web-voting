<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\Classroom;
use App\Models\Vote;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index($id)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            abort(404);
        }

        $students = Student::where('classroom_id', '=', $id)
            ->orderBy('identity', 'ASC')
            ->get();

        return view('pages.classrooms.students.index', compact('classroom', 'students'));
    }

    public function create($id)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            abort(404);
        }

        return view('pages.classrooms.students.create', compact('classroom'));
    }

    public function store($id, Request $request)
    {
        $classroom = Classroom::find($id);

        if (!$classroom) {
            abort(404);
        }

        $request->validate(
            [
                'identity' => 'required|max:255',
                Rule::unique('students', 'identity')->where(function ($query) use ($classroom) {
                    return $query->where('identity', $classroom->id);
                }),
                'name' => 'required|max:255',
                'gender' => 'required|in:Laki-laki,Perempuan'
            ],
            [
                'identity.required' => 'NIS harus diisi!',
                'identity.max' => 'Maksimal 255 karakter!',
                'identity.unique' => 'NIS sudah digunakan!',
                'name.required' => 'Nama harus diisi!',
                'name.max' => 'Maksimal 255 karakter!',
                'gender.required' => 'Jenis kelamin harus diisi!',
                'gender.in' => 'Jenis kelamin harus berisi Laki-laki, Perempuan!',
            ]
        );

        $student = new Student;
        $student->classroom_id = $classroom->id;
        $student->identity = $request->identity;
        $student->name = $request->name;
        $student->gender = $request->gender;
        $student->status = 'Belum Memilih';
        $student->save();

        return redirect()->route('classrooms.students.index', $classroom->id)->withSuccess('Siswa berhasil ditambahkan!');
    }

    public function edit($id_classroom, $id_student)
    {
        $classroom = Classroom::find($id_classroom);
        $student = Student::find($id_student);

        if (!$classroom && !$student) {
            abort(404);
        }

        return view('pages.classrooms.students.edit', compact('classroom', 'student'));
    }

    public function update($id_classroom, $id_student, Request $request)
    {
        $classroom = Classroom::find($id_classroom);
        $student = Student::find($id_student);

        if (!$classroom && !$student) {
            abort(404);
        }

        $request->validate(
            [
                'identity' => 'required|max:255',
                'name' => 'required|max:255',
                'gender' => 'required|in:Laki-laki,Perempuan'
            ],
            [
                'identity.required' => 'NIS harus diisi!',
                'identity.max' => 'Maksimal 255 karakter!',
                'name.required' => 'Nama harus diisi!',
                'name.max' => 'Maksimal 255 karakter!',
                'gender.required' => 'Jenis kelamin harus diisi!',
                'gender.in' => 'Jenis kelamin harus berisi Laki-laki, Perempuan!',
            ]
        );

        $student->identity = $request->identity;
        $student->name = $request->name;
        $student->gender = $request->gender;
        $student->save();

        return redirect()->route('classrooms.students.index', $classroom->id)->withSuccess('Siswa berhasil diubah!');
    }

    public function destroy($id_classroom, $id_student)
    {
        $classroom = Classroom::find($id_classroom);
        $student = Student::find($id_student);

        if (!$classroom && !$student) {
            abort(404);
        }

        $student->delete();

        return redirect()->route('classrooms.students.index', $classroom->id)->withSuccess('Siswa berhasil dihapus!');
    }

    public function import(Request $request, $classroomId)
    {
        $classroom = Classroom::find($classroomId);

        $request->validate([
            'import_file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new StudentsImport($classroom), $request->file('import_file'));
            return redirect()->route('classrooms.students.index', $classroom->id)->with('success', 'Data siswa berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('classrooms.students.index', $classroom->id)->with('error', 'Terjadi kesalahan saat mengimpor data siswa.' . $e->getMessage());
        }
    }

    public function reset($id_classroom, $id_student)
    {
        $classroom = Classroom::find($id_classroom);
        $student = Student::find($id_student);

        if (!$classroom && !$student) {
            abort(404);
        }

        $vote = Vote::where('student_id', $student->id)->first();
        $vote->delete();

        $student->status = 'Belum Memilih';
        $student->save();

        return redirect()->back()->withSuccess('Status berhasil direset!');
    }
}
