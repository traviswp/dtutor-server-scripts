<?php
 
class DB_Helper {
 
    private $db;

    /**
     * TODO: delete/update functions?
     */
 
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';

        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }
 
    // destructor
    function __destruct() {
    }

    public function updateMemberReservations($email, $reservations) {
        $result = mysql_query("UPDATE members SET reservations='$reservations' WHERE email='$email'");
        return $result;
    }

    public function updateMemberNotifications($email, $notifications) {
        $result = mysql_query("UPDATE members SET notifications='$notifications' WHERE email='$email'");
        return $result;
    }

    public function storeLocation($building, $room, $lat, $long, $reservations) {
        $result = mysql_query("INSERT INTO locations(building, room, latitude, longitude, reservations) VALUES ('$building', '$room', '$lat', '$long', '$reservations')");

        // check for successful store
        if ($result) {
            $uid = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM locations WHERE uid = $uid");

            // return user details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }

    public function updateLocationReservations($building, $room, $reservations) {
        $result = mysql_query("UPDATE locations SET reservations='$reservations' WHERE building='$building' AND room='$room'");
        return $result;
    }

    public function fetchAllLocations() {
        $result = mysql_query("SELECT * FROM locations") or die(mysql_error());

        // check for result
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $arrayM = array();
            while ($row = mysql_fetch_array($result)) {
               $arrayM[] = $row;
            }
            return $arrayM;
        } else { // no records...
            return false;
        }
    }

    /**
     * Store a new user.
     *
     * @param name
     * @param email
     * @param password
     * returns user details
     */
    public function storeUser($name, $email, $password) {

        // TODO: fix this bit -- hashed passwords/salt values are causing entries
        // not to be inserted...
        //
        // $hash = $this->hashSSHA($password);
        // $encrypted_password = $hash["encrypted"]; // encrypted password
        // $salt = $hash["salt"]; // salt
        // $result = mysql_query("INSERT INTO users(name, email, encrypted_password, salt, created_at) VALUES('$name', '$email', '$encrypted_password', '$salt', NOW())");
        
        $result = mysql_query("INSERT INTO users(name, email, encrypted_password, salt, created_at) VALUES('$name', '$email', '$password', '5', NOW())");

        // check for successful store
        if ($result) {
            $uid = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM users WHERE uid = $uid");
            
            // return user details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }
 
    /**
     * Get user by email and password.
     *
     * @param email
     * @param password
     * returns user details
     */
    public function getUserByEmailAndPassword($email, $password) {
        $result = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
        
        // check for result
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            $encrypted_password = $result["encrypted_password"];
            if($encrypted_password == $password) {
                return $result;
            }
            return false;

            // TODO: UNCOMMENT AND USE THIS WHEN PASSWORDS ARE STORED ENCRYPTED
            //$salt = $result['salt'];
            //$encrypted_password = $result['encrypted_password'];
            //$hash = $this->checkhashSSHA($salt, $password);

            // check for password equality
            //if ($encrypted_password == $hash) {
                // user authentication details are correct
            //    return $result;
            //}

        } else {
            // user not found
            return false;
        }
    }
 
    /**
     * Check user existence.
     *
     * @param email
     * returns boolean flag indicating user existence
     */
    public function isUserExists($email) {
        $result = mysql_query("SELECT email from users WHERE email = '$email'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user exists
            return true;
        } else {
            // user doesn't exist
            return false;
        }
    }
 
    /**
     * Encrypt a given password.
     *
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypt a password.
     *
     * @param salt
     * @param password
     * returns a hash string
     */
    public function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }
 
}
 
?>