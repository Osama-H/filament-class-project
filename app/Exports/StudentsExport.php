<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithMapping, WithHeadings
{

    use Exportable;


    public function __construct(public Collection $records) {}


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->records;
    }


    public function map($students): array
    {
        return [
            $students->name,
            $students->email,
            $students->class->name,
            $students->section->name
        ];
    }


    // based on the map function .. 
    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Class Name',
            'Section Name'
        ];
    }
}
