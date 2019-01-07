<?php
 
header('Content-type: text/plain; charset=utf-8');
class DB_Functions {
 
    private $conn;
 
    function __construct() {
        require __DIR__ . "/DB_Connect.php";
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
    // destructor
    function __destruct() {

    }
 
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password, $facebook_json) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
 
        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, encrypted_password, salt, facebook_json, created_at) VALUES(?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $uuid, $name, $email, $encrypted_password, $salt, $facebook_json);
        $result = $stmt->execute();
        $stmt->close();
 
        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            return $user;
        } else {
            return false;
        }
    }
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
 
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
    }
     /**
     * Get user by email
     */
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM `users` WHERE `email` = ? ");

        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $user;
        } else {
            return NULL;
        }
    }
 
    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");
        $stmt->bind_param("s", $email);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
    /**
     * Updating facebook_json field
     */
    public function updateFacebookJson($email, $facebook_json) {
        $stmt = $this->conn->prepare("UPDATE users SET facebook_json = ?, updated_at = NOW() WHERE email = ?");
 
        $stmt->bind_param("ss",$facebook_json, $email);
 
        $stmt->execute();
 
        $stmt->store_result();
    }
    /**
     * Get Facebook Json
     */
    public function getFacebookJson($email) {
         $stmt = $this->conn->prepare("SELECT facebook_json FROM users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 

        if ($stmt->execute()) {
            $facebook_json = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $facebook_json;

        } else {
            return NULL;
        }
    }

     /**
     * Updating password
     */
    public function updatePassword($email, $password, $current_password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $current_password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                $hash = $this->hashSSHA($password);
                $encrypted_password = $hash["encrypted"];
                $salt = $hash["salt"];

                $stmt = $this->conn->prepare("UPDATE users SET encrypted_password=?, salt=?, updated_at = NOW() WHERE email = ?");

                $stmt->bind_param("sss",$encrypted_password, $salt, $email);

                $result = $stmt->execute();

                $stmt->store_result();
                $stmt->close();
                if($result) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
     /**
     * Updating password for facebook_login first time
     */
    public function updatePasswordFacebook($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $hash = $this->hashSSHA($password);
            $encrypted_password = $hash["encrypted"];
            $salt = $hash["salt"];

            $stmt = $this->conn->prepare("UPDATE users SET encrypted_password=?, salt=?, updated_at = NOW() WHERE email = ?");

            $stmt->bind_param("sss",$encrypted_password, $salt, $email);

            $result = $stmt->execute();

            $stmt->store_result();
            $stmt->close();
            if($result) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Updating email
     */
    public function updateEmail($email, $password, $new_email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                $stmt = $this->conn->prepare("UPDATE users SET email=?, updated_at = NOW() WHERE email = ?");

                $stmt->bind_param("sss",$new_email, $email);

                $result = $stmt->execute();

                $stmt->store_result();
                $stmt->close();
                if($result) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
 
    /**
     * Encrypting password
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
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
 
}
 
?>
