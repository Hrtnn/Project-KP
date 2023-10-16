<?php
require_once('_BaseModel.php');

class ProgressModel extends BaseModel
{
    private $attributes = array(
        'progress_id',
        'progress_atp_id',
        'system_key',
        'team_name',
        'submit_date',
        'finish_date',
        'result'
    );

    // Mengambil daftar data pada table progress
    public function getProgressList($conditions = "", $columns = "*")
    {
        $result = $this->db->query("SELECT $columns FROM progress ".$conditions);
        return $this->to_array($result);
    }

    // Membuat progress baru ke dalam tabel Progress
    public function CreateProgress($data)
    {
        $data["progress_id"] = uniqid();
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
        $query = "INSERT INTO progress (".$columns.") VALUES (".$values.")";

        return $this->db->query($query);
    }

    // Mengedit atau update data dalam table Progress
    public function EditProgress($data)
    {
        if(!isset($data["progress_id"])) return false;

        $progress_id = $data["progress_id"];
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
        $query = "UPDATE progress SET ".$updates." WHERE progress_id = $progress_id";

        return $this->db->query($query);
    }

    // Menghapus data dari tabel Progress
    public function DeleteProgress($data)
    {
        if(!isset($data["progress_id"])) return false;

        $id = $data["progress_id"];
        $query = "DELETE FROM progress WHERE progress_id = $id";

        // Untuk soft delete
        // $this->EditProgress(array("progress_id" => $data["progress_id"], "deleted" => 1));

        return $this->db->query($query);
    }
}

?>