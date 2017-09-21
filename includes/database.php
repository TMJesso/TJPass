<?php
require_once LIB_PATH.DS.'config.php';
class Cypress extends Common {
	private $db;
	
	function __construct() {
		$this->connect_db();
		$this->check_database();
	}
	
	private function connect_db() {
		$this->db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT, DB_SOCKET);
		if (mysqli_connect_errno()) {
			die ("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" );
		}
	}
	
	public function close_connection() {
		if (isset($this->db)) {
			mysqli_close($this->db);
			unset($this->db);
		}
	}
	
	public function query($sql) {
		$result = mysqli_query( $this->db, $sql);
		// test if there was a query error
		$this->confirm_query($result);
		return $result;
	}
	
	public function confirm_query($result) {
		if (!$result) {
			die( "Database query failed. Error Code: " . mysqli_errno($this->db) . ". Error: " . mysqli_error($this->db));
		}
		
	}
	
}

$base = new Cypress();
