<?php


namespace App\Services\Exporters;


class BasicExporter
{
    protected $data;
    protected $filename;
    protected $link;
    protected $file_name;
    protected $file;

    public function __construct(array $data, $filename)
    {
        $this->data = $data;
        $this->filename = $filename;
    }

    private function openFile()
    {

    }

    public function export()
    {

    }

}
