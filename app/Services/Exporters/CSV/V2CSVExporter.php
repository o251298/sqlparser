<?php


namespace App\Services\Exporters\CSV;


use Illuminate\Support\Facades\Storage;
use App\Services\Exporters\BasicExporter;
use App\Services\Exporters\IExport;

class V2CSVExporter extends BasicExporter implements IExport
{
    const PATH = '../storage/app/public/csv';

    public function __construct(array $data, $filename)
    {
        parent::__construct($data, $filename);
        $this->openFile();
    }

    public function export()
    {
        $delimiter = ';'; //parameter for fputcsv
        $enclosure = '"'; //parameter for fputcsv
        foreach ($this->data as $fields){
            fputcsv($this->file, $fields, $delimiter, $enclosure);
        }
        $this->link = substr($this->file_name, strpos($this->file_name, 'public'));
    }

    private function openFile()
    {
        if (!is_dir(self::PATH)) mkdir(self::PATH, 0777,true);
        $this->file_name = self::PATH . '/' .  $this->filename . '_' . date('Y_m_d') . '.csv';
        if (file_exists($this->file_name))
        {
            Storage::delete(substr($this->file_name, strpos($this->file_name, 'public')));
        }
        $this->file = fopen($this->file_name, 'a+');

    }

    private function closeFile()
    {
        fclose($this->file);
    }

    public function __destruct()
    {
        $this->closeFile();
    }

    public function getLink()
    {
        return $this->link;
    }

}
