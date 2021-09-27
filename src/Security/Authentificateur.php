<?php
namespace App\Security;

use App\Entity\User;


//Start session

session_start();

//Array to store validation errors

$errmsg_arr = array();


//Validation error flag

$errflag = false;



//Function to sanitize values received from the form. Prevents SQL injection

function clean($str) {

    $str = @trim($str);

    if(get_magic_quotes_gpc()) {

        $str = stripslashes($str);

    }

    return mysqli_real_escape_string($str);

}



//Sanitize the POST values

$email = clean($_POST['email']);

$password = clean($_POST['password']);



//Input Validations

if($email == '') {

    $errmsg_arr[] = 'Email missing';

    $errflag = true;

}

if($password == '') {

    $errmsg_arr[] = 'Password missing';

    $errflag = true;

}



//If there are input validations, redirect back to the login form

if($errflag) {

    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;

    session_write_close();

    header("location: app_login");

    exit();

}



//Create query

$query="SELECT * FROM user WHERE email='$email' AND password='$password'";

$result=mysqli_query($query);



//Check whether the query was successful or not

if($result) {

    if(mysqli_num_rows($result) > 0) {

        //Login Successful

        session_regenerate_id();

        $user = mysqli_fetch_assoc($result);

        $_SESSION['SESS_MEMBER_ID'] = $user['id'];

        $_SESSION['SESS_FIRST_NAME'] = $user['email'];

        $_SESSION['SESS_LAST_NAME'] = $user['password'];

        session_write_close();

        header("location: main_acceuil");

        exit();

    }else {

        //Login failed

        $errmsg_arr[] = 'email and password not found';

        $errflag = true;

        if($errflag) {

            $_SESSION['ERRMSG_ARR'] = $errmsg_arr;

            session_write_close();

            header("location: main_accueil");

            exit();

        }

    }

}else {

    die("Query failed");

}

?>