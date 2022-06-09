<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Session;
use App\Services\Exporters\CSVExporter;
use App\Services\Parsers\SQLParser\SQLParseFile;
use App\Services\Parsers\SQLParser\SQLQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileRequest;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = File::all();
        return view('pages.files.index', ['files' => $files]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.files.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FileRequest $request)
    {
        if($request->hasfile('file'))
        {
            foreach($request->file('file') as $file)
            {
                $typeFromName = substr($file->getClientOriginalName(), strlen($file->getClientOriginalName()) - 3); // example database.sql
                if ($typeFromName != "sql")
                {
                    Session::flash('error', 'Файл повинен бути SQL');
                    return redirect()->back();
                }
                $folder = 'uploads';
                $name = $time = date('Y-m-d_H:m') . '_' .$file->getClientOriginalName();
                $file->move(public_path($folder), $name);
                $sql_file = File::create([
                    'name' => $file->getClientOriginalName(),
                    'url' => "../public/$folder/" . $name
                ]);
                $data[] = $sql_file;
            }
            $serialize = serialize($data);
        }
        return redirect()->back()->with('files', $serialize);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
    }

    public function parse(Request $request)
    {
        dd(1);
        $response = [];
        // select what method to export data (csv, xml, txt)
        // if isset EXPORT_TO_ONE_FILE -> to export one file (array_merge())

        $files = $request->get('file_to_parse');
        foreach (array_values($files) as $file_id) {
            $obj_files[] = File::find($file_id);
        }
        foreach ($obj_files as $file)
        {
            $sqlParser = new SQLParseFile($file);
            foreach (array_keys($sqlParser->getInsertTables()) as $tableName)
            {
                if (SQLParseFile::checkPostsTable($tableName))
                {
                    $sql = new  SQLQuery($sqlParser, $tableName);
                    $exporter = new CSVExporter($sql->select, $sql->table);
                    $response[$file->name][] = $exporter->getLink();
                    Storage::download($exporter->getLink());
                }
            }
        }
        return view('pages.files.upload', ['files' => $response]);

    }



    public function sql(Request $request)
    {
        $sqls = $request->get('sql');
        foreach ($sqls as $obj)
        {
            $sql = unserialize($obj);
            dump($sql);
        }
    }
}
