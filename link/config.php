<?php
ini_set( "display_errors", true );
date_default_timezone_set( "Australia/Sydney" );
define( "DB_DSN", "mysql:host=localhost;dbname=cms;charset=utf8" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "1111" );
define( "CLASS_PATH", "./core/action" );
define( "TEMPLATE_PATH", "./core/view" );
define( "HOMEPAGE_NUM_ARTICLES", 5 );
define( "ADMIN_USERNAME", "admin" );
define( "ADMIN_PASSWORD", "1111" );
require( CLASS_PATH ."/UserController.php" );
require( CLASS_PATH ."/UserActions.php" );

function handleException( $exception ) {
    echo "Sorry, a problem occurred. Please try later.";
    error_log( $exception->getMessage() );
}

set_exception_handler( 'handleException' );
?>