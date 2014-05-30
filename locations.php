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
 
    // check for request_type type
    if ($request_type == "add") {
        $building = $_POST['building'];
        $room = $_POST['room'];
        $lat = $_POST['latitude'];
        $long = $_POST['longitude'];
        $reservations = $_POST['reservations'];
 
        // store the new location
        $location = $db->storeLocation($building, $room, $lat, $long, $reservations);

        // construct response
        if ($location != false) {
            $response["success"] = 1;
            $response["msg"] = "location add success!";
        } else { 
            $response["error"] = 1;
            $response["msg"] = "unable to add location!";
        }

        echo json_encode($response);
    } else if ($request_type == "update") { 
        $building = $_POST['building'];
        $room = $_POST['room'];
        $reservations = $_POST['reservations'];
 
         // update location w/ reservation data
        $ret_val = $db->updateLocationReservations($building, $room, $reservations);
        $response["ret_val"] = $ret_val;

        if ($ret_val != false) {
            $response["success"] = 1;
            $response["msg"] = "location reservations update success!";
        } else {
            $response["error"] = 1;
            $response["msg"] = "unable to update location\/reservations!";
        }

        echo json_encode($response);
    } else if ($request_type == "fetchAll") { 
        $locations = $db->fetchAllLocations();

        // construct response
        if ($locations != false) {
            $response["success"] = 1;
            $response["msg"] = "fetched locations!";
            $response["locations"] = $locations;
        } else { 
            $response["error"] = 1;
            $response["msg"] = "unable to fetch locations";
        }

        echo json_encode($response);    
    } else {
        echo "Invalid Request";
    }
} else {
    echo "Access Denied";
}

?>