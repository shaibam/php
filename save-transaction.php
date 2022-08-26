<?php
    if(isset($_POST)) {
        $data = json_decode(file_get_contents("php://input"));
        if (!$data->url && !$data->transaction) {
            deliver_response(400, "data missing", $data);
        } else {
            $username="udoimzgcuyxfd";
            $password="lfapts0vjgny";
            $database="dbnnichahvjtu4";
            $mysqli = new mysqli("localhost", $username, $password, $database);
            $mysqli->select_db($database) or die( "Unable to select database");

            $url = $data->url;
            $transaction = $data->transaction;

            if ($transaction) {
                $update_transaction_query="UPDATE metadata SET receipt = '{$transaction}', status = 'minted' WHERE url='{$url}'";
                $result_update = $mysqli->query("$update_transaction_query"); 
                deliver_response(200, "success",  $transaction );
            } else {
                deliver_response(500,"fail","");
            }
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
