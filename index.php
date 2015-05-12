<?php

require( "config.php" );
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";

switch ( $action ) {
  case 'archive':
    IController::archive();
    break;
  case 'viewArticle':
    IController::viewArticle();
    break;
  default:
    IController::homepage();
}

?>
