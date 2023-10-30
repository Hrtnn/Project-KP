<?php
require_once('_BaseModel.php');

class ProgressATPModel extends BaseModel
{
    private $attributes = array(
        'progress_atp_id',
        'system_key',
        'progress_title',
        'team_name',
        'CQC_submit_date',
        'CQC_approve_date',
        'PQC_submit_date',
        'PQC_approve_date',
        'AQC_submit_date',
        'AQC_approve_date',
        'FOP_approve_date',
        'ROH_approve_date',
        'status'
    );

    // Mengambil daftar data pada table proyek
    public function getProyekList($condition = "", $columns = "*")
    {
        $query = "SELECT $columns FROM progress_atp $condition";
        $stmt = $this->db->prepare($query);

        if($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if($result) return $this->to_array($result);
        }

        return false;
    }
    // Mengambil data pada tabel progress berdasarkan system_key
    public function getProgressATPByKey($system_key)
    {
        $query = "SELECT * FROM progress_atp WHERE system_key = '$system_key'";
        $stmt = $this->db->prepare($query);

        if($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if($result) return $this->to_array($result)[0];
        }

        return false;
    }

    public function checkStatus($data)
    {
        $progress_status = "";

        $checks = array(
            'CQC_submit_date',
            'CQC_approve_date',
            'PQC_submit_date',
            'PQC_approve_date',
            'AQC_submit_date',
            'AQC_approve_date',
            'FOP_approve_date',
            'ROH_approve_date',
        );

        $status = array(
            "10000000" => "CQC under Review - PQC Need Submit",
            "00100000" => "CQC Need Submit - PQC Under Review",
            "11000000" => "CQC Approved - PQC Need Submit",
            "11100000" => "CQC Approved - PQC Under Review",
            "00110000" => "CQC Need Submit - PQC Approved",
            "10110000" => "CQC Under Review - PQC Approved",
            "11110000" => "AQC Ready",
            "11111000" => "AQC Under Review",
            "11111100" => "Need ATP FOP",
            "11111110" => "Need ATP ROH",
            "11111111" => "Work Done"
        );

        foreach($checks as $val) {
            $progress_status .= (($data[$val] != NULL) ? '1' : '0');
        }

        return $status[$progress_status];
    }

    // Membuat progress baru ke dalam tabel ProgressATP
    public function CreateProgressATP($data)
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

        $query = "INSERT INTO progress_atp ($columnStr) VALUES ($placeholderStr)";
        $stmt = $this->db->prepare($query);

        if($stmt) 
        {
            $stmt -> bind_param($types, ...$values);
            if($stmt->execute()) return true;
        }

        return false;
    }

    // Mengedit atau update data dalam table ProgressATP
    public function EditProgressATP($data)
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
        $query = "UPDATE progress_atp SET $updatesStr WHERE system_key = ?";
        $stmt = $this->db->prepare($query);

        if($stmt) 
        {
            $stmt->bind_param($types, ...$values);
            if ($stmt->execute()) return true;
        }

        return false;
    }

    // Menghapus data dari tabel ProgressATP
    public function DeleteProgressATP($id)
    {
        $values = array($id);
        $types = "s";

        $query = "DELETE FROM progress_atp WHERE system_key = ?";
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