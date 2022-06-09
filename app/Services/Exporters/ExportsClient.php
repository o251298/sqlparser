<?php


namespace App\Services\Exporters;


class ExportsClient
{
    public $source;
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
}
