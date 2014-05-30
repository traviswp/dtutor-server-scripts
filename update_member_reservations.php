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
 
    if ($request_type == "update_member_reservations") { 
        $email = $_POST['email'];
        $building = $_POST['building'];
        $room = $_POST['room'];
        $reservations = $_POST['reservations'];
 
        // package reservation info
        $item = json_encode(array("building" => $building, "room" => $room, "reservations" => $reservations));
        $response['res_item'] = $item;

         // update member's reservation data
        $ret_val = $db->updateMemberReservations($email, $item);
        $response["ret_val"] = $ret_val;

        if ($ret_val != false) {
            $response["success"] = 1;
            $response["msg"] = "member reservations update success!";
        } else {
            $response["error"] = 1;
            $response["msg"] = "unable to update member\/reservations!";
        }

        echo json_encode($response);
    }  else {
        echo "Invalid Request";
    }
} else {
    echo "Access Denied";
}

?>