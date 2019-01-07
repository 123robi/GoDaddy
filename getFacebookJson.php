<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['email'])) {
 
    // receiving the post params
    $email = $_POST['email'];
 
    // get the user by email and password
    $facebook_json = $db->getFacebookJson($email);
 
    if ($facebook_json != false) {
        // use is found
        $response["error"] = FALSE;
        $response["facebook_json"] = $facebook_json;
        echo json_encode($response);
    } else {
        // user is not found with the credentials
        $response["error"] = TRUE;
        $response["error_msg"] = "No user was found";
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email missing!";
    echo json_encode($response);
}
?>