<?php

require_once("config.php");
$actP= isset( $_GET['action'] ) ? $_GET['action'] : "";
$action = isset( $_POST['action'] ) ? $_POST['action'] : $actP ;
$user;

switch ( $action ) {
    case 'archive':
       UserController::archive();
        break;
    case 'viewArticle':
        UserController::viewArticle();
        break;
    case 'SignIn':
        UserController::viewArticle();
        break;
    case 'Registration':
        UserController::regPage();
        break;
    case 'logout':
        logout();
        break;
    case 'Sign UP':
        $user = new UserActions();
        $user->regUser($_POST);
        break;
    default:
        UserController::homepage();
}

