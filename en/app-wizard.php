<?php
session_start();
if (empty($_SESSION['buwana_id']) || empty($_SESSION['client_id'])) {
    header('Location: login.php');
    exit();
}

$lang = basename(dirname($_SERVER['SCRIPT_NAME']));
$page = 'app-wizard';
$version = '0.1';
$lastModified = date('Y-m-d\TH:i:s\Z', filemtime(__FILE__));
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <?php require_once("../meta/dashboard-en.php"); ?>
        <style>
            .wizard-step { display:none; }
            .wizard-step.active { display:block; }
            .wizard-buttons { text-align:center; margin-top:20px; }
            .wizard-buttons button { margin:0 5px; }
        </style>
    <?php require_once("../includes/buwana-index-inc.php"); ?>

<div id="form-submission-box" class="landing-page-form">
  <div class="form-container">
    <h1 style="text-align:center;">New App Setup</h1>
    <form id="appWizardForm" method="post" action="../scripts/create_app.php">
      <div id="step1" class="wizard-step active">
        <h2>Step 1: Basic Info</h2>
        <label>App Name<br><input type="text" name="app_name" required></label><br>
        <label>Registration Date<br><input type="date" name="app_registration_dt" required></label><br>
        <label>Redirect URIs<br><input type="text" name="redirect_uris" required></label><br>
        <label>App Login URL<br><input type="text" name="app_login_url"></label><br>
        <label>Scopes<br><input type="text" name="scopes"></label><br>
        <label>App Domain<br><input type="text" name="app_domain"></label><br>
        <label>App URL<br><input type="text" name="app_url"></label><br>
        <label>App Dashboard URL<br><input type="text" name="app_dashboard_url"></label><br>
        <label>Description<br><textarea name="app_description"></textarea></label><br>
        <label>Version<br><input type="text" name="app_version"></label><br>
        <label>Display Name<br><input type="text" name="app_display_name"></label><br>
        <label>Contact Email<br><input type="email" name="contact_email"></label><br>
      </div>
      <div id="step2" class="wizard-step">
        <h2>Step 2: Text Strings</h2>
        <label>Slogan<br><input type="text" name="app_slogan"></label><br>
        <label>Terms of Use<br><textarea name="app_terms_txt"></textarea></label><br>
        <label>Privacy Text<br><textarea name="app_privacy_txt"></textarea></label><br>
        <label>Emoji List (comma separated)<br><textarea name="app_emojis_array"></textarea></label><br>
      </div>
      <div id="step3" class="wizard-step">
        <h2>Step 3: Basic Graphics</h2>
        <label>Logo URL<br><input type="text" name="app_logo_url"></label><br>
        <label>Dark Logo URL<br><input type="text" name="app_logo_dark_url"></label><br>
        <label>Square Icon URL<br><input type="text" name="app_square_icon_url"></label><br>
        <label>Wordmark URL<br><input type="text" name="app_wordmark_url"></label><br>
        <label>Dark Wordmark URL<br><input type="text" name="app_wordmark_dark_url"></label><br>
      </div>
      <div id="step4" class="wizard-step">
        <h2>Step 4: Signup Graphics</h2>
        <label>Signup Top Image Light<br><input type="text" name="signup_top_img_url"></label><br>
        <label>Signup Top Image Dark<br><input type="text" name="signup_top_img_dark_url"></label><br>
        <label>Signup 1 Light<br><input type="text" name="signup_1_top_img_light"></label><br>
        <label>Signup 1 Dark<br><input type="text" name="signup_1_top_img_dark"></label><br>
        <label>Signup 2 Light<br><input type="text" name="signup_2_top_img_light"></label><br>
        <label>Signup 2 Dark<br><input type="text" name="signup_2_top_img_dark"></label><br>
        <label>Signup 3 Light<br><input type="text" name="signup_3_top_img_light"></label><br>
        <label>Signup 3 Dark<br><input type="text" name="signup_3_top_img_dark"></label><br>
        <label>Signup 4 Light<br><input type="text" name="signup_4_top_img_light"></label><br>
        <label>Signup 4 Dark<br><input type="text" name="signup_4_top_img_dark"></label><br>
        <label>Signup 5 Light<br><input type="text" name="signup_5_top_img_light"></label><br>
        <label>Signup 5 Dark<br><input type="text" name="signup_5_top_img_dark"></label><br>
        <label>Signup 6 Light<br><input type="text" name="signup_6_top_img_light"></label><br>
        <label>Signup 6 Dark<br><input type="text" name="signup_6_top_img_dark"></label><br>
        <label>Signup 7 Light<br><input type="text" name="signup_7_top_img_light"></label><br>
        <label>Signup 7 Dark<br><input type="text" name="signup_7_top_img_dark"></label><br>
        <label>Login Image Light<br><input type="text" name="login_top_img_light"></label><br>
        <label>Login Image Dark<br><input type="text" name="login_top_img_dark"></label><br>
      </div>
      <div id="step5" class="wizard-step">
        <h2>Step 5: Finish</h2>
        <p>Review your details and submit to create the app.</p>
      </div>
      <div class="wizard-buttons">
        <button type="button" id="prevBtn">Previous</button>
        <button type="button" id="nextBtn">Next</button>
        <button type="submit" id="submitBtn" style="display:none;">Submit</button>
      </div>
    </form>
  </div>
</div>
</div>
<script>
  const steps = document.querySelectorAll('.wizard-step');
  let currentStep = 0;
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');
  const submitBtn = document.getElementById('submitBtn');

  function showStep(index) {
    steps.forEach((step,i)=>{
      step.classList.toggle('active', i === index);
    });
    prevBtn.style.display = index === 0 ? 'none':'inline-block';
    nextBtn.style.display = index === steps.length -1 ? 'none':'inline-block';
    submitBtn.style.display = index === steps.length -1 ? 'inline-block':'none';
  }

  nextBtn.addEventListener('click', () => {
    if(currentStep < steps.length -1){
      currentStep++;
      showStep(currentStep);
    }
  });

  prevBtn.addEventListener('click', () => {
    if(currentStep > 0){
      currentStep--;
      showStep(currentStep);
    }
  });

  showStep(currentStep);
</script>
<?php require_once("../footer-2025.php"); ?>
</body>
</html>
