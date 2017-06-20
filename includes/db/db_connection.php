<?php
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
	
}

