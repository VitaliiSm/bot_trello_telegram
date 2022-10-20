<?php
class Database{
private static $_connection = false;
private static $host = "";
private static $db_name = "";
private static $username = "";
private static $password = "";
public static function getConnection(){
try{
self::$_connection = new \mysqli(self::$host, self::$username, self::$password, self::$db_name);
}catch(MySqlException $exception){
echo "Connection error: " . $exception->getMessage();
}
return self::$_connection;
}
}
