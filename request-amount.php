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
            $amount = $data->amount;
            $user_only_query="SELECT * FROM metadata WHERE account = '{$account}' AND status = 'pending' LIMIT {$amount}";
            $result = $mysqli->query("$user_only_query");
            $available_pending = $result->num_rows;
            $result_array = array();
            $ids_array = array();
            if ($available_pending == $amount) {
                //return $result records 
                while ($row = $result->fetch_assoc()) {
                    $json = "{\"url\":\"{$row["url"]}\"}";                    
                    array_push($result_array, $json);             
                    array_push($ids_array,$row["id"]);
                }  
                $result->free();           
            }
            
            if ($available_pending < $amount) {
                $new_limit = $amount-$available_pending;
                $available_records_query="SELECT * FROM metadata WHERE account = '' AND status = '' LIMIT {$new_limit}";
                $result1 = $mysqli->query("$available_records_query");
                //return $result and $result 1 records 
                while ($row = $result1->fetch_assoc()) {
                    $json = "{\"url\":\"{$row["url"]}\"}";                    
                    array_push($result_array, $json);    
                    array_push($ids_array,$row["id"]);         
                }  
                $result1->free(); 
            }
               
            $implodeded = implode(",",$result_array);
            
            //set records to pending and set date
            foreach ($ids_array as $row) {
                $update_row_query="UPDATE metadata SET account = '{$account}', status = 'pending', date=current_date() WHERE id={$row}";
                $result_update = $mysqli->query("$update_row_query"); 
            }

            deliver_response(200, "success", "[" . $implodeded . "]");
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
