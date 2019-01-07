<?php
header("Content-Type: text/html;charset=utf-8");
require_once  __DIR__ .'/include/DB_Functions.php';

$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
 
    // receiving the post params
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $facebook_json = NULL;
    
    if(isset($_POST['facebook_json'])) {
        $facebook_json = $_POST['facebook_json'];
    }
   
 
    // check if user is already existed with the same email
    if ($db->isUserExisted($email)) {
        // user already existed
        if(!($facebook_json === NULL)) {
            $db->updateFacebookJson($email, $facebook_json);
        }
        $user = $db->getUserByEmail($email);
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $email;
        $response["user"]["name"] = $user["name"];
        $response["user"]["email"] = $user["email"];
        $response["user"]["facebook_json"] = $user["facebook_json"];
        $response["user"]["created_at"] = $user["created_at"];
        $response["user"]["updated_at"] = $user["updated_at"];
        echo json_encode($response);
    } else {
        // create a new user
        $user = $db->storeUser($name, $email, $password, $facebook_json);
        if ($user) {
            // user stored successfully
            $response["error"] = FALSE;
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["facebook_json"] = $user["facebook_json"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}

?>