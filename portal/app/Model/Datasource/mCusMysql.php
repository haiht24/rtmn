<?php 

App::uses('Mysql', 'Model/Datasource/Database');

class mCusMysql extends Mysql {
    
    public function __construct($config) {
        parent::__construct($config);
        $this->columns['binary'] = array('name' => 'binary');
        $this->columns['blob'] = array('name' => 'blob');
        $this->columns['decimal'] = array('name' => 'decimal');
        $this->description = 'Extended MySQL DBO Driver';
    }
    
    public function column($real) {
        if (is_array($real)) {
            $col = $real['name'];
            if (isset($real['limit'])) {
                $col .= '(' . $real['limit'] . ')';
            }
            return $col;
        }
    
        $col = str_replace(')', '', $real);
        $limit = $this->length($real);
        if (strpos($col, '(') !== false) {
            list($col, $vals) = explode('(', $col);
        }
    
        if (in_array($col, array('date', 'time', 'datetime', 'timestamp'))) {
            return $col;
        }
        if (($col === 'tinyint' && $limit === 1) || $col === 'boolean') {
            return 'boolean';
        }
        if (strpos($col, 'bigint') !== false || $col === 'bigint') {
            return 'biginteger';
        }
        if (strpos($col, 'int') !== false) {
            return 'integer';
        }
        if (strpos($col, 'char') !== false || $col === 'tinytext') {
            return 'string';
        }
        if (strpos($col, 'text') !== false) {
            return 'text';
        }
        if (strpos($col, 'blob') !== false) {
            return 'blob';
        }
        if($col === 'binary') {
            return 'binary';
        }
        if (strpos($col, 'float') !== false || strpos($col, 'double') !== false) {
            return 'float';
        }
        if (strpos($col, 'decimal') !== false) {
            return 'decimal';
        }
        if (strpos($col, 'enum') !== false) {
            return "enum($vals)";
        }
        return 'text';
    }
}
?>