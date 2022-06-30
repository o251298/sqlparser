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
            try {
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
                            $exporter = ExportsClient::setSource($this->format, $sql->select, $file->name . '_' . $sql->table);
                            $this->response[$file->name][] = $exporter->getLink();
                        }
                    }
                }
            } catch (\Exception $exception)
            {
                echo $exception->getMessage();
                die();
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
