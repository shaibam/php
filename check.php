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
            $user_purchase_only_query="SELECT * FROM metadata WHERE account = '{$account}' AND status = 'purchase'";
            $result = $mysqli->query("$user_only_query");
            $limit=3;
            $available = $limit - $result->num_rows;
            if ($available == 0) {
                //zero availability
                deliver_response(200, "success", "{\"available\":{$available}}");      
            } else {
                $user_pending_only_query="SELECT * FROM metadata WHERE account = '{$account}' AND status = 'pending'";
                $result = $mysqli->query("$user_pending_only_query");                
                $available_pending = $result->num_rows;
                if ($available_pending ==  $limit) {
                    //max availability is pending
                    deliver_response(200, "success", "{\"available\":{$available_pending}}");      
                } else {
                    $new_limit = $limit-$available_pending;
                    $available_records_query="SELECT * FROM metadata WHERE account = '' AND status = '' LIMIT ${$new_limit}";
                    $result = $mysqli->query("$available_records_query");
                    $available_total = $result->num_rows;
                    deliver_response(200, "success", "{\"available\":{$available_total}}");      
                }
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
