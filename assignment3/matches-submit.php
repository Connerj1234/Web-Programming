<?php
require_once "common.php";

$name = isset($_GET["name"]) ? trim($_GET["name"]) : "";

if (!is_valid_name($name)) {
  error_page("Name cannot be blank.");
}

$profiles = read_profiles("singles.txt");
$me = find_profile_by_name($profiles, $name);

if (!$me) {
  error_page("No such user found. Please sign up first.");
}

page_header("NerdLuv - Matches for " . $me["name"]);
?>
<h1>Matches for <?= htmlspecialchars($me["name"]) ?></h1>
<?php
$found = false;
foreach ($profiles as $p) {
  if ($p["name"] === $me["name"]) continue;
  if (is_match($me, $p)) {
    $found = true;
    render_match($p);
  }
}
if (!$found) {
  echo "<p>No matches found. Try widening your age range or updating your profile.</p>";
}
page_footer();
