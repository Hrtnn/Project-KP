<?php
require_once('_BaseModel.php');

class TeamModel extends BaseModel
{
    private $attributes = array(
        "team_id",
        "username",
        "first_name",
        "last_name",
        "no_hp",
        "nik",
        "email",
        "no_k3",
        "role",
        "join_date"
    );

    // Mengambil daftar data pada table Team
    public function getTeamList($condition = "", $columns = "*")
    {
        $query = "SELECT $columns FROM teams $condition";
        $stmt = $this->db->prepare($query);

        if($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if($result) return $this->to_array($result);
        }

        return false;
    }

    public function getTeamById($id)
    {
        $query = "SELECT * FROM teams WHERE team_id = '$id'";
        $stmt = $this->db->prepare($query);

        if($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();

            if($result) return $this->to_array($result)[0];
        }

        return false;
    }

    // Membuat Team baru ke dalam tabel Team
    public function CreateTeam($data)
    {
        $data["team_id"] = "FT-".substr(uniqid(), -6);

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

        $query = "INSERT INTO teams ($columnStr) VALUES ($placeholderStr)";
        $stmt = $this->db->prepare($query);

        if($stmt) 
        {
            $stmt -> bind_param($types, ...$values);
            if($stmt->execute()) return true;
        }

        return false;
    }

    // Mengedit atau update data dalam table Team
    public function EditTeam($data)
    {
        if(!isset($data["team_id"])) return false;
    
        $team_id = $data["team_id"];
        $updates = [];
        $types = "";
        $values = [];
    
        foreach($this->attributes as $col) {
            if($col === "team_id") continue;
    
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
        
        $types .= "s"; // Add string type for the WHERE condition 
        $values[] = $team_id;

        $updatesStr = implode(", ", $updates);
        $query = "UPDATE teams SET $updatesStr WHERE team_id = ?";
        $stmt = $this->db->prepare($query);

        if($stmt) 
        {
            $stmt->bind_param($types, ...$values);
            if ($stmt->execute()) return true;
        }

        return false;
    }

    // Menghapus data dari tabel Team
    public function DeleteTeam($id)
    {
        $values = array($id);
        $types = "s";

        $query = "DELETE FROM teams WHERE team_id = ?";
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