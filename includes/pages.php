<?php
class Page extends Common {
    protected static $table_name = 'pages';
    protected static $db_fields = array('id', 'subject_id', 'name', 'menu_name', 'url', 'position', 'visible', 'content');
    
    public $id;
    public $subject_id;
    public $name;
    public $menu_name;
    public $url;
    public $position;
    public $visible;
    public $content;
    
    public static function find_page_by_id($id) {
        $sql  = "SELECT * FROM " . self::$table_name;
        $sql .= " WHERE id = {$id}";
        $row = self::find_by_sql($sql);
        return ($row) ? array_shift($row) : false;
    }
    
    public static function get_all_pages_by_subject_id($id) {
        $sql  = "SELECT * FROM " . self::$table_name;
        $sql .= " WHERE subject_id = {$id}";
        $sql .= " ORDER BY position";
        return self::find_by_sql($sql);
    }
    
    
}


?>
