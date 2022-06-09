<?php

// UNWORK VIRSION
namespace App\Services\Exporters;


use App\Services\Parsers\SQLParser\SQLQuery;

class CSVExporter
{
    protected $data;
    protected $filename;
    protected $link;
    public function __construct(array $data, $filename)
    {
        $this->data = $data;
        $this->filename = $filename;
        //$this->export();
    }

    public function export()
    {
        // сохраняет файл и возвращает строку о месте, где лежит данный файл
        $path = public_path() . '/uploads/';
        if (!is_dir($path)) mkdir($path, 0777,true);
        $f = fopen($path . date('Y-m-d_H_i') . $this->filename . '.csv', 'a+');
        $delimiter = ';'; //parameter for fputcsv
        $enclosure = '"'; //parameter for fputcsv
        foreach ($this->data as $fields){
            fputcsv($f, $fields, $delimiter, $enclosure);
        }
        fclose($f);
        $this->link = $path . date('Y-m-d_H_i') . $this->filename . '.csv';
    }

    public function getLink()
    {
        return $this->link;
    }
}
