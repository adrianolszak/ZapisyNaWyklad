<?php
class dbConnnection 
{
	private $db = NULL;

public function __construct()
{
$this -> db_connect();
}
public function getDB(){
	return $this->db;
}
public function db_connect()
{
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "system";

	// Create connection
	$this->db = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($this->db->connect_error) {
		die("Connection failed: " . $this->db->connect_error);
	} 
}
public function closeConnection(){
	mysqli_close ($this->getDB());
}
}
?>