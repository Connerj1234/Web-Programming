<?php session_start(); /* Starts the session */

if(!isset($_SESSION['UserData']['Username'])){
	header("location:login.php");
	exit;
}
?>

Congratulation! You have logged into password protected page. <a href="logout.php">Click here</a> to Logout.

<?php
require_once "common.php";
page_header("NerdLuv - Home");
?>
<h1>Welcome!</h1>
<ul>
  <li><a href="signup.php"><img src="user.jpg" alt="" width="24"> Sign up</a></li>
  <li><a href="matches.php"><img src="user.jpg" alt="" width="24"> Check matches</a></li>
</ul>
<?php page_footer(); ?>
