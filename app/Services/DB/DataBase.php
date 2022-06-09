<?php
/**
 * @author Oleg (251298@gmail.com)
 */

namespace App\Services\DB;
use PDO;

class DataBase
{
    /**
     * @return PDO
     */
    public static function connect()
    {
        $dsn = 'mysql:dbname=sqlparser;host=db';
        $user = 'root';
        $password = 'testpassword1234';
        $dbh = new PDO($dsn, $user, $password);
        return $dbh;
    }
}
