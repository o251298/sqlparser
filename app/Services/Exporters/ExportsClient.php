<?php
/**
 * @author Oleg (251298@gmail.com)
 */

namespace App\Services\Exporters;


use App\Services\Exporters\CSV\V2CSVExporter;
use App\Services\Exporters\XML\XMLExporter;

class ExportsClient
{
    public $source;
    public static $allTableName = 'export_all_';

    public function __construct(IExport $source)
    {
        $this->source = $source;
        $this->export();
    }

    public function export()
    {
        return $this->source->export();
    }

    public function getLink()
    {
        return $this->source->getLink();
    }

    public static function setSource(string $format, array $table, $filename)
    {
        switch ($format){
            case ("csv"):
                $source = new V2CSVExporter($table, $filename);
                break;
            case ("xml"):
                $source = new XMLExporter($table, $filename);
                break;
        }
        return new self($source);
    }
}
