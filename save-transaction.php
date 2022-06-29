<?php
    if(isset($_POST)) {
        $data = json_decode(file_get_contents("php://input"));
        if (!$data->account) {
            deliver_response(400, "account missing", $data);
        } else {
            $username="udoimzgcuyxfd";
            $password="lfapts0vjgny";
            $database="dbnnichahvjtu4";
            $mysqli = new mysqli("localhost", $username, $password, $database);
            $mysqli->select_db($database) or die( "Unable to select database");

            $account = $data->account;
            $transactions = $data->transactions;

            echo $transactions;
        }
    }
   
    function deliver_response($status, $status_message, $data) {
        header("HTTP/1.1 $status $status_message");
        $response['status'] = $status;
        $response['status_message'] = $status_message;
        $response['data'] = $data;
   
        $json_response = json_encode($response);
        echo $json_response;
   }
