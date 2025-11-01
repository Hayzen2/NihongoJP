<div class="container py-5">
    <div class="row justify-content-center align-items-center">
      
      <div class="col-md-5 welcome-section d-none d-md-block">
        <h1 class="welcome-title">🌸 Welcome to <span>NihongoJP!</span></h1>
        <p class="welcome-subtitle">Learn Japanese with joy and beauty</p>
      </div>
      
      <div class="col-md-6">
        <div class="card p-4 mx-auto" style="max-width: 600px;">
          <h2 class="text-center mb-4 signup-title">🌸 Sign Up 🌸</h2>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>

          <form method="POST" action="/register/local">
            <div class="mb-3">
              <label for="username" class="form-label">Username<span class="required">*</span></label>
              <input type="text" class="form-control" id="username" name="username" required>
              <div class="is-invalid-exists mt-2">This username already exists.</div>
            </div>

            <div class="mb-3">
              <label for="name" class="form-label">Full Name<span class="required">*</span></label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email<span class="required">*</span></label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password<span class="required">*</span></label>
              <input type="password" class="form-control" id="password" name="password" required>
              <ul class="password-requirements mt-2">
                <li>Password must have at least 8 characters</li>
                <li>Password must include at least 1 number</li>
                <li>Password must include at least 1 uppercase letter</li>
                <li>Password must include at least 1 lowercase letter</li>
                <li>Password must include at least 1 special character</li>
              </ul>
            </div>

            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirm Password<span class="required">*</span></label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
              <div class="is-invalid-not-match">Passwords do not match.</div>
            </div>

            <div class="mb-3">
                <div class="d-flex location-label">Location<span class="required">*</span></div>
                <div class="location-row">
                    <label for="country" class="form-label d-none">Country</label>
                    <select class="form-select" id="country" name="country" required>
                    <option>Select Country</option>
                    </select>
                    <label for="province" class="form-label d-none">Province</label>
                    <select class="form-select" id="province" name="province" required>
                    <option>Select Province</option>
                    </select>
                    <label for="city" class="form-label d-none">City</label>
                    <select class="form-select" id="city" name="city" required>
                    <option>Select City</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-2">Sign Up</button>
            <div class="text-center mb-2">— or —</div>
            <div id="g_id_onload"
              data-client_id="<?= ($_ENV['GOOGLE_CLIENT_ID']) ?>"
              data-locale="en"
              data-callback="handleCredentialResponse"
              data-auto_prompt="false">
            </div>
            <div class="d-flex justify-content-center align-items-center my-4">
              <div 
                class="g_id_signin shadow-lg rounded-4"
                data-type="standard"
                data-shape="pill"
                data-theme="filled_black"
                data-text="sign_in_with"
                data-size="large"
                data-logo_alignment="left"
                data-width="300"
                data-locale="en">
              </div>
            </div>
            <a class="btn btn-secondary w-100" href="/login">Already have an account? Login</a>
          </form>
        </div>
      </div>
    </div>
  </div>