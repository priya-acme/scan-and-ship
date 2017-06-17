<?php
echo "stores";
include 'db_connection.php';
class Stores extends db_connection {
    
  private $table_name = 'stores';

public function __construct() {
    parent :: __construct();
}  
function addData($data){
    //print_r($data);
    return $this->add($this->table_name , $data);
}
function is_shop_exists($shop){
    //echo $shop;
      return $this->select($this->table_name , "*" ,"store_url='$shop'");  
}
 public function updateData($data, $criteria)
    {
        return $this->update($this->table_name, $data, $criteria);
    }
}
?>