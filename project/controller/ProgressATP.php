<?php
require_once('_base.php');
require_once('_paths.php');
require_once($ModelPath.'ProyekModel.php');
require_once($ModelPath.'ProgressATPModel.php');
include_once('_session.php');

class ProgressATPController extends BaseController
{

    public function getProgressATPList($data)
    {
        $ProgressATP = new ProgressATPModel();
        $ProgressATPList = $ProgressATP->getProgressATPList();
        $this->sendResponseData("success", $ProgressATPList);
    }

    public function CreateProgressATP($data)
    {
        $ProgressATP = new ProgressATPModel();
        $success = $ProgressATP->CreateProgressATP($data);

        if($success) 
        {
            $this->sendResponseMessage("success", "Data berhasil ditambahkan!");
        } 
        else 
        {
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
        }
    }

    public function DeleteProgressATP($data)
    {
        $ProgressATP = new ProgressATPModel();
        $success = $ProgressATP->DeleteProgressATP($data);

        if($success) 
        {
            $this->sendResponseMessage("success", "Data berhasil ditambahkan!");
        } 
        else 
        {
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
        }
    }

    public function UpdateProgressATP($data)
    {
        $ProgressATP = new ProgressATPModel();
        $success = $ProgressATP->EditProgressATP($data);

        if($success) 
        {
            $this->sendResponseMessage("success", "Data berhasil diupdate!");
        } 
        else 
        {
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
        }
    }

    public function UpdateStatusProgressATP($data)
    {
        $ProgressATP = new ProgressATPModel();
        $ProgressData = $ProgressATP->getProgressATPByKey($data["system_key"]);
        $result = false;
        
        if(!empty($ProgressData) && isset($data["type"]))
        {
            $ProgressData[$data["type"]] = date("Y-m-d");
            $result = $ProgressATP->EditProgressATP($ProgressData);
        }

        if($result) 
        {
            $this->sendResponseMessage("success", "Data berhasil diupdate!");
        } 
        else 
        {
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
        }
    }
}


$controller = new ProgressATPController();

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