<?php
//
//namespace App\Imports;
//
//use App\Models\Classes;
//use App\Models\Section;
//use App\Models\Student;
//use Maatwebsite\Excel\Concerns\Importable;
//use Maatwebsite\Excel\Concerns\ToModel;
//use Maatwebsite\Excel\Concerns\WithHeadingRow;
//
//class StudentsImport implements ToModel, withHeadingRow
//{
//    use Importable;
//
//    /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */
//    public function model(array $row)
//    {
//        $class_id = self::getClassId($row['class']);
//        $section_id = self::getSectionId($class_id, $row['section']);
//
//        return new Student([
//            //
//            'name' => $row['name'],
//            'email' => $row['email'],
//            'class_id' => $class_id,
//            'section_id' => $section_id,
//        ]);
//    }
//    public static function getClassId($class)
//    {
//        return Classes::where('name', $class)->first()->id;
//    }
//
//    public static function getSectionId($class_id, $section)
//    {
//        return Section::where('name', $section)->where('class_id', $class_id)->first()->id;
//    }
//
//
//}

namespace App\Imports;

use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $class_id = self::getClassId($row['class']);
        $section_id = self::getSectionId($class_id, $row['section']);

        return new Student([
            'name' => $row['name'],
            'email' => $row['email'],
            'class_id' => $class_id,
            'section_id' => $section_id,
        ]);
    }

    public static function getClassId($class)
    {
        return Classes::where('name', $class)->first()->id;
    }

    public static function getSectionId($class_id, $section)
    {
        return Section::where('name', $section)->where('class_id', $class_id)->first()->id;
    }
}
