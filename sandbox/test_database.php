<?php
class Test_DB {
	private $db;
	
	function __construct() {
		$this->connect_db();
		
	}
	
	private function connect_db() {
		$this->db = mysqli_connect("localhost", "db_1242_access_priv", "7jp7miXZ88ucQPYg", "db_1242_tjpass");
		if (mysqli_connect_errno()) {
			die("Error connecting to database! " . mysqli_connect_errno() . " :: " . mysqli_connect_error());
		}
	}

}

$base = new Test_DB();
