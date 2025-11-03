<?php
require_once "common.php";

/* Read POSTed form fields (assume valid per spec) */
$name  = $_POST["name"] ?? "";
$gender= $_POST["gender"] ?? "F";
$age   = $_POST["age"] ?? "";
$ptype = $_POST["ptype"] ?? "";
$os    = $_POST["os"] ?? "Windows";
$min   = $_POST["min"] ?? "";
$max   = $_POST["max"] ?? "";

/* Build the CSV line exactly as required: name,gender(M/F),age,ptype,os,min,max */
$line = sprintf("%s,%s,%d,%s,%s,%d,%d\n",
  str_replace(["\n","\r"], "", $name),
  $gender,
  (int)$age,
  strtoupper($ptype),
  $os,
  (int)$min,
  (int)$max
);

/* Append to singles.txt */
file_put_contents("singles.txt", $line, FILE_APPEND);

page_header("NerdLuv - Thank You");
?>
<h1>Thank you!</h1>
<p>Welcome, <strong><?= htmlspecialchars($name) ?></strong>! Your information has been added.</p>
<p>Now you can <a href="matches.php">log in to see your matches</a>!</p>
<?php page_footer(); ?>
