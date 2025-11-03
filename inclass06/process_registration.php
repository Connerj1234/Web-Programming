<?php
// process_registration.php
// Conner Jamison
// Purpose: Validate input and display submitted data dynamically.

// 1) Optional: turn on errors while developing (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2) Ensure this page is reached via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request. Please submit the form from index.html');
}

// 3) Sanitize helper that also works for arrays
function sanitize($value) {
    if (is_array($value)) {
        return array_map('sanitize', $value); // apply to each element
    }
    return htmlspecialchars(trim((string)$value), ENT_QUOTES, 'UTF-8');
}

// Sanitize all incoming POST data once
$data = sanitize($_POST);

// 4) Required fields and labels (must match index.html names)
$required = [
    'first_name','last_name','student_email','student_id',
    'birth_date','gender','major','academic_level'
];

$labels = [
    'first_name'     => 'First Name',
    'last_name'      => 'Last Name',
    'student_email'  => 'Email Address',
    'student_id'     => 'Student ID',
    'birth_date'     => 'Date of Birth',
    'gender'         => 'Gender',
    'major'          => 'Major',
    'academic_level' => 'Academic Level'
];

$errors = [];

// 5) Validate required fields
foreach ($required as $field) {
    $value = $data[$field] ?? '';
    if ($value === '' || (is_array($value) && count($value) === 0)) {
        $errors[] = ($labels[$field] ?? $field) . ' is required.';
    }
}

// 6) Email format check
if (!empty($data['student_email']) && !filter_var($data['student_email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email Address is not valid.';
}

// 7) Begin output HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Registration Results</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 2rem; background:#f7f7fb; }
    .error { color: #b00020; }
    .ok { color: #0a7e07; }
    .card { background:#fff; border:1px solid #e6e6ee; border-radius:12px; padding:24px; max-width:900px; }
    h1 { margin-top:0; }
    ul.error { margin-top:.5rem; }
    dl { display:grid; grid-template-columns: 220px 1fr; gap:.5rem 1rem; margin-top:1rem; }
    dt { font-weight:bold; margin:0; }
    dd { margin:0; }
    code { background:#f6f8fa; padding:2px 4px; border-radius:4px; }
    a.button { display:inline-block; padding:.6rem 1rem; border-radius:8px; text-decoration:none; border:1px solid #cfd3d7; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Registration Results</h1>

    <?php if (!empty($errors)): ?>
      <h2 class="error">Please fix the following:</h2>
      <ul class="error">
        <?php foreach ($errors as $msg): ?>
          <li><?= $msg ?></li>
        <?php endforeach; ?>
      </ul>
        <p><a href="javascript:history.back()" class="button">Go back to the form</a><p>
    <?php else: ?>
      <p class="ok"><strong>Success!</strong> Your form was submitted.</p>

      <h2>Submitted Data</h2>
      <dl>
        <?php // MAIN TASK: foreach over ALL fields (handles arrays too) ?>
        <?php foreach ($data as $name => $value): ?>
          <dt><?= ucwords(str_replace(['_', '-'], ' ', $name)) ?></dt>
          <dd>
            <?php if (is_array($value)): ?>
              <?= count($value) ? implode(', ', $value) : '<em>(none)</em>' ?>
            <?php else: ?>
              <?= ($value !== '') ? $value : '<em>(empty)</em>' ?>
            <?php endif; ?>
          </dd>
        <?php endforeach; ?>
      </dl>

        <p><a href="index.html" class="button">Reset form</a><p>
    <?php endif; ?>
</body>
</html>
