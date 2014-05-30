<?php

/**
 * Database configuration/connection variables
 */
define("DB_HOST", "mysql12.000webhost.com");
define("DB_USER", "a6725582_test");
define("DB_PASSWORD", "testing1");
define("DB_DATABASE", "a6725582_tutors");

/**
 * Database interaction variables
 */

// Auth: Request types
define("LOGIN", "login");
define("REGISTER", "register");

// Request response codes
define("FAILURE", 0);
define("SUCCESS", 1);

// Error codes
define("NO_ERROR", 0);
define("REG_ERROR", 1);                   // registration error
define("REG_ERROR_USER_EXISTS", 2);       // registration error
define("INCORRECT_EMAIL_OR_PASSWORD", 3); // login error

?>