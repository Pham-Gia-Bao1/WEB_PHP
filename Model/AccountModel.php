<?php
include_once("../Model/BlogModel.php");

class Account
{
    private $user_name = "";
    private $password = "";
    private $email = "";
    private $img = "https://cdn-icons-png.flaticon.com/512/3177/3177440.png";
    private $conn;
    public function __construct($name = "", $password = "", $email = "", $img = "")
    {
        $this->user_name = $name;
        $this->password = $password;
        $this->email = $email;
        if (!empty($img)) {
            $this->img = $img;
        }
    }
    public function getImg()
    {
        return $this->img;
    }
    public function getUserName()
    {
        return $this->user_name;
    }
    public function setUserName($name)
    {
        $this->user_name = $name;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setImg($img)
    {
        $this->img = $img;
    }

    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Sign up function
    public static function signUpAccount($username, $password, $email, $role_id)
    {
        if (!empty($username) && !empty($password) && !empty($email)) {
            $account = new Account($username, $password, $email);
            $blog = new Blog();
            $conn = $blog->connect_database();
            $sql_check = "SELECT * FROM users WHERE email = :email or name = :name";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bindParam(':email', $email);
            $stmt_check->bindParam(':name', $username);
            $stmt_check->execute();
            if ($stmt_check->rowCount() > 0) {
                return false;
            }
            // Insert the new user into the users table
            $sql = "INSERT INTO users (name, email, password, role_id, img)
                    VALUES (:username, :email, :password, :role_id , :img)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role_id', $role_id);
            $img = $account->getImg();
            $stmt->bindParam(':img', $img);
            $stmt->execute();
            $blog->closeConnection();

            return true;
        }
        return false;
    }
    public static function login($name, $email, $password)
    {
        if (isset($email) && isset($password)) {
            $blog = new Blog();
            $conn = $blog->connect_database();
            $sql = "SELECT users.id,users.password as pass FROM users WHERE users.name = :name and users.email = :email AND users.password = :password";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $result = $stmt->fetch((PDO::FETCH_ASSOC));
            $blog->closeConnection();
            if($result['pass'] == $password){
                return true;
            }else{
                return false;
            }

        }
        return false;
    }
    public function logOut()
    {
        setcookie("User", "", time() - 3600, "/"); // Setting an expired time in the past deletes the cookie
        header("Location: home");
        exit;
    }
    public function get_name_and_img_user()
    {
        $blog = new Blog();
        $conn = $blog->connect_database();
        if(isset($_COOKIE['User'])){
            $username = base64_decode($_COOKIE['User']);
            $sql = "SELECT name, img FROM users WHERE name=:username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $name_and_img = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($name_and_img, $row['name']);
                array_push($name_and_img, $row['img']);
            }
            return $name_and_img; // mảng có length = 2

        }
        // return null;
    }
    public function get_name_and_img_user_by_id($id)
    {
        $blog = new Blog();
        $conn = $blog->connect_database();
        $sql = "SELECT name, img FROM users WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $name_and_img = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($name_and_img, $row['name']);
            array_push($name_and_img, $row['img']);
        }
        return $name_and_img; // mảng có length = 2
    }
    public function change_avatar($newAvatarUrl)
    {
        $blog = new Blog();
        $conn = $blog->connect_database();
        $username = base64_decode($_COOKIE['User']);
        $sql = "UPDATE users SET img = :newAvatarUrl WHERE name = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':newAvatarUrl', $newAvatarUrl);
        $stmt->bindParam(':username', $username);
        $result = $stmt->execute();
        $blog->closeConnection();
        return $result;
    }
    public function updateUserInfo($newName, $newPassword, $newEmail)
    {
        $blog = new Blog();
        $conn = $blog->connect_database();
        $username = base64_decode($_COOKIE['User']);
        $sql = "UPDATE users SET name = :newName, password = :newPassword, email = :newEmail WHERE name = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':newName', $newName);
        $stmt->bindParam(':newPassword', $newPassword);
        $stmt->bindParam(':newEmail', $newEmail);
        $stmt->bindParam(':username', $username);
        $result = $stmt->execute();
        $new_user_name = base64_encode($newName);
        setcookie("User", $new_user_name, time() + (86400 * 30), "/"); // 86400 = 1 day
        $blog->closeConnection();
        return $result;
    }
    public function updateExpert($id, $role_id, $full_name, $gender, $address, $email, $phone_number, $age, $experience, $profile_picture, $count_rating, $certificate, $specialization, $status) {
        $blog = new Blog();
        $conn = $blog->connect_database();
        $sql = "UPDATE experts SET
                role_id = :role_id,
                full_name = :full_name,
                gender = :gender,
                address = :address,
                email = :email,
                phone_number = :phone_number,
                age = :age,
                experience = :experience,
                profile_picture = :profile_picture,
                count_rating = :count_rating,
                certificate = :certificate,
                specialization = :specialization,
                status = :status
                WHERE id = :id";  // Remove 'experts.' before 'id'
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":role_id", $role_id);
        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":gender", $gender);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone_number", $phone_number);
        $stmt->bindParam(":age", $age);
        $stmt->bindParam(":experience", $experience);
        $stmt->bindParam(":profile_picture", $profile_picture);
        $stmt->bindParam(":count_rating", $count_rating);
        $stmt->bindParam(":certificate", $certificate);
        $stmt->bindParam(":specialization", $specialization);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);  // Add binding for 'id'
        $stmt->execute();
        return true;
    }
    public function get_id_expert()
    {
        $blog = new Blog();
        $conn = $blog->connect_database();
        if (!isset($_SESSION["User"])) {
            $user_name = base64_decode($_COOKIE['User']);
            $sql = "SELECT id FROM experts WHERE full_name = :name"; // Corrected the column name
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $user_name);
            $stmt->execute();
            $id = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($id)) {
                return $id[0]['id'];
            }
        }
        return null;
    }
    public function get_id()
    {
        $blog = new Blog();
        $conn = $blog->connect_database();
        if (!isset($_SESSION["User"])) {
            $user_name = base64_decode($_COOKIE['User']);
            $sql = "SELECT users.id from users where name = :name";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $user_name);
            $stmt->execute();
            $id = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $id[0]['id'];
        }
        return null;
    }
    public function get_role_id()
    {
        $blog = new Blog();
        $conn = $blog->connect_database();
        if (isset($_COOKIE["User"])) {
            $user_name = base64_decode($_COOKIE['User']);
            $sql = "SELECT role_id from users where users.name = :name";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $user_name);
            $stmt->execute();
            $id = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $id[0]['role_id'];
        }
        return 2;
    }
    public function compare_user_password($password,$name)
    {   $passwordPattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=]).{8,}$/';
        $blog = new Blog();
        $conn = $blog->connect_database();
        $sql = "SELECT users.password FROM users WHERE users.password = :password and users.name = :name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        $data = $stmt->fetch();
        if(preg_match($passwordPattern,$data)){
            return true;
        }else{
            return false;
        }
    }
    public function add_expert($role_id, $full_name, $gender, $address, $email, $phone_number, $age, $experience, $profile_picture, $count_rating, $certificate, $specialization, $status)
    {
        $blog = new Blog();
        $conn = $blog->connect_database();
        // $sql = "SELECT users.email from users where "
        $sql = "INSERT INTO experts (role_id, full_name, gender, address, email, phone_number, age, experience, profile_picture, count_rating, certificate, specialization, status) VALUES (:role_id, :full_name, :gender, :address, :email, :phone_number, :age, :experience, :profile_picture, :count_rating, :certificate, :specialization, :status)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":role_id", $role_id);
        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":gender", $gender);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone_number", $phone_number);
        $stmt->bindParam(":age", $age);
        $stmt->bindParam(":experience", $experience);
        $stmt->bindParam(":profile_picture", $profile_picture);
        $stmt->bindParam(":count_rating", $count_rating);
        $stmt->bindParam(":certificate", $certificate);
        $stmt->bindParam(":specialization", $specialization);
        $stmt->bindParam(":status", $status);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            return true;
        }
        return false;
    }
}
