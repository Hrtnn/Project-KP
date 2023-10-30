<?php
require_once('_BaseModel.php');
require_once('ProgressAtpModel.php');

class ProyekModel extends BaseModel
{
    private $attributes = array(
        'client',
        'system_key',
        'tpd_id',
        'ewo_nbwo',
        'site_id_ne',
        'site_name_ne',
        'site_id_fe',
        'site_name_fe',
        'project_id',
        'project_name',
        'project_status',
        'sow_category',
        'sow_remarks',
        'detail_solution',
        'region_xl',
        'area',
        'wo_status',
        'wo_date',
        'pic_alita',
        'visit_id',
        'submit_date'
    );

    // Mengambil daftar data pada table proyek
    public function getProyekList($condition = "", $columns = "*")
    {
        $query = "SELECT $columns FROM project $condition";
        $stmt = $this->db->prepare($query);

        if($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if($result) return $this->to_array($result);
        }

        return false;
    }

    // Mengambil data pada tabel proyek berdasarkan system_key
    public function getProyekByKey($system_key)
    {
        $query = "SELECT * FROM project WHERE system_key = '$system_key'";
        $stmt = $this->db->prepare($query);

        if($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if($result) return $this->to_array($result)[0];
        }

        return false;
    }

    // Membuat system key baru 
    private function GenerateSystemKey($code = "")
    {
        return $code.substr(uniqid(), -6);
    }

    // Membuat proyek baru ke dalam tabel proyek
    public function CreateProyek($data)
    {
        $data["system_key"] = $this->GenerateSystemKey("CAG-");

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

        $query = "INSERT INTO project ($columnStr) VALUES ($placeholderStr)";
        $stmt = $this->db->prepare($query);

        if($stmt) 
        {
            $stmt -> bind_param($types, ...$values);
            if($stmt->execute()) return true;
        }

        return false;
    }

    // Mengedit atau update data dalam table proyek
    public function EditProyek($data)
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
        $query = "UPDATE project SET $updatesStr WHERE system_key = ?";
        $stmt = $this->db->prepare($query);

        if($stmt) 
        {
            $stmt->bind_param($types, ...$values);
            if ($stmt->execute()) return true;
        }
    }

    // Menghapus data dari tabel proyek
    public function DeleteProyek($id)
    {
        $values = array($id);
        $types = "s";

        $query = "DELETE FROM project WHERE system_key = ?";
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