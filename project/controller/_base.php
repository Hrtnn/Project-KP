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
}

?>