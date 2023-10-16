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
        $ProyekList = $Proyek->getProyekList();
        $this->sendResponseData("success", $ProyekList);
    }

    public function CreateProyek($data)
    {
        $Proyek = new ProyekModel();
        $success = $Proyek->CreateProyek($data);

        if($success) 
        {
            $this->sendResponseMessage("success", "Data berhasil ditambahkan!");
        } 
        else 
        {
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
        }
    }

    public function DeleteProyek($data)
    {

    }

    public function SetProgress($data)
    {

    }
}


$controller = new ProyekController();

if(isset($_POST['function'])) 
{
    $func = $_POST['function'];
    $controller->$func($_POST);
}

if(isset($_GET['function']))
{
    $func = $_GET['function'];
    $controller->$func($_GET);
}
?>