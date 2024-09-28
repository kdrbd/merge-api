<?php

namespace Core;

use Core\Session;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    } //end constructor;

    public function userRegistration($data)
    {
        $firstName = $data['first_name'];
        $lastName  = $data['last_name'];
        $username  = $data['username'];
        $phone     = $data['phone'];
        $email     = $data['email'];
        $pass      = $data['pass_word'];

        $checkUserName  = $this->checkUserName($username);
        $checkPhone     = $this->checkPhone($phone);
        $checkEmail     = $this->checkEmail($email);

        if ($firstName == "" or $lastName == "" or $username == "" or $phone == "" or $email == "" or $pass == "") {
            $msg = "<span style='color:red;'>Field could not be empty! </span>";
            return $msg;
        }

        if (strlen($username) < 5) {
            $msg = "<span style='color:red;'>User Name at least 5 character! </span>";
            return $msg;
        } elseif (preg_match('/[^a-z0-9_-]+/i', $username)) {
            $msg = "<span style='color:red;'> Invalid User Name! </span>";
            return $msg;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $msg = "<span style='color:red;'> Invalid Email! </span>";
            return $msg;
        }

        if ($checkUserName == true) {
            $msg = "<span style='color:red;'> Duplicate User Name! </span>";
            return $msg;
        }

        if ($checkPhone == true) {
            $msg = "<span style='color:red;'> Duplicate Phone! </span>";
            return $msg;
        }

        if ($checkEmail == true) {
            $msg = "<span style='color:red;'> Duplicate Email! </span>";
            return $msg;
        }

        if (strlen($pass) < 5) {
            $msg = "<span style='color:red;'>Pass_word at least 5 character! </span>";
            return $msg;
        }

        $pass_word = md5($pass);

        //Inser / Register Now.....

        $sql = "INSERT INTO users 
        (first_name, last_name, username, phone, m_verified, email, e_verified, pass_word, role, lebel, img_url, is_del) VALUES 
        (:first_name, :last_name, :username, :phone, :m_verified, :email, :e_verified, :pass_word, :role, :lebel, :img_url, :is_del)";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':first_name', $firstName);
        $query->bindValue(':last_name', $lastName);
        $query->bindValue(':username', $username);
        $query->bindValue(':phone', $phone);
        $query->bindValue(':m_verified', NULL);
        $query->bindValue(':email', $email);
        $query->bindValue(':e_verified', NULL);
        $query->bindValue(':pass_word', $pass_word);
        $query->bindValue(':role', NULL);
        $query->bindValue(':lebel', NULL);
        $query->bindValue(':img_url', NULL);
        $query->bindValue(':is_del', NULL);
        $rst = $query->execute();

        if (isset($rst)) {
            return "Success";
        } else {
            return "Failed";
        }
    } // end userReg

    public function checkUserName($username)
    {
        //Check User Name
        $sql = "SELECT username FROM users WHERE username = :username";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':username', $username);
        $query->execute();

        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } // end checkUserName

    public function checkPhone($phone)
    {
        //Check User Name
        $sql = "SELECT phone FROM users WHERE phone = :phone";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':phone', $phone);
        $query->execute();

        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } // end checkPhone

    public function checkEmail($email)
    {
        $sql = "SELECT email FROM users WHERE email = :email";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':email', $email);
        $query->execute();

        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } // end Email Check;

    public function checkPass($pass)
    {
        $sql = "SELECT pass_word FROM users WHERE pass_word = :pass";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':pass', $pass);
        $query->execute();

        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } // end checkPass;

    public function getLoginUser($email, $pass_word)
    {
        $sql = "SELECT * FROM users WHERE username = :email AND pass_word = :pass_word LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':email', $email);
        $query->bindValue(':pass_word', $pass_word);
        $query->execute();
        $result = $query->fetch();
        return $result;
    } // end getLoginUser

    public function userLogin($data)
    {
        $user  = $data['username'];
        $pass  = md5($data['pass_word']);

        $checkEmail = $this->checkUserName($user);
        $checkPass  = $this->checkPass($pass);
        //$ckpass   = $this->passCheck($pass_word);

        if ($user == "" or $pass == "") {
            $msg = "<span style='color:red;'>Field could not be empty! </span>";
            return $msg;
        }

        // if (filter_var($user, FILTER_VALIDATE_EMAIL) === false) {
        //     $msg = "<span style='color:red;'> Invalid Email! </span>";
        //     return $msg;
        // }

        if ($checkEmail == false) {
            $msg = "<span style='color:red;'>User Not Found!</span>";
            return $msg;
        }

        if ($checkPass == false) {
            $msg = "<span style='color:red;'>Password Wrong!</span>";
            return $msg;
        }

        $lgusr = $this->getLoginUser($user, $pass);

        if ($lgusr) {
            Session::init();
            Session::set("login", true);
            Session::set("id", $lgusr['id']);
            Session::set("username", $lgusr['username']);
            Session::set("loginmsg", "<span style='color:green;'> Login Successfull! Welcome  <strong> " . Session::get('username') . " </strong> </span>");
            echo "<script>window.location.href = '/dashboard';</script>";
        } else {
            $msg = "<span style='color:red;'> Login Failed...! </span>";
            return $msg;
        }
    } // end User Login

    public function getUserData()
    {
        $sql = "SELECT * FROM users ORDER BY id";
        $query = $this->db->pdo->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    } // end getUserData();

    public function singleUser($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':id', $id);
        $query->execute();
        $user = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $user;
    }
}
