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

                $row = strtolower($row);
                $existStudent = Student::where('identity', $row['nis'])->first();

                if ($existStudent) {
                    throw new Exception('NIS ' . $row['nis'] . ' sudah ada');
                }

                if ($row['nis'] !== null && $row['nama'] !== null && $row['jenis_kelamin'] !== null) {
                    Student::create([
                        'classroom_id' => $this->classroom->id,
                        'identity' => $row['nis'],
                        'name' => $row['nama'],
                        'gender' => $row['jenis_kelamin'],
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
