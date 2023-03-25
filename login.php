<?php

require('db.php');
$db = $conn;
$data = $_POST['serialize'];
$email = $data['formUserName'];
$password = $data['formPassword'];

$login = login($email, $password);


function login($email, $password)
{

    global $db;

    $sql = "SELECT email FROM users WHERE email= ?";
    $query = $db->prepare($sql);
    $query->bind_param('s', $email);
    $query->execute();
    $exec = $query->get_result();
    if ($exec) {
        if ($exec->num_rows > 0) {
            $loginSql = "SELECT email, password FROM users WHERE email=? AND password=?";
            $loginQuery = $db->prepare($loginSql);
            $loginQuery->bind_param('ss', $email, $password);
            $loginQuery->execute();
            $execQuery = $loginQuery->get_result();
            if ($execQuery) {
                if ($execQuery->num_rows > 0) {
                    $row = $execQuery->fetch_assoc();
                    $resp['data'] = $row[0];
                    $resp['status'] = true;
                    echo json_encode($resp);
                    exit;
                } else {
                    $error[] = "Credentials does not match";
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
        } else {
            $error[] = "User Not Registered";
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


}
?>