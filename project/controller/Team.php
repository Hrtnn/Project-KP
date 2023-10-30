<?php
require_once('_base.php');
require_once('_paths.php');
require_once($ModelPath.'ProyekModel.php');
require_once($ModelPath.'TeamModel.php');
include_once('_session.php');

class TeamController extends BaseController
{
    public function getTeamList($data)
    {
        $Team = new TeamModel();
        $columns = "*";

        if(isset($data['columns']))
        {
            $columnsArray = json_decode($data['columns']);
            $columns = "";
            
            foreach($columnsArray as $attr)
                $columns .= "$attr, ";
            
            $columns = rtrim($columns, ", ");
        }

        $TeamList = $Team->getTeamList(null, $columns);
        $this->sendResponseData("success", $TeamList);
    }

    public function getTeamById($data)
    {
        $Team = new TeamModel();
        
        if(!isset($data['team_id']))
        {
            $this->sendResponseMessage("error", "Id tidak ditemukan!");
            return;
        }
        
        $TeamData = $Team->getTeamById($data['team_id']);
        $this->sendResponseData("success", $TeamData);
    }

    public function CreateTeam($data)
    {
        $Team = new TeamModel();
        $success = $Team->CreateTeam($data);

        if($success) 
            $this->sendResponseMessage("success", "Data berhasil ditambahkan!");
        else 
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
    }

    public function EditTeam($data)
    {
        $Team = new TeamModel();
        $result = $Team->EditTeam($data);

        if($result) 
            $this->sendResponseMessage("success", "Data berhasil diubah");
        else 
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
    }

    public function DeleteTeam($data)
    {
        $Team = new TeamModel();
        $success = isset($data["team_id"]) && $Team->DeleteTeam($data["team_id"]);

        if($success) 
            $this->sendResponseMessage("success", "Data berhasil dihapus!");
        else 
            $this->sendResponseMessage("error", "Terjadi kesalahan!");
    }
}

$controller = new TeamController();
$controller->ajaxMethodCall();
?>