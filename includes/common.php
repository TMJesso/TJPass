<?php
require_once 'database.php';
class Common {
	protected static $db_name = "db_1242_tjpass";
	
	public static function find_by_sql($sql="") {
		global $base;
		$result_set = $base->query($sql);
		$object_array = array();
		if ($result_set) {
			while ($row = $base->fetch_array($result_set)) {
				$object_array[] = static::instantiate($row);
			}
		}
		return $object_array;
	}
	
	public static function new_find_by_sql($sql="") {
		global $base;
		return $base->fetch_object($sql);
	}
	
	protected static function instantiate($record) {
		$object = new static;
		// More dynamic, short-form approach:
		foreach($record as $attribute=>$value){
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}
		return $object;
	}
	
	protected function has_attribute($attribute) {
		// We don't care about the value, we just want to know if the key exists
		// Will return true or false
		return array_key_exists($attribute, $this->attributes());
	}
	
	protected function attributes() {
		// return an array of attribute names and their values
		$attributes = array();
		foreach(static::$db_fields as $field) {
			if(property_exists($this, $field)) {
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}
	
	protected function sanitized_attributes() {
		global $base;
		$clean_attributes = array();
		// sanitize the values before submitting
		// Note: does not alter the actual value of each attribute
		foreach($this->attributes() as $key => $value) {
			$clean_attributes[$key] = $base->prevent_injection($value);
		}
		return $clean_attributes;
	}
	
	public function save() {
		// A new record won't have an id yet.
		if (!isset($this->year)) {
			$this->year = (int) strftime("%Y",time());
		}
		return (isset($this->id)) ? $this->update() : $this->create();
	}
	
	public function create() {
		global $base;
		$attributes = $this->sanitized_attributes();
		$sql = "INSERT IGNORE INTO ". static::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
		$sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		if($base->query($sql)) {
			$this->id = $base->insert_id();
			return true;
		} else {
			return false;
		}
	}
	
	public function update() {
		global $base;
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE IGNORE ". static::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id = ". $base->prevent_injection($this->id);
		$base->query($sql);
		return ($base->affected_rows() == 1) ? true : false;
	}
	
	public function delete() {
		global $base;
		$sql  = "DELETE FROM ". static::$table_name;
		$sql .= " WHERE id=". $base->prevent_injection($this->id);
		$sql .= " LIMIT 1";
		$base->query($sql);
		return ($base->affected_rows() == 1) ? true : false;
		
	}
	
	protected function check_database() {
		// grant pivileges must be set prior to creating the tables
		// database must also exist
		global $base;
		$sql  = 'CREATE TABLE IF NOT EXISTS user ( ';
		$sql .= 'id int(11) NOT NULL AUTO_INCREMENT, ';
		$sql .= 'username varchar(20) NOT NULL, ';
		$sql .= 'passcode varchar(72) NOT NULL, ';
		$sql .= 'date_create datetime NOT NULL, ';
		$sql .= 'last_update datetime NOT NULL, ';
		$sql .= 'terminate_access tinyint(1) NOT NULL DEFAULT 0';
		$sql .= 'fname varchar(20) NOT NULL, ';
		$sql .= 'lname varchar(20) NOT NULL, ';
		$sql .= 'phone varchar(10) NOT NULL, ';
		$sql .= 'address varchar(35) NULL DEFAULT ""';
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
		$base->query($sql);
	}
	
}