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
        //$this->csv();

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
        //$key = $this->source->getTableName() . '^%^' . $this->table;
        //$this->select[$key] = $s->fetchAll(\PDO::FETCH_ASSOC);
        $i = 0;
        while ($row = $s->fetch())
        {
            foreach ($this->conditions as $condition)
            {
                $this->select[$i][$condition] = stripslashes(strip_tags($row[$condition]));
            }
            $i++;
        }
    }

    protected function csv()
    {
        //$path = '../uploads/' . date('Y-m-d_H_i') . 'test.csv';
//        $path = public_path() . '/uploads/';
//        if (!is_dir($path)) mkdir($path, 0777,true);
//        $f = fopen($path . $this->source->getTableName() . '.csv', 'a+');
        $filename = $this->source->getTableName() . '_' . date('Y-m-d') . 'csv';
        $f = fopen('php://memory', 'a+');
        $delimiter = ';'; //parameter for fputcsv
        $enclosure = '"'; //parameter for fputcsv

        foreach ($this->select as $fields){
            fputcsv($f, $fields, $delimiter, $enclosure);
        }
        fseek($f, 0);
        header('Content-Type: text:csv');
        header('Content-Disposition: attachment; filename="' . $filename .'";');
        fpassthru($f);

        fclose($f);
    }
}
