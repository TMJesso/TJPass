<?php
class Subject extends Common {
    protected static $table_name = 'subjects';
    protected static $db_fields = array('id', 'username', 'menu_name', 'url', 'position', 'visible');
    
    public $id;
    public $username;
    public $menu_name;
    public $url;
    public $position;
    public $visible;
    
    public static function find_subject_by_id($id) {
        $sql  = "SELECT * FROM " . self::$table_name;
        $sql .= " WHERE id = {$id}";
        $row = self::find_by_sql($sql);
        return ($row) ? array_shift($row) : false;
    }
    
    public static function get_all_subject_by_menu_name() {
        $sql  = "SELECT * FROM " . self::$table_name;
        $sql .= " ORDER BY menu_name";
        return self::find_by_sql($sql);
    }
    
    public static function get_all_subject_by_position() {
        $sql  = "SELECT * FROM " . self::$table_name;
        $sql .= " ORDER BY position";
        return self::find_by_sql($sql);
    }
    
    
    
}
?>
