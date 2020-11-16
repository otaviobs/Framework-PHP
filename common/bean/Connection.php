<?php
namespace postgres;

class Connection{
  private static $conn;

  public function connect(){
    $connString = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
                $paramDb['host'],
                $paramDb['port'],
                $paramDb['database'],
                $paramDb['user'],
                $paramDb['password']);
 
    $pdo = new \PDO($connString);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    return $pdo;
  }

  public static function get() {
    if (null === static::$conn) {
        static::$conn = new static();
    }

    return static::$conn;
  }

}
?>