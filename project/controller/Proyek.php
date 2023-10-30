<?php
require_once('_base.php');
require_once('_paths.php');
require_once($ModelPath.'ProyekModel.php');
require_once($ModelPath.'ProgressATPModel.php');
include_once('_session.php');

class ProyekController extends BaseController
{

    public function getProyekList($data)
    {
        $Proyek = new ProyekModel();
        $columns = "*";

        if(isset($data['columns']))
        {
            $columnsArray = json_decode($data['columns']);
            $columns = "";
            
            foreach($columnsArray as $attr)
                $columns .= "$attr, ";
            
            $columns = rtrim($columns, ", ");
        }

        $ProyekList = $Proyek->getProyekList(null, $columns);
        $this->sendResponseData("success", $ProyekList);
    }

    public function getProyekById($data)
    {
        $Proyek = new ProyekModel();
        
        if(!isset($data['system_key']))
        {
            $this->sendResponseMessage("error", "Id tidak ditemukan!");
            return;
        }
        
        $ProyekData = $Proyek->getProyekByKey($data['system_key']);
        $this->sendResponseData("success", $ProyekData);
    }

    public function CreateProyek($data)
    {
        $Proyek = new ProyekModel();
        $success = $Proyek->CreateProyek($data);

        if($success) 
            $this->sendResponseMessage("success", "Data berhasil ditambahkan!");
        else 
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
    }

    public function EditProyek($data)
    {
        $Proyek = new ProyekModel();
        $result = $Proyek->EditProyek($data);

        if($result) 
            $this->sendResponseMessage("success", "Data berhasil diubah");
        else 
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
    }

    public function DeleteProyek($data)
    {
        $Proyek = new ProyekModel();
        $success = isset($data["system_key"]) && $Proyek->DeleteProyek($data["system_key"]);

        if($success) 
            $this->sendResponseMessage("success", "Data berhasil dihapus!");
        else 
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
    }
}


$controller = new ProyekController();
$controller->ajaxMethodCall();
?>