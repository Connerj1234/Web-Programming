<?php

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
      <a href="index.php">
        <img src="Screenshot 2025-11-05 at 4.59.48 PM.png" alt="NerdLuv" style="width:220px;vertical-align:middle;margin-right:8px;">
      </a>
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

/* Error page */
function error_page(string $message = "We're sorry. You submitted invalid user information. Please go back and try again.") {
  page_header("NerdLuv - Error");
  ?>
  <h1>Error! Invalid data.</h1>
  <p><?= htmlspecialchars($message) ?></p>
  <p>This page is for single nerds to meet and date each other. Type in your personal information and wait for the nerdly luv to begin. Thank you for using our site.</p>
  <p><a href="index.php">Back to front page</a></p>
  <?php
  page_footer();
  exit;
}

/*  File I/O */
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

function find_profile_by_name(array $profiles, string $name): ?array {
  foreach ($profiles as $p) {
    if (strcasecmp($p["name"], $name) === 0) return $p;
  }
  return null;
}

function profiles_contains_name(array $profiles, string $name): bool {
  return find_profile_by_name($profiles, $name) !== null;
}

/*  Validation helpers */
function is_valid_name(?string $s): bool {
  return is_string($s) && preg_match('/\S/u', $s) === 1;
}
function is_valid_gender(?string $s): bool {
  return in_array($s, ["M","F"], true);
}
function is_valid_age(?string $s): bool {
  if (!is_string($s) || preg_match('/^\d{1,2}$/', $s) !== 1) return false;
  $n = (int)$s;
  return $n >= 0 && $n <= 99;
}
function is_valid_ptype(?string $s): bool {
  return is_string($s) && preg_match('/^[IE][NS][FT][JP]$/i', $s) === 1;
}
function is_valid_os(?string $s): bool {
  return in_array($s, ["Windows","Mac OS X","Linux"], true);
}
function is_valid_range(?string $min, ?string $max): bool {
  if (!is_valid_age($min) || !is_valid_age($max)) return false;
  return (int)$min <= (int)$max;
}

/*  Matching logic  */
function personality_overlap(string $a, string $b): bool {
  $a = strtoupper($a);
  $b = strtoupper($b);
  $len = min(strlen($a), strlen($b));
  for ($i = 0; $i < $len; $i++) {
    if ($a[$i] === $b[$i]) return true;
  }
  return false;
}

function is_match(array $me, array $other): bool {
  if ($me["gender"] === $other["gender"]) return false;
  if (!($other["age"] >= $me["min"] && $other["age"] <= $me["max"])) return false;
  if (!($me["age"] >= $other["min"] && $me["age"] <= $other["max"])) return false;
  if ($me["os"] !== $other["os"]) return false;
  if (!personality_overlap($me["ptype"], $other["ptype"])) return false;
  return true;
}

function render_match(array $p) { ?>
  <div class="match">
    <p>
      <img src="Screenshot 2025-11-05 at 5.06.03 PM.png" alt="User" />
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
