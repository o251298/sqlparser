<?php


namespace App\Services\Parsers\SQLParser;


use App\Models\File;
use function Symfony\Component\Translation\t;

class SQLParseFile
{
    protected $create_tables = [];
    protected $drop_tables = [];
    protected $insert_tables = [];
    protected $setVal = [];
    protected $file;
    public $str;

    public function __construct(File $file)
    {
        $this->file = $file;
        $this->readFile();
        $this->parse();
    }

    protected function readFile()
    {
        $this->str = file_get_contents($this->file->url);
    }

    protected function parse()
    {
        foreach (SQLParser::getQueries($this->str) as $item)
        {
            if (preg_match('/SET /', $item))
            {
                $this->setVal[] = $item;
            }
            if(preg_match('/CREATE TABLE/', $item))
            {
                $strq = substr($item, 0, strpos($item, '('));
                $strq = (string) substr($strq, strpos($strq, '`'), strrpos($strq, '`'));
                $strq = trim(str_replace('`', '', $strq));
                $this->create_tables[$strq] = $item;
            }
            if(preg_match('/DROP TABLE/', $item))
            {
                $strq = (string) substr($item, strpos($item, '`'), strrpos($item, '`'));
                $strq = trim(str_replace('`', '', $strq));
                $this->drop_tables[$strq] = $item;
            }

            if(preg_match('/INSERT INTO/', $item))
            {
                $strq = substr($item, 0, strpos($item, '('));
                $strq = (string) substr($strq, strpos($strq, '`'), strrpos($strq, '`'));
                $strq = trim(str_replace('`', '', $strq));
                $this->insert_tables[$strq] = $item;
            }
        }
    }

    public function getCreateTables()
    {
        return $this->create_tables;
    }
    public function getDropTables()
    {
        return $this->drop_tables;
    }
    public function getInsertTables()
    {
        return $this->insert_tables;
    }
    public function getTableName()
    {
        return $this->file->name;
    }
    public function getSetVal()
    {
        return $this->setVal;
    }
    public static function checkPostsTable($str)
    {
        if (preg_match('/posts/', $str)) return true;
    }
}

