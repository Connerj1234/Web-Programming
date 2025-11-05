<?php
require_once "common.php";
page_header("NerdLuv - Sign Up");
?>
<h1>New User Signup</h1>

<form action="signup-submit.php" method="post" novalidate>
  <fieldset>
    <legend>New User Signup</legend>

    <p>
      <label class="left" for="name"><strong>Name:</strong></label>
      <input type="text" id="name" name="name" maxlength="16" size="16" required />
    </p>

    <p>
      <span class="left"><strong>Gender:</strong></span>
      <label><input type="radio" name="gender" value="F" checked /> Female</label>
      <label><input type="radio" name="gender" value="M" /> Male</label>
    </p>

    <p>
      <label class="left" for="age"><strong>Age:</strong></label>
      <input type="text" id="age" name="age" maxlength="2" size="6" required />
    </p>

    <p>
      <label class="left" for="ptype">
        <strong><a href="http://www.humanmetrics.com/cgi-win/JTypes2.asp" target="_blank" rel="noopener">Personality type:</a></strong>
      </label>
      <input type="text" id="ptype" name="ptype" maxlength="4" size="6" placeholder="e.g., ISTJ" required />
    </p>

    <p>
      <label class="left" for="os"><strong>Favorite OS:</strong></label>
      <select id="os" name="os">
        <option>Windows</option>
        <option>Mac OS X</option>
        <option>Linux</option>
      </select>
    </p>

    <p>
      <span class="left"><strong>Seeking age:</strong></span>
      <input type="text" name="min" maxlength="2" size="6" placeholder="min" required />
      to
      <input type="text" name="max" maxlength="2" size="6" placeholder="max" required />
    </p>

    <p>
      <input type="submit" value="Sign Up" />
    </p>
  </fieldset>
</form>

<p>Already have an account? <a href="matches.php">Log in to see your matches</a>.</p>
<?php page_footer(); ?>
