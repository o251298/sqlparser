<?php


namespace App\Services\Parsers\SQLParser;


use App\Services\DB\DataBase;

class SQLQuery
{
    protected $source;
    public $table;
    private $db;
    public $select;
    protected $conditions = ['post_title', 'post_content'];
    public function __construct(SQLParseFile $file, $tableName)
    {
        $this->source = $file;
        $this->table = $tableName;
        $this->db = DataBase::connect();
        $this->dropTable();
        $this->createTable();
        $this->insertTable();
        $this->selectTable();
    }

    private function dropTable()
    {
        $table = $this->source->getDropTables()[$this->table];
        $statement = $this->db->query($table);
    }
    private function createTable()
    {
        $set = implode(';', $this->source->getSetVal());
        $table = $this->source->getCreateTables()[$this->table];
        $sql = $set . ';' . $table;
        $s = $this->db->query($sql);
    }
    private function insertTable()
    {
        $set = implode(';', $this->source->getSetVal());
        $table = $this->source->getInsertTables()[$this->table];
        $sql = $set . ';' . $table;
        $s = $this->db->query($sql);
    }

    private function selectTable()
    {
        $sql = "SELECT * FROM $this->table";
        $s = $this->db->query($sql);
        $i = 0;
        while ($row = $s->fetch())
        {
            foreach ($this->conditions as $condition)
            {
                $this->select[$i][$condition] = stripslashes(strip_tags($row[$condition]));
            }
            $i++;
        }
        $this->dropAfterSelect("DROP TABLE $this->table");
    }
    private function dropAfterSelect($sql)
    {
        $this->db->query($sql);
    }
}
