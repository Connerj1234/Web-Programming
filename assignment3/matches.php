<?php
require_once "common.php";
page_header("NerdLuv - Matches");
?>
<h1>View Matches</h1>

<form action="matches-submit.php" method="get" novalidate>
  <fieldset>
    <legend>Returning User:</legend>

    <p>
      <label class="left" for="name"><strong>Name:</strong></label>
      <input type="text" id="name" name="name" maxlength="16" size="16" required />
    </p>

    <p>
      <input type="submit" value="View My Matches" />
    </p>
  </fieldset>
</form>

<p>New here? <a href="signup.php">Create an account</a>.</p>
<?php page_footer(); ?>
