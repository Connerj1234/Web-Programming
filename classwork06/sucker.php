<?php

function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

function required($k){ return isset($_POST[$k]) && trim($_POST[$k]) !== ''; }

$name     = $_POST['name']     ?? '';
$section  = $_POST['section']  ?? '';
$card     = $_POST['card']     ?? '';
$cardtype = $_POST['cardtype'] ?? '';

// ---- Validation ----
$all_ok = required('name') && required('section') && required('card') && required('cardtype');

// Accept ####-####-####-#### OR 16 digits
$card_ok_format = preg_match('/^(?:\d{16}|\d{4}-\d{4}-\d{4}-\d{4})$/', $card) === 1;

// Normalize digits for prefix check
$digits = preg_replace('/[^0-9]/', '', $card);

$valid = $all_ok && $card_ok_format;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Buy Your Way to a Better Education</title>
  <link href="buyagrade.css" rel="stylesheet" type="text/css" />
  <style>
    body { font-family: Georgia, 'Times New Roman', Times, serif; background:#35285a; color:#111; }
    .panel { background:#fff; border: 12px solid #c9ba5b; margin: 2rem auto; padding: 1.5rem 2rem; max-width: 800px; }
    h1 { text-align:center; margin-top:0; }
    .error { color: #8b0000; font-weight: 700; }
    pre { background:#f6f8fa; padding:1rem; border-radius:8px; overflow:auto; }
    a { color:#4b2e83; font-weight:700; }
  </style>
</head>
<body>
  <div class="panel">
<?php if(!$valid): ?>
    <h1>Sorry</h1>
    <p class="error">You provided an invalid credit card number. <a href="buyagrade.html">Try again?</a></p>
<?php else: ?>
    <h1>Thanks, sucker!</h1>
    <p>Your information has been recorded.</p>

    <dl>
      <dt>Name</dt><dd><?= h($name) ?></dd>
      <dt>Section</dt><dd><?= h($section) ?></dd>
      <dt>Credit Card</dt><dd><?= h($card) ?> (<?= h($cardtype) ?>)</dd>
    </dl>

<?php
    // Save the entry
    $line = sprintf("%s;%s;%s;%s\n",
        str_replace(["\n",";"], [' ', '-'], $name),
        str_replace(["\n",";"], [' ', '-'], $section),
        $digits,
        $cardtype
    );
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'suckers.txt';
    file_put_contents($file, $line, FILE_APPEND | LOCK_EX);

    $all = file_get_contents($file);
?>
    <h2>All Submissions</h2>
    <pre><?= h($all) ?></pre>
<?php endif; ?>
  </div>
</body>
</html>
