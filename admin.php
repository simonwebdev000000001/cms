<?php

require("config.php");



		 session_start();
		$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
		$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

		if ( $action != "login" && $action != "logout" && !$username ) {
			login();
			return;
			}

switch ( $action ) {
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'newArticle':
    newArticle();
    break;
  case 'editArticle':
    editArticle();
    break;
  case 'deleteArticle':
    deleteArticle();
    break;
  default:
    listArticles();
}


function login() {

  $results = array();
  $results['pageTitle'] = "Admin Login | Widget News";

  if ( isset( $_POST['login'] ) ) {
    if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {
      $_SESSION['username'] = ADMIN_USERNAME;
      header( "Location: index.php" );
    } else {
      $results['errorMessage'] = "Incorrect username or password. Please try again.";
      require( TEMPLATE_PATH . "/admin/loginForm.php" );
    }
  } else {
    require( TEMPLATE_PATH . "/admin/loginForm.php" );
  }

}


function logout() {
  unset( $_SESSION['username'] );
  header( "Location: index.php" );
}


function newArticle() {

  $results = array();
  $results['pageTitle'] = "New Article";
  $results['formAction'] = "newArticle";

  if ( isset( $_POST['saveChanges'] ) ) {
    $article = new IController;
    $article->storeFormValues( $_POST );
    $article->insert();
    header( "Location: index.php?status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {
    header( "Location: index.php" );
  } else {
    $results['article'] = new IController;
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }

}
function editArticle() {
  $results = array();
  $results['pageTitle'] = "Edit Article";
  $results['formAction'] = "editArticle";

  if ( isset( $_POST['saveChanges'] ) ) {
    if ( !$article = IController::getById( (int)$_POST['articleId'] ) ) {
      header( "Location: index.php?error=articleNotFound" );
      return;
    }
    $article->storeFormValues( $_POST );
    $article->update();
    header( "Location: index.php?status=changesSaved" );
  } elseif ( isset( $_POST['cancel'] ) ) {
    header( "Location: index.php" );
  } else {
    $results['article'] = IController::getById( (int)$_GET['articleId'] );
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }

}


function deleteArticle() { 
  if ( !$article = IController::getById( (int)$_GET['articleId'] ) ) {
    header( "Location: index.php?error=articleNotFound" );
    return;
  } 
  $article->delete();
  header( "Location: index.php?status=articleDeleted" );
}


function listArticles() {
  $results = array();
  $data = IController::getList();
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "All Articles"; 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "articleNotFound" ) $results['errorMessage'] = "Error: Article not found.";
  } 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "articleDeleted" ) $results['statusMessage'] = "Article deleted.";
  }

  require( TEMPLATE_PATH . "/admin/listArticles.php" );

}
?>
