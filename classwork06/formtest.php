
<?php

function h($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

function show_value($key) {
    if (!isset($_POST[$key])) return '<em>(not set)</em>';
    $val = $_POST[$key];
    if (is_array($val)) {
        return h(implode(', ', $val));
    }
    return h($val);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Form Test Results</title>
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 2rem; }
    dt { font-weight: 700; }
    dd { margin: 0 0 .75rem 0; }
    pre { background:#f6f8fa; padding: 1rem; border-radius: 8px; overflow:auto; }
  </style>
</head>
<body>
  <h1>Thanks! Here is what you submitted</h1>
  <dl>
    <dt>Your Name</dt><dd><?= show_value('visitor_name') ?></dd>
    <dt>Password</dt><dd><?= show_value('password') ?></dd>
    <dt>Accepted License?</dt><dd><?= isset($_POST['licenseOK']) ? 'yes' : 'no' ?></dd>
    <dt>Account Type</dt><dd><?= show_value('account_type') ?></dd>
    <dt>User ID</dt><dd><?= show_value('userid') ?></dd>
    <dt>OS</dt><dd><?= show_value('system') ?></dd>
    <dt>Options (multi-select)</dt><dd><?= show_value('options') ?></dd>
    <dt>Remarks</dt><dd><?= nl2br(show_value('remarks')) ?></dd>
  </dl>

  <h2>Raw POST</h2>
  <pre><?php print_r($_POST); ?></pre>
</body>
</html>
