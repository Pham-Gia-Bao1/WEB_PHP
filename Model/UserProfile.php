<?php
include_once("../Model/Blog.php");
include_once("../Model/Account.php");

class UserProfile extends Account
{
    public function change_avatar($newAvatarUrl)
    {
        $blog = new Blog();
        $conn = $blog->connect_database();

        // Lấy tên người dùng từ cookie
        $username = base64_decode($_COOKIE['User']);

        // Cập nhật đường dẫn hình đại diện mới trong cơ sở dữ liệu
        $sql = "UPDATE users SET img = :newAvatarUrl WHERE name = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':newAvatarUrl', $newAvatarUrl);
        $stmt->bindParam(':username', $username);
        $result = $stmt->execute();

        // Đóng kết nối đến cơ sở dữ liệu
        $blog->closeConnection();

        return $result;
    }


    public function get_name_pass_email_user()
    {
        $blog = new Blog();
        $conn = $blog->connect_database();
        $username = base64_decode($_COOKIE['User']);

        $sql = "SELECT name, password, email FROM users WHERE name = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $name_pass_email = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name_pass_email[] = $row['name'];
            $name_pass_email[] = $row['password'];
            $name_pass_email[] = $row['email'];
        }
        
        $blog->closeConnection();

        return $name_pass_email;
    }

}