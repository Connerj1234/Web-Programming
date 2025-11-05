<?php session_start();

/* Chose extra feature #1 - exemplified in signup-submit.php, common.php, and matches-submit.php */

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
  <li><a href="signup.php"><img src="Screenshot 2025-11-05 at 5.01.49 PM.png" alt="" width="24"> Sign up</a></li>
  <li><a href="matches.php"><img src="Screenshot 2025-11-05 at 5.02.27 PM.png" alt="" width="24"> Check matches</a></li>
</ul>
<?php page_footer(); ?>
