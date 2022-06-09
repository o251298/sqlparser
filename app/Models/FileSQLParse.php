<?php


namespace App\Models;


use App\Services\ExporterClient;
use App\Services\Exporters\ExportsClient;
use App\Services\Exporters\CSV\V2CSVExporter;
use App\Services\Exporters\XML\XMLExporter;
use App\Services\Parsers\SQLParser\SQLParseFile;
use App\Services\Parsers\SQLParser\SQLQuery;

class FileSQLParse
{
    private $files;
    private $saveToOneFile;
    private $allTables = [];
    private $response;
    private $format;
    private $table;

    public function __construct(array $files, $saveToOneFile, $format, $table)
    {
        $this->files = $files;
        $this->format = $format;
        $this->table = $table;
        $this->saveToOneFile = $saveToOneFile;
    }

    private function getFileObj()
    {
        foreach (array_values($this->files) as $file_id) {
            yield File::find($file_id);
        }
    }

    public function parse()
    {
        foreach ($this->getFileObj() as $file)
        {
            $sqlParser = new SQLParseFile($file);
            foreach (array_keys($sqlParser->getInsertTables()) as $tableName)
            {
                if (SQLParseFile::checkPostsTable($tableName, $this->table))
                {
                    $sql = new  SQLQuery($sqlParser, $tableName);
                    if ($this->saveToOneFile)
                    {
                        $this->allTables = array_merge($this->allTables, $sql->select);
                    } else {
                        // select type
                        switch ($this->format){
                            case ('csv'):
                                $source = new V2CSVExporter($sql->select, $file->name . '_' . $sql->table);
                                break;
                            case ("xml"):
                                $source = new XMLExporter($sql->select, $file->name . '_' . $sql->table);
                                break;
                        }
                        $exporter = new ExportsClient($source);
                        $this->response[$file->name][] = $exporter->getLink();
                    }
                }
            }
        }
    }
    public function getResponse()
    {
        return $this->response;
    }

    public function saveToOneFile()
    {
        return $this->saveToOneFile;
    }

    public function getAllTables()
    {
        return $this->allTables;
    }
}
