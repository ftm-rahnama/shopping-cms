<?php
require_once "include.php";
if (isset($_SESSION["userInfo"])) {
    $user_id = $_SESSION["userInfo"]["id"];
    $email = $_SESSION["userInfo"]["userEmail"];
    $user_name = $_SESSION["userInfo"]["userName"];
    $details = "خروج";
    $log = Log::insertLog($user_id, $_SERVER["REMOTE_ADDR"], $email, $user_name, $details);
    session_unset();
    session_destroy();
    header("Location:../index.php");
}
