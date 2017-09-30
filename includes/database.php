<?php
require_once LIB_PATH . 'config.php';
class Cypress extends Common {
	private $db;
	
	/** constructor
	 * 
	 */
	function __construct() {
		$this->connect_db();
		$this->check_database(false);
	}
	
	/** connect to database for this application
	 * 
	 */
	private function connect_db() {
		$this->db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME, DB_PORT, DB_SOCKET);
		if (mysqli_connect_errno()) {
			die ("Database connection failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" );
		}
	}
	
	/** close the connection
	 * 
	 */
	public function close_connection() {
		if (isset($this->db)) {
			mysqli_close($this->db);
			unset($this->db);
		}
	}
	
	/** query the database
	 * 
	 * @param string $sql
	 * @return results
	 */
	public function query($sql) {
		$result = mysqli_query($this->db, $sql);
		// test if there was a query error
		$this->confirm_query($result);
		return $result;
	}
	
	/** validate the query
	 * 
	 * @param query $result
	 */
	public function confirm_query($result) {
		if (!$result) {
			die( "Database query failed. Error Code: " . mysqli_errno($this->db). ". Error: " . mysqli_error($this->db));
		}
	}
	
	/** prevent sql injection
	 * 
	 * @param string $string
	 * @return string
	 */
	public function prevent_injection($string) {
			$escaped_string = mysqli_real_escape_string($this->db, $string);
			return $escaped_string;
	}
	
	public function insert_id() {
		// get the last id inserted over the current db connection
		return mysqli_insert_id($this->db);
	}
	
	/** get a list of fields associated with the table in query
	 * 
	 * @param $results
	 * @return 
	 */
	public function fetch_fields($results) {
		return mysqli_fetch_field($results);
	}
	
	public function fetch_array($result_set) {
		if ($result_set) {
			return mysqli_fetch_array($result_set);
		} else {
			return false;
		}
	}
	
	public function fetch_assoc_array($result_set) {
		if ($result_set) {
			return mysqli_fetch_assoc($result_set);
		} else {
			return false;
		}
	}
	
	public function affected_rows() {
		return mysqli_affected_rows($this->db);
	}
	
	private function check_database($to=false) {
		// grant pivileges must be set prior to creating the tables
		// and database must also exist
		
		$collect_boolean = array(array());
		$num = 0;
		$loop_value = true;
		while ($loop_value) {
			switch ($num) {
				case 0:
					$name = 'user';
					
					$sql  = 'CREATE TABLE IF NOT EXISTS ' . $name . ' ( ';
					$sql .= 'id int(11) NOT NULL AUTO_INCREMENT, ';
					$sql .= 'username varchar(20) NOT NULL, ';
					$sql .= 'passcode varchar(72) NOT NULL, ';
					$sql .= 'date_create datetime NOT NULL, ';
					$sql .= 'last_update datetime NOT NULL, ';
					$sql .= 'terminate_access tinyint(1) NOT NULL DEFAULT 0, ';
					$sql .= 'count_pass int(1) NOT NULL DEFAULT 0, ';
					$sql .= 'fname varchar(20) NOT NULL, ';
					$sql .= 'lname varchar(20) NOT NULL, ';
					$sql .= 'phone varchar(10) NOT NULL, ';
					$sql .= 'email varchar(50) NOT NULL, ';
					$sql .= 'address varchar(35) NULL DEFAULT "", ';
					$sql .= 'city varchar(25) NOT NULL, ';
					$sql .= 'state varchar(2) NOT NULL, ';
					$sql .= 'zip varchar(5) NOT NULL, ';
					$sql .= 'security int(1) NOT NULL DEFAULT 9, ';
					$sql .= 'clearance int(1) NOT NULL DEFAULT 9, ';
					$sql .= 'PRIMARY KEY (username), ';
					$sql .= 'UNIQUE INDEX id (id), ';
					$sql .= 'INDEX full_name (fname, lname), ';
					$sql .= 'INDEX reverse_name (lname, fname), ';
					$sql .= 'INDEX state (state, city)) ';
					$sql .= 'ENGINE=Innodb DEFAULT CHARSET=utf8';
					$collect_boolean[$num]["passed"] = ($this->query($sql)) ? true : false;
					$collect_boolean[$num]["db_name"] = $name;
					break;
					
				case 1:
					$name = 'crypt_values';
					
					$sql  = 'CREATE TABLE IF NOT EXISTS ' . $name . ' ( ';
					$sql .= 'id int(11) NOT NULL AUTO_INCREMENT, ';
					$sql .= 'username varchar(20) NOT NULL, ';
					$sql .= 'crypt_id varchar(11) NOT NULL, ';
					$sql .= 'crypt_name text NULL, ';
					$sql .= 'crypt_security text NULL, ';
					$sql .= 'descript varchar(75) NULL, ';
					$sql .= 'link varchar(255) NULL, ';
					$sql .= 'link_order int(5) NOT NULL DEFAULT 0, ';
					$sql .= 'active tinyint(1) NOT NULL DEFAULT 0, ';
					$sql .= 'PRIMARY KEY (crypt_id), ';
					$sql .= 'UNIQUE INDEX link_order (link_order), ';
					$sql .= 'UNIQUE INDEX id (id), ';
					$sql .= 'INDEX username (username), ';
					$sql .= 'INDEX descript (descript), ';
					$sql .= 'FOREIGN KEY (username) REFERENCES user (username)) ';
					$sql .= 'ENGINE=InnoDB DEFAULT CHARSET=utf8';
					$collect_boolean[$num]["passed"] = ($this->query($sql)) ? true : false;
					$collect_boolean[$num]["db_name"] = $name;
					break;
					
				case 2:
					$name = 'user_values';
					
					$sql  = 'CREATE TABLE IF NOT EXISTS ' . $name . ' ( ';
					$sql .= 'id int(11) NOT NULL AUTO_INCREMENT, ';
					$sql .= 'security int(1) NOT NULL, ';
					$sql .= 'name varchar(15) NOT NULL, ';
					$sql .= 'PRIMARY KEY (security), ';
					$sql .= 'UNIQUE INDEX id (id), ';
					$sql .= 'INDEX name (name)) ';
					$sql .= 'ENGINE=InnoDB DEFAULT CHARSET=utf8';
					$collect_boolean[$num]["passed"] = ($this->query($sql)) ? true : false;
					$collect_boolean[$num]["db_name"] = $name;
					break;
					
				case 3:
					$name = 'access_values';
					
					$sql  = 'CREATE TABLE IF NOT EXISTS ' . $name . ' ( ';
					$sql .= 'id int(11) NOT NULL AUTO_INCREMENT, ';
					$sql .= 'clearance int(1) NOT NULL, ';
					$sql .= 'name varchar(15) NOT NULL, ';
					$sql .= 'PRIMARY KEY (clearance), ';
					$sql .= 'UNIQUE INDEX id (id), ';
					$sql .= 'INDEX name (name)) ';
					$sql .= 'ENGINE=InnoDB DEFAULT CHARSET=utf8';
					$collect_boolean[$num]["passed"] = ($this->query($sql)) ? true : false;
					$collect_boolean[$num]["db_name"] = $name;
					break;
					
				case 4:
					$name = 'user_log';
					
					$sql  = 'CREATE TABLE IF NOT EXISTS ' . $name . ' ( ';
					$sql .= 'id int(11) not null auto_increment, ';
					$sql .= 'user_id int(11) not null, ';
					$sql .= 'user_type varchar(15) not null, ';
					$sql .= 'date_stamp datetime default now() on update now() not null, ';
					$sql .= 'activity text null, ';
					$sql .= 'primary key (id), ';
					$sql .= 'index user_id (user_id)) ';
					$sql .= 'ENGINE=innodb DEFAULT CHARSET=utf8';
					$collect_boolean[$num]["passed"] = ($this->query($sql)) ? true : false;
					$collect_boolean[$num]["db_name"] = $name;
					break;
					
				default:
					$loop_value = false;
			}
			$num++;
			if ($to) {
				return $collect_boolean;
			}
		}
	}
	
	public function call_check_database($logic=false) {
		if ($logic) {
			return $this->check_database($logic);
		} else {
			$this->check_database($logic);
		}
	}
	
}

$base = new Cypress();
