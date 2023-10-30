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
            global $ViewPath;
            
            if($UserData["user_type"] == "admin")
            {
                header('Location: '.$ViewPath.'admin_dashboard.php');
                exit;
            }
            else
            {
                header('Location: '.$ViewPath.'user_dashboard.php');
                exit;
            }
        }
    }

    // Pendaftaran user baru
    public function UserRegistration($data)
    {
        $User = new UserModel();
        $data["user_type"] = "umum";

        if($User->CreateUser($data))
            $this->sendResponseMessage('success', 'Berhasil');
        else 
            $this->sendResponseMessage("error", "Tidak berhasil");
    }

    // Hapus session pada saat logout
    public function Logout()
    {
        session_unset();
        session_destroy();
    }
}


$controller = new LoginController();
$controller->ajaxMethodCall();
?>