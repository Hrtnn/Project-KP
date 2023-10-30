<?php
require_once('_BaseModel.php');
include_once('ProgressATPModel.php');

class ProgressModel extends BaseModel
{
    private $attributes = array(
        'system_key',
        'team_name',
        'submit_date',
        'finish_date',
        'result'
    );

    // Mengambil daftar data pada table progress
    public function getProgressList($condition = "", $columns = "*")
    {
        $query = "SELECT $columns FROM progress $condition";
        $stmt = $this->db->prepare($query);

        if($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if($result) return $this->to_array($result);
        }

        return false;
    }

    // Mengambil data pada tabel progress berdasarkan system_key
    public function getProgressByKey($system_key)
    {
        $query = "SELECT * FROM progress WHERE system_key = '$system_key'";
        $stmt = $this->db->prepare($query);

        if($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if($result) return $this->to_array($result)[0];
        }

        return false;
    }

    // Membuat progress baru ke dalam tabel Progress
    public function CreateProgress($data)
    {
        $columns = [];
        $placeholders = [];
        $types = "";
        $values = [];

        foreach ($this->attributes as $col)
        {
            if (array_key_exists($col, $data))
            {
                $columns[] = $col;
                $values[] = $data[$col];

                $types .= (is_int($data[$col]) ? "i" : "s");
                $placeholders[] = "?";
            }
        }

        $columnStr = implode(", ", $columns);
        $placeholderStr = implode(", ", $placeholders);

        $query = "INSERT INTO progress ($columnStr) VALUES ($placeholderStr)";
        $stmt = $this->db->prepare($query);

        if($stmt) 
        {
            $stmt -> bind_param($types, ...$values);
            if($stmt->execute()) return true;
        }

        return false;
    }

    // Mengedit atau update data dalam table Progress
    public function EditProgress($data)
    {
        if(!isset($data["system_key"])) return false;
    
        $system_key = $data["system_key"];
        $updates = [];
        $types = "";
        $values = [];
    
        foreach($this->attributes as $col) {
            if($col === "system_key") continue;
    
            if(array_key_exists($col, $data)) {
                $val = $data[$col];
    
                // Determine the data type and set the binding types accordingly
                if (is_int($val)) {
                    $types .= "i"; // Integer type
                } else {
                    $types .= "s"; // Default to string type
                }
    
                $updates[] = "$col = ?";
                $values[] = $val;
            }
        }
        
        $types .= "s"; // Add string type for the WHERE condition (system_key)
        $values[] = $system_key;

        $updatesStr = implode(", ", $updates);
        $query = "UPDATE progress SET $updatesStr WHERE system_key = ?";
        $stmt = $this->db->prepare($query);

        if($stmt) 
        {
            $stmt->bind_param($types, ...$values);
            if ($stmt->execute()) return true;
        }

        return false;
    }

    // Menghapus data dari tabel Progress
    public function DeleteProgress($id)
    {
        $values = array($id);
        $types = "s";

        $query = "DELETE FROM progress WHERE system_key = ?";
        $stmt = $this->db->prepare($query);

        if($stmt) 
        {
            $stmt->bind_param($types, ...$values);
            if ($stmt->execute()) return true;
        }

        return false;
    }
}

?>