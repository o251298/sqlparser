<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileSQLParse;
use App\Models\FileStore;
use App\Models\FileValidation;
use App\Services\Exporters\ExportsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileRequest;

class FileController extends Controller
{

    public function index()
    {
        return view('pages.files.index', ['files' => File::all()]);
    }

    public function create()
    {
        return view('pages.files.create');
    }

    public function store(FileRequest $request)
    {
        if($request->hasfile('file'))
        {
            foreach($request->file('file') as $file)
            {
                FileValidation::checkType($file);
                FileValidation::checkUnique($file);
                if(FileValidation::$errors) return redirect()->back();
                $uploadFile = new FileStore($file);
                $data[] = $uploadFile->save();
            }
        }
        return redirect()->back()->with('files', serialize($data));
    }

    public function truncate()
    {
        foreach (['public/csv', 'public/xml'] as $path) {
            Storage::deleteDirectory($path);
        }
        return redirect()->route('files.list');
    }

    public function download(Request $request)
    {
        return Storage::download($request->link);
    }

    public function destroy(File $file)
    {
        Storage::delete($file->url);
        $file->delete();
        return redirect()->back();
    }

    public function parse(Request $request)
    {
        // if none files = error
        if (empty($request->get('file_to_parse'))) return redirect()->back()->with('error', 'Empty files');
        // flag to download all to one file
        $all = false;
        //
        if ($request->select === "yes") $all = true;
        $files_parsing = new FileSQLParse($request->get('file_to_parse'), $all, $request->export_format, $request->table);

        try {
            $files_parsing->parse();
            if ($files_parsing->saveToOneFile())
            {
                $exporter = ExportsClient::setSource($request->export_format, $files_parsing->getAllTables(), ExportsClient::$allTableName . date('Y_m_d'));
                return Storage::download($exporter->getLink());
            } else {
                return view('pages.files.upload', ['files' => $files_parsing->getResponse()]);
            }
        } catch (\Exception $exception)
        {
            Session::flash('error', "{$exception->getMessage()} IN FILE: {$exception->getFile()} IN LINE {$exception->getLine()}");
            return redirect()->back();
        }
    }

}
