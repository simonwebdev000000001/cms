<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo htmlspecialchars( $results['pageTitle'] )?></title>
      <script>
          function newDoc() {
              window.location.assign("?action=Registration")
          }
      </script>
      <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
  </head>
  <body>
    <div id="container">
        <div id="reg_form">
	  <?php if(!isset($_SESSION['username'])){
		  echo '<form name="reg" method="POST" action="index.php">
		  <input type="text" name="username" placeholder="login" autofocus=true required/>
		   <input type="text" name="password" placeholder="password" required/>
		    <input type="button" value="Registration" onclick="newDoc()" name="action" />
		    <input type="submit" name="action" value="SignIn" />
	  </form> ';}
		  else {
			  echo '<div id="adminHeader">
        <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION[\'username\']) ?></b>. <a href="admin.php?action=logout"?>Log out</a></p>
      </div>';
		  }?>
            </div>
       <a href="."><img id="logo" src="images/logo.jpg" alt="Widget News" /></a>

