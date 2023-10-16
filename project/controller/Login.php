<?php
require_once('_base.php');
require_once('_paths.php');
require_once($ModelPath.'UserModel.php');
include_once('_session.php');


class LoginController extends BaseController
{

    // Validasi login
    public function LoginValidation($data)
    {
        $User = new UserModel();
        $UserData = $User->getUserList("WHERE email = ".$data["email"]." AND password = ".$data["password"])[0];

        if(empty($UserData)) 
        {
            $this->sendResponseMessage('error', 'Maaf, email atau password yang dimasukkan salah.');
        }
        else 
        {
            $_SESSION['UserType'] = $UserData["user_type"];
            $this->sendResponseMessage('success');
        }
    }

    // Pendaftaran user baru
    public function UserRegistration($data)
    {
        $User = new UserModel();

        if(!isset($data["user_type"])) $data["user_type"] = "Umum";

        if($User->CreateUser($data))
        {
            $this->sendResponseMessage('success', 'Berhasil mendaftarkan pengguna.');
        } 
        else 
        {
            $this->sendResponseMessage("error", "Tidak berhasil mendaftarkan pengguna.");
        }
    }

    // Hapus session pada saat logout
    public function Logout()
    {
        session_unset();
        session_destroy();
    }
}


$controller = new LoginController();

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