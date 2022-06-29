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
            $user_only_query="SELECT * FROM metadata WHERE account = '{$account}' AND (status = '' or status = 'pending')";
            $result = $mysqli->query("$user_only_query");
            $limit=3;
            $available = $limit - $result->num_rows;
            if ($limit >= $amount) {
                // serve these records according to amount
            }
            $query="SELECT id,url FROM metadata WHERE status = '' and (account = '' or account = '{$account}') LIMIT {$amount}";
            $result = $mysqli->query("$query");
            $result_array = array();
            while ($row = $result->fetch_assoc()) {
                $json = "{\"id\":{$row["id"]},\"url\":\"{$row["url"]}\"}";
                // $row_string = implode(",",$row);
                array_push($result_array, $json);             
            }            
            $implodeded = implode(",",$result_array);
            deliver_response(200, "success", "[" . $implodeded . "]");  
            $result->free();            
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
?>