<?php

class BaseController
{
    // Send response to request
    function sendResponseMessage($status, $message = "")
    {
        $response = array('status' => $status, 'message' => $message);
        echo json_encode($response);
    }

    // Send response to request
    function sendResponseData($status, $data)
    {
        $response = array('status' => $status, 'data' => $data);
        echo json_encode($response);
    }

    public function ajaxMethodCall()
    {
        if(isset($_POST['function'])) 
        {
            $func = $_POST['function'];
            $this->$func($_POST);
        }

        if(isset($_GET['function']))
        {
            $func = $_GET['function'];
            $this->$func($_GET);
        }
    }
}

?>