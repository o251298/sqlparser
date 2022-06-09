<?php


namespace App\Services\Exporters;


interface IExport
{
    public function export();
    public function getLink();
}
