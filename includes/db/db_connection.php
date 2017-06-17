<?php
include __DIR__ .'../../config/conf.inc.php';
//echo "hello";
//$host = "host=ec2-54-243-185-123.compute-1.amazonaws.com";
//$dbname= "dbname=dftadju73ao8b";
//$port = "port=5432";
//$credentials = "user=wavptfphsawxvl password=f197258ef0d018d06137e62f49428d88807a2a3b1aca06ec0419276f40eb51bf"; 
//$db = pg_connect("$host $port $dbname $credentials");
//if(!$db){
//    echo "Not Connected";
//}
//else {
//echo "Connected";    
//}
//echo "db";
class db_connection
{
    private $conn;
    function __construct() {
      if(APP_ENV == 'local'){
          $this->conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
      }
      elseif(APP_ENV == 'heroku'){
          $host = "host=ec2-54-243-185-123.compute-1.amazonaws.com";
          $dbname= "dbname=dftadju73ao8b";
          $port = "port=5432";
          $credentials = "user=wavptfphsawxvl password=f197258ef0d018d06137e62f49428d88807a2a3b1aca06ec0419276f40eb51bf"; 

          $this->conn = pg_connect("$host $port $dbname $credentials");
          //$this->conn = pg_connect('host='.DB_HOST.'dbname='.DB_NAME.'user='.DB_USER.'pass='.DB_PASS);
     }
    }
    function add($table,$data){
        $query = "INSERT INTO $table (store_url,access_key,token,created_at) VALUES (";
        $columns = [];
        foreach($data as $column => $value){
            $columns[] = "'$value'";
       }
        $query .= implode(",",$columns).")";
        if(APP_ENV == 'local'){
            $result = mysqli_query($this->conn , $query);
            return mysqli_insert_id($result);
        }
        elseif(APP_ENV == 'heroku'){
            //echo $query;
           $result = pg_query($this->conn , $query);
           //return pg_last_oid($result);
           
          // echo "Data Inserted";
        }
    }
    function select($table,$columns = "*" ,$criteria = null){
        $query = "SELECT $columns from $table ";
        if(!empty($criteria)){
            $query .= "WHERE $criteria";
        if(APP_ENV == 'local'){
            $result = mysqli_query($this->conn , $query);
            return mysqli_insert_id($result);
        }
        elseif(APP_ENV == 'heroku'){
          //echo $query;
          $result = pg_query($this->conn , $query);
          return pg_fetch_all($result);
        }
        }

    }
     public function update($table, $data, $criteria)
    {
        $query = "UPDATE $table SET ";
        
        $columns = [];
        foreach($data as $column => $value) {
            $columns[] = "$column = $value";
        }
        
        $query .= implode(", ", $columns) . ")";
        
        if (!empty($criteria)) {
            $query .= " WHERE $criteria";
        }
        
        if (APP_ENV == "local") {
            $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            mysqli_query($connection, $query) or die(mysqli_error($connection));
            
            return mysqli_insert_id($this->conn);
        } elseif (APP_ENV == "heroku") {
            $result = pg_query($this->conn, $query);
            
            return pg_last_oid($result);
        }
    }
}
?>

