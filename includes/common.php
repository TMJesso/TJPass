<?php
//require_once LIB_PATH . 'database.php';
class Common {
	
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
	
	public static function find_max_id() {
		global $base;
		$sql  = "SELECT MAX(id) FROM " . static::$table_name;
		$results = $base->query($sql);
		$row = ($results) ? $base->fetch_array($results) : 0;
		return is_array($row) ? array_shift($row) : $row;
	}
	
	public function save() {
		// A new record won't have an id yet.
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
		$sql .= " WHERE id=" . $this->id;
		$sql .= " LIMIT 1";
		$base->query($sql);
		return ($base->affected_rows() == 1) ? true : false;
		
	}
	
}