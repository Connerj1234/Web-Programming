<?php
/*
 * common.php
 * Shared page header/footer and small helpers for NerdLuv.
 * Uses nerdluv.css (provided).
 */

function page_header(string $title = "NerdLuv") { ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8" />
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="nerdluv.css" />
  </head>
  <body>
    <div id="bannerarea">
      <a href="index.php"><img src="user.jpg" alt="NerdLuv" style="width:64px;vertical-align:middle;margin-right:8px;"></a>
      <h1 style="display:inline;">NerdLuv</h1>
    </div>
<?php }

function page_footer() { ?>
    <div id="w3c">
      <a href="https://validator.w3.org/"><img src="https://www.w3.org/Icons/valid-html401" alt="Valid HTML" /></a>
      <a href="https://jigsaw.w3.org/css-validator/"><img src="https://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS" /></a>
    </div>
  </body>
  </html>
<?php }

/* Read all profiles from singles.txt as arrays with named keys */
function read_profiles(string $path = "singles.txt"): array {
  if (!is_readable($path)) return [];
  $rows = array_filter(array_map("trim", file($path, FILE_IGNORE_NEW_LINES)));
  $profiles = [];
  foreach ($rows as $line) {
    $parts = array_map("trim", explode(",", $line));
    if (count($parts) === 7) {
      [$name,$gender,$age,$ptype,$os,$min,$max] = $parts;
      $profiles[] = [
        "name" => $name,
        "gender" => $gender,
        "age" => (int)$age,
        "ptype" => strtoupper($ptype),
        "os" => $os,
        "min" => (int)$min,
        "max" => (int)$max
      ];
    }
  }
  return $profiles;
}

/* Find a profile by exact name (assumed to exist per spec) */
function find_profile_by_name(array $profiles, string $name): ?array {
  foreach ($profiles as $p) {
    if (strcasecmp($p["name"], $name) === 0) return $p;
  }
  return null;
}

/* personality match: share at least one same-index letter */
function personality_overlap(string $a, string $b): bool {
  $a = strtoupper($a);
  $b = strtoupper($b);
  $len = min(strlen($a), strlen($b));
  for ($i = 0; $i < $len; $i++) {
    if ($a[$i] === $b[$i]) return true;
  }
  return false;
}

/* Determine if $other is a match for $me according to assignment rules */
function is_match(array $me, array $other): bool {
  if ($me["gender"] === $other["gender"]) return false;
  if (!($other["age"] >= $me["min"] && $other["age"] <= $me["max"])) return false;
  if (!($me["age"] >= $other["min"] && $me["age"] <= $other["max"])) return false;
  if ($me["os"] !== $other["os"]) return false;
  if (!personality_overlap($me["ptype"], $other["ptype"])) return false;
  return true;
}

/* Render one match block */
function render_match(array $p) { ?>
  <div class="match">
    <p>
      <img src="user.jpg" alt="User" />
      <?= htmlspecialchars($p["name"]) ?>
    </p>
    <ul>
      <li><strong>Gender:</strong> <?= htmlspecialchars($p["gender"]) ?></li>
      <li><strong>Age:</strong> <?= (int)$p["age"] ?></li>
      <li><strong>Personality:</strong> <?= htmlspecialchars($p["ptype"]) ?></li>
      <li><strong>OS:</strong> <?= htmlspecialchars($p["os"]) ?></li>
    </ul>
  </div>
<?php }
