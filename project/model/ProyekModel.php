<?php
require_once('_BaseModel.php');
require_once('ProgressAtpModel.php');

class ProyekModel extends BaseModel
{
    private $attributes = array(
        'system_key',
        'tdp_id',
        'ewo_nbwo',
        'site_id_ne',
        'site_id_fe',
        'project_id',
        'project_name',
        'sow_category',
        'detail_solution',
        'region_xl',
        'area',
        'wo_status',
        'wo_date',
        'pic_alita',
        'visit_id'
    );

    // Mengambil daftar data pada table proyek
    public function getProyekList($condition = "", $columns = "*")
    {
        $result = $this->db->query("SELECT $columns FROM project ".$condition);
        return $this->to_array($result);
    }

    // Mengambil data pada tabel proyek berdasarkan system_key
    public function getProyekByKey($system_key)
    {
        $result = $this->db->query("SELECT * FROM proyek WHERE system_key = '$system_key'");
        return $this->to_array($result)[0];
    }

    // Membuat system key baru 
    private function GenerateSystemKey($code = "")
    {
        $projectCount = $this->db->query("SELECT COUNT(system_key) FROM proyek WHERE system_key LIKE '$code%'");
        return $code.($projectCount + 1);
    }

    // Membuat proyek baru ke dalam tabel proyek
    public function CreateProyek($data)
    {
        $data["system_key"] = $this->GenerateSystemKey("CAG-");
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
        $query = "INSERT INTO proyek (".$columns.") VALUES (".$values.")";

        return $this->db->query($query);
    }

    // Mengedit atau update data dalam table proyek
    public function EditProyek($data)
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
                $updates .= (gettype($val) == "integer") ? "NULLIF($val), " : "NULLIF('$val'), ";
            }
        }

        $updates = rtrim($updates, ", ");
        $query = "UPDATE proyek SET ".$updates." WHERE system_key = '$system_key'";

        return $this->db->query($query);
    }

    // Menghapus data dari tabel proyek
    public function DeleteProyek($data)
    {
        if(!isset($data["system_key"])) return false;

        $id = $data["system_key"];
        $query = "DELETE FROM proyek WHERE system_key = '$id'";

        // Untuk soft delete
        // $this->EditProyek(array("system_key" => $data["system_key"], "deleted" => 1));

        return $this->db->query($query);
    }
}

?>