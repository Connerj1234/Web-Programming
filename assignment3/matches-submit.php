<?php
require_once "common.php";

$name = $_GET["name"] ?? "";
$profiles = read_profiles("singles.txt");
$me = find_profile_by_name($profiles, $name);

page_header("NerdLuv - Matches for " . $name);
?>
<h1>Matches for <?= htmlspecialchars($name) ?></h1>

<?php
if (!$me) {
  /* Per spec we can assume the name exists, but guard anyway */
  echo "<p>No such user found.</p>";
} else {
  $found = false;
  foreach ($profiles as $p) {
    if ($p["name"] === $me["name"]) continue; // skip self
    if (is_match($me, $p)) {
      $found = true;
      render_match($p);
    }
  }
  if (!$found) {
    echo "<p>No matches found. Try widening your age range or updating your profile.</p>";
  }
}
page_footer();
