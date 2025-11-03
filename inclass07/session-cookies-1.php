<?php

date_default_timezone_set('America/New_York');
$current_date = date("l, F j, Y \\a\\t g:i A");

// If the "VISIT_NUMBER" cookie exists, increase it by 1
if (isset($_COOKIE["VISIT_NUMBER"])) {
    $visit_number = $_COOKIE["VISIT_NUMBER"] + 1;
} else {
    $visit_number = 1;
}

// If the "FIRST_VISIT" cookie does not exist, set it now (it should remain constant)
if (!isset($_COOKIE["FIRST_VISIT"])) {
    $first_visit = $current_date;
    setcookie("FIRST_VISIT", $first_visit, time() + (86400 * 14)); // 14 days
} else {
    $first_visit = $_COOKIE["FIRST_VISIT"];
}

// Set/update the "LAST_VISIT" cookie to the current date
setcookie("LAST_VISIT", $current_date, time() + (86400 * 14));
setcookie("VISIT_NUMBER", $visit_number, time() + (86400 * 14));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Session and Cookies Example</title>
</head>
<body style="font-family: Arial; margin: 40px;">
  <h2>Cookie and Visit Tracker</h2>

  <?php
  if (isset($_COOKIE["LAST_VISIT"])) {
      echo "<p>You have visited this web page <strong>$visit_number</strong> times.</p>";
      echo "<p>Your first visit was on <strong>$first_visit</strong>.</p>";
      echo "<p>Your last visit was on <strong>{$_COOKIE['LAST_VISIT']}</strong>.</p>";
  } else {
      echo "<p>Welcome! This is your first visit to this page.</p>";
      echo "<p>Your first visit date has been recorded as <strong>$current_date</strong>.</p>";
  }
  ?>
</body>
</html>
