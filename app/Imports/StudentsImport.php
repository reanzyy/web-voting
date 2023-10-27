<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\Student;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    protected $classroom;

    public function __construct(Classroom $classroom)
    {
        $this->classroom = $classroom;
    }

    public function collection(Collection $rows)
    {
        try {
            DB::beginTransaction();
            foreach ($rows as $row) {
                $existStudent = Student::where('identity', strtolower($row['nis']))
                    ->where('classroom_id', $this->classroom->id)
                    ->first();

                if ($existStudent) {
                    throw new Exception('NIS ' . $row['nis'] . ' sudah ada');
                }

                if (strtolower($row['nis']) !== null && $row['nama'] !== null && strtolower($row['jenis_kelamin']) !== null) {
                    Student::create([
                        'classroom_id' => $this->classroom->id,
                        'identity' => strtolower($row['nis']),
                        'name' => $row['nama'],
                        'gender' => strtolower($row['jenis_kelamin']),
                        'status' => 'Belum Memilih',
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
