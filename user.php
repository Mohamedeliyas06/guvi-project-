<?php

require('db.php');
$db = $conn;
$user_id = "";
if(isset($_GET['user_id']) && $_GET['user_id'] !=''){
    $user_id = $_GET['user_id'];
}else{
    $error[] = "Oops! Something went wrong";
    $resp['msg'] = $error;
    $resp['status'] = false;
    echo json_encode($resp);
    exit;
}
$sql = "SELECT * FROM users WHERE user_id= ?";
$query = $db->prepare($sql);
$query->bind_param('s', $user_id);
$query->execute();
$exec = $query->get_result();
if ($exec) {
    if ($exec->num_rows > 0) {
        $row = $exec->fetch_assoc();
        $resp['data'] = $row[0];
        $resp['status'] = true;
        echo json_encode($resp);
        exit;
    } else {
        $error[] = "Data not found";
        $resp['msg'] = $error;
        $resp['status'] = false;
        echo json_encode($resp);
        exit;
    }


} else {
    $error[] = $db->error;
    $resp['msg'] = $error;
    $resp['status'] = false;
    echo json_encode($resp);
    exit;
}


?>