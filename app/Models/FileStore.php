<?php


namespace App\Models;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;

class FileStore
{
    private $path = "public/files_db/";
    private $file;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function save()
    {
        $this->file->storeAs($this->path , $this->file->getClientOriginalName());
        $file = File::create([
            'name' => $this->file->getClientOriginalName(),
            'url' => $this->path . $this->file->getClientOriginalName()
        ]);
        return $file;
    }
}
