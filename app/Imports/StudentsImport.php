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

                if (!isset($row['nis'])) {
                    throw new Exception(' NIS tidak ada');
                }

                if (!isset($row['nama'])) {
                    throw new Exception(' Nama tidak ada');
                }

                if (!isset($row['jenis_kelamin'])) {
                    throw new Exception(' Jenis Kelamin tidak ada');
                }

                $status = $row['status'] ?? 'Belum Memilih';

                $existStudent = Student::where('identity', $row['nis'])->first();

                if ($existStudent) {
                    throw new Exception('NIS ' . $row['nis'] . ' sudah ada');
                }

                if ($row['nis'] !== null && $row['nama'] !== null && $row['gender'] !== null) {
                    Student::create([
                        'classroom_id' => $this->classroom->id,
                        'identity' => $row['nis'],
                        'name' => $row['nama'],
                        'gender' => $row['jenis_kelamin'],
                        'status' => $status,
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
