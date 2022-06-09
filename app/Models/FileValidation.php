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
            Session::flash('error', 'Файл повинен бути SQL');
            self::$errors[] = ['error' => 'file must have been to sql'];
        }
    }

    public static function checkUnique(UploadedFile $file)
    {
        if (File::query()->where('name', $file->getClientOriginalName())->first())
        {
            Session::flash('error', 'файл с таким названием уже существует');
            self::$errors[] = ['error' => 'файл с таким названием уже существует'];
        }
    }
}
