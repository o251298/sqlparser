<?php


namespace App\Http\Controllers;


use App\Http\Requests\FileRequest;
use App\Models\File;
use App\Models\FileSQLParse;
use App\Models\FileStore;
use App\Models\FileValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\Exporters\CSV\V2CSVExporter;
use App\Services\Exporters\XML\XMLExporter;
use App\Services\Exporters\ExportsClient;

class V2FileController extends FileController
{
    public function store(FileRequest $request)
    {

        if($request->hasfile('file'))
        {
            // validation, store
            foreach($request->file('file') as $file)
            {
                FileValidation::checkType($file);
                FileValidation::checkUnique($file);
                if(FileValidation::$errors) return redirect()->back();
                $uploadFile = new FileStore($file);
                    $data[] = $uploadFile->save();
                }
            $serialize = serialize($data);
        }
        return redirect()->back()->with('files', $serialize);
    }

    public function parse(Request $request)
    {
        if (empty($request->get('file_to_parse'))) return redirect()->back()->with('error', 'empty files');
        $all = false;
        if ($request->select === "yes") $all = true;
        $files_parsing = new FileSQLParse($request->get('file_to_parse'), $all, $request->export_format, $request->table);
        $files_parsing->parse();
        if ($files_parsing->saveToOneFile())
        {
            switch ($request->export_format){
                case ("csv"):
                    $source = new V2CSVExporter($files_parsing->getAllTables(), 'export_all_' . date('Y_m_d'));
                    break;
                case ("xml"):
                    $source = new XMLExporter($files_parsing->getAllTables(), 'export_all_' . date('Y_m_d'));
                    break;
            }
            $exporter = new ExportsClient($source);
            return Storage::download($exporter->getLink());
        }
        return view('pages.files.upload', ['files' => $files_parsing->getResponse()]);
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

}
