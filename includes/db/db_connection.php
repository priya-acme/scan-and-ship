<?php
echo 'in DBC';
// require __DIR__ . '../../config/conf.inc.php';

require '/var/www/html/scan-and-ship/includes/config/conf.inc.php';
class DB_Connection{
	
	protected $connection;
	
	
	public function connect()
	{
		echo 'inside connect';
		$this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if($this->connection)
		{
			echo 'CONNNNNECTED';
		}
		else {
			echo 'NOPE NOT CONNECTED'. mysqli_connect_error();
		}
		
		return $this->connection;
	}
	
	public function add($table, $data)
	{
		//echo "inside db add";
		$query = "INSERT INTO $table ";
		
		$columns = [];
		$values = [];
		foreach($data as $column => $value) {
			$columns[] = $column;
			$values[] = $value;
			
			//echo 'col:'.$column;
			//echo 'val:'.$value;
			
		}
		
		$query .= "(" . implode(", ", $columns) . ")";
		$query .= "VALUES(" . implode(", ", $values) . ")";
		
		
		$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		mysqli_query($connection, $query) or die(mysqli_error($connection));
		
		return mysqli_insert_id($connection);
		if($connection)
			echo "conn established";
			else
				echo 'not established';
				
	}
	public function select($table_name, $columns = "*", $criteria = null)
	{
		//echo "inside db select".$table_name;
		$query = "SELECT $columns FROM $table_name";
		
		if (!empty($criteria)) {
			$query .= " WHERE $criteria";
		}
		
		
		$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$result = mysqli_query($connection, $query) or die(mysqli_error($connection));
		
		return mysqli_fetch_all($result);
		
	}
	
}

