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

    // Mengambil daftar data pada table progress atp
    public function getProgressATPList($condition = "", $columns = "*")
    {
        $result = $this->db->query("SELECT $columns FROM progress_atp ".$condition);
        return $this->to_array($result);
    }

    // Mengambil data pada tabel progress berdasarkan system_key
    public function getProgressATPByKey($system_key)
    {
        $result = $this->db->query("SELECT * FROM progress_atp WHERE system_key = '$system_key'");
        return $this->to_array($result)[0];
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

        foreach($checks as $val)
        {
            $progress_status .= (($data[$val] != NULL) ? '1' : '0');
        }

        return $status[$progress_status];
    }

    // Membuat progress baru ke dalam tabel ProgressATP
    public function CreateProgressATP($data)
    {
        $data["progress_atp_id"] = uniqid();
        $columns = "";
        $values = "";

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
        $query = "INSERT INTO progress_atp (".$columns.") VALUES (".$values.")";

        return $this->db->query($query);
    }

    // Mengedit atau update data dalam table ProgressATP
    public function EditProgressATP($data)
    {
        if(!isset($data["system_key"])) return false;

        $system_key = $data["system_key"];
        $updates = "";

        foreach($this->attributes as $col)
        {
            if(array_key_exists($col, $data)) 
            {
                $val = $data[$col];
                $updates .= "$col = ";
                if($val == '' || $val == null)
                    $updates .= "NULL, ";
                else
                    $updates .= (gettype($val) == "integer") ? "$val, " : "'$val', ";
            }
        }

        $updates = rtrim($updates, ", ");
        $query = "UPDATE progress_atp SET ".$updates." WHERE system_key = '$system_key'";

        return $this->db->query($query);
    }

    // Menghapus data dari tabel ProgressATP
    public function DeleteProgressATP($data)
    {
        if(!isset($data["system_key"])) return false;

        $id = $data["system_key"];
        $query = "DELETE FROM progress_atp WHERE system_key = $id";

        // Untuk soft delete
        // $this->EditProgressATP(array("system_key" => $data["system_key"], "deleted" => 1));

        return $this->db->query($query);
    }
}
?>