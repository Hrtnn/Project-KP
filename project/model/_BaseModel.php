<?php
require_once('_dbConn.php');

class BaseModel
{
    protected $db;

    public function __construct()
    {
        $this->db = create_connection();
    }

    // Ubah objek mysqli_result menjadi associative array
    public function to_array($result)
    {
        $rows = array();

        if($result != false)
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        return $rows;
    }
}


?>