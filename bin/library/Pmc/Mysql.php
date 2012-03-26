<?php
namespace Pmc;

class Mysql extends Cli {
    
    public function __construct($mysqlHost, $mysqlUsername, $mysqlPassword, $mysqlDatabase) {
        $db = new \mysqli($mysqlHost, $mysqlUsername, $mysqlPassword, $mysqlDatabase);
        
        if ($db->connect_error) {
            trigger_error('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error, E_USER_ERROR);
        }
        
        $this->setDb($db);
    }
    
    public function setCurrentTag() {
        $sql = "SELECT tag FROM server_checkin;";
        
        $query = $this->getDb()->query($sql);
        
        if (!$query) {
            trigger_error('Could not get tag: ' . $this->getDb()->error, E_USER_ERROR);
        }

        if ( $query->num_rows !== 1 ) {
            trigger_error('Expected 1 row, got ' . $query->num_rows, E_USER_ERROR);
        }

        $result = $query->fetch_array();

        $this->_data['current_tag'] = $result[0];
        
        return $this;
        
    }
    
}