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
    if ($request_type == LOGIN) {
        // Request type is Login
        $email = $_POST['email'];
        $password = $_POST['password'];
 
        // check for user
        $user = $db->getUserByEmailAndPassword($email, $password);
        if ($user != false) { // user found
            $response["success"] = SUCCESS;
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
        } else { // user not found
            $response["error"] = INCORRECT_EMAIL_OR_PASSWORD;
            $response["error_msg"] = "Incorrect email or password!";
        }
        echo json_encode($response);
    } else if ($request_type == REGISTER) { 
        // Request type is Register new user
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
 
        // check if user is already existed
        if ($db->isUserExists($email)) {
            // user is already existed - error response
            $response["error"] = REG_ERROR_USER_EXISTS;
            $response["error_msg"] = "User already existed";
        } else {
            // store user
            $user = $db->storeUser($name, $email, $password);
            if ($user != false) {
                // user stored successfully
                $response["success"] = SUCCESS;
                $response["user"]["name"] = $user["name"];
                $response["user"]["email"] = $user["email"];
                $response["user"]["created_at"] = $user["created_at"];
            } else {
                // user failed to store
                $response["error"] = REG_ERROR;
                $response["error_msg"] = "Error occured in Registartion";
            }
        }
        echo json_encode($response);
    } else {
        echo "Invalid Request";
    }
} else {
    echo "Access Denied";
}

?>