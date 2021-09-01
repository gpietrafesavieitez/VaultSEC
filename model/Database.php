<?php header("Content-Type: text/html; charset=utf-8");

$db = parse_ini_file("config/db.ini");
define("DB_HOST", $db["host"]);
define("DB_NAME", $db["name"]);
define("DB_USER", $db["user"]);
define("DB_PASS", $db["pass"]);
define("DB_CHAR", $db["char"]);

class Database{

    protected static $instance = null;
    protected function __construct() {}
    protected function __clone() {}
    
    public static function instance(){
        if(self::$instance === null){
            $opts = array(  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES => FALSE,
            );
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR;
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $opts);
        }
        return self::$instance;
    }

    public static function __callStatic($method, $args){
        return call_user_func_array(array(self::instance(), $method), $args);
    }

    public static function run($sql, $args = []){
        if(!$args){
            return self::instance()->query($sql);
        }
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

}

const SQL = [

    "ADDUSER" => 
        "INSERT INTO vs_users(uid, displayname, password, role) VALUES (?, ?, ?, ?);",
        
    "ADDFILE" => 
        "INSERT INTO vs_storage(checksum, path, name, mimetype, user, size) VALUES (?, ?, ?, ?, ?, ?);",
        
    "GETALLROLES" => 
        "SELECT rid,type FROM vs_roles;",

    "GETALLUSERS" => 
        "SELECT vs_users.uid, vs_users.displayname, vs_roles.type, vs_users.creation FROM vs_users INNER JOIN vs_roles ON vs_users.role = vs_roles.rid WHERE uid <> ?;",
        
    "GETUSERBYUID" => 
        "SELECT vs_users.uid, vs_users.displayname, vs_users.password, vs_roles.type FROM vs_users INNER JOIN vs_roles ON vs_users.role = vs_roles.rid WHERE vs_users.uid=? LIMIT 1;",

    "GETPASSBYUID" => 
        "SELECT password FROM vs_users WHERE uid=? LIMIT 1;",

    "UPDATEPASSBYUID" => 
        "UPDATE vs_users SET password=? WHERE uid=?;",
    
    "UPDATEUSERBYUID" => 
        "UPDATE vs_users SET displayname=?, role=? WHERE uid=?;",

    "GETFILEBYOWNER" => 
        "SELECT * FROM vs_storage WHERE checksum=? AND user=? LIMIT 1;",

    "GETMIMEBYNAME" => 
        "SELECT mid FROM vs_mimetypes WHERE name=? LIMIT 1;",

    "REMOVEFILEBYOWNER" => 
        "DELETE FROM vs_storage WHERE checksum=? AND user=?;",

    "REMOVEUSERBYUID" => 
        "DELETE FROM vs_users WHERE uid=?;",

    "UPDATEFILEBYOWNER" => 
        "UPDATE vs_storage SET path=? WHERE checksum=? AND user=?;",

];

?>
