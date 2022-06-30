<?php


namespace App\Models;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;

class FileValidation
{
    static $errors = [];
    // checkType, checkUnique
    public static function checkType(UploadedFile $file)
    {
        $typeFromName = substr($file->getClientOriginalName(), strlen($file->getClientOriginalName()) - 3); // example database.sql
        if ($typeFromName != "sql")
        {
            Session::flash('error', 'File must be in the format SQL');
            return self::$errors[] = ['error' => 'File must be in the format SQL'];
        }
    }

    public static function checkUnique(UploadedFile $file)
    {
        if (File::query()->where('name', $file->getClientOriginalName())->first())
        {
            Session::flash('error', 'File with the same name already exists');
            return self::$errors[] = ['error' => 'File with the same name already exists'];
        }
    }
}
