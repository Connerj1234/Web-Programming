<?php
require_once "common.php";

/* Grab form fields */
$name   = $_POST["name"]  ?? "";
$gender = $_POST["gender"]?? "";
$age    = $_POST["age"]   ?? "";
$ptype  = $_POST["ptype"] ?? "";
$os     = $_POST["os"]    ?? "";
$min    = $_POST["min"]   ?? "";
$max    = $_POST["max"]   ?? "";

/* Normalize fields */
$ptype = strtoupper(trim($ptype));
$os    = trim($os);
$name  = trim($name);

/*  Validation */
if (!is_valid_name($name)) {
  error_page("Name cannot be blank.");
}
if (!is_valid_gender($gender)) {
  error_page("Gender must be M or F.");
}
if (!is_valid_age($age)) {
  error_page("Age must be an integer between 0 and 99.");
}
if (!is_valid_ptype($ptype)) {
  error_page("Personality type must be a 4-letter Keirsey code like ISTJ or ENFP.");
}
if (!is_valid_os($os)) {
  error_page("Favorite operating system must be Windows, Mac OS X, or Linux.");
}
if (!is_valid_range($min, $max)) {
  error_page("Seeking ages must be integers between 0 and 99, and minimum must be less than or equal to maximum.");
}

/* Check duplicate name */
$profiles = read_profiles("singles.txt");
if (profiles_contains_name($profiles, $name)) {
  error_page("That name is already registered. Please choose a different name.");
}

/* All good: append to singles.txt in the exact CSV order required by the spec */
$line = sprintf("%s,%s,%d,%s,%s,%d,%d\n",
  str_replace(["\n","\r"], "", $name),
  $gender,
  (int)$age,
  $ptype,
  $os,
  (int)$min,
  (int)$max
);

if (@file_put_contents("singles.txt", $line, FILE_APPEND) === false) {
  error_page("Could not write to singles.txt. Check file permissions and enable group write.");
}

/* Thank-you page */
page_header("NerdLuv - Thank You");
?>
<h1>Thank you!</h1>
<p>Welcome, <strong><?= htmlspecialchars($name) ?></strong>. Your information has been added.</p>
<p>Now you can <a href="matches.php">log in to see your matches</a>.</p>
<?php page_footer(); ?>
