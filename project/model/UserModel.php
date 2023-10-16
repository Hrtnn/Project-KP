<?php
require_once('_BaseModel.php');

class UserModel extends BaseModel
{
    private $attributes = array(
        "email",
        "name",
        "password",
        "user_type"
    );

    // Mengambil daftar user dari tabel
    public function getUserList($condition = "")
    {
        $result = $this->db->query("SELECT email, user_type FROM users ".$condition);
        $dataList = [];

        while ($row = $result->fetch_assoc()) 
            $dataList[] = $row;

        return $dataList;
    }

    // Mendaftarkan user baru
    public function CreateUser($data)
    {
        if(!isset($data["user_type"])) $data["user_type"] = "umum";
        
        $columns = "";
        $values = "";

        // Buat query berdasarkan data
        foreach($this->attributes as $col) 
        {
            if(array_key_exists($col, $data)) 
            {
                $val = $data[$col];
                $columns .= "'$col', ";
                $values .= (gettype($val) == "integer") ? "NULLIF($val), " : "NULLIF('$val'), ";
            }
        }

        $columns = rtrim($columns, ", ");
        $values = rtrim($values, ", ");
        $query = "INSERT INTO proyek (".$columns.") VALUES (".$values.")";

        return $this->db->query($query);
    }
}

?>