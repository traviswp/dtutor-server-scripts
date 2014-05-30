<?php
/**
 * File to handle all API requests -- accepts GET and POST requests.
 */

/**
 * check for POST request
 */
if (isset($_POST['request_type']) && $_POST['request_type'] != '') {
    // get request_type
    $request_type = $_POST['request_type'];

    // include db handler
    require_once 'DB_Helper.php';
    $db = new DB_Helper();
 
    // response array
    $response = array("request_type" => $request_type, "success" => 0, "error" => 0);
 
    if ($request_type == "update_member") { 
        $email = $_POST['email'];
        $notifications = $_POST['notifications'];
 
         // update member's reservation data
        $ret_val = $db->updateMemberNotifications($email, $notifications);
        $response["ret_val"] = $ret_val;

        if ($ret_val != false) {
            $response["success"] = 1;
            $response["msg"] = "member notifications update success!";
        } else {
            $response["error"] = 1;
            $response["msg"] = "unable to update member\/notifications!";
        }

        echo json_encode($response);
    }  else {
        echo "Invalid Request";
    }
} else {
    echo "Access Denied";
}

?>