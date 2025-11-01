<div class="container py-5">
  <div class="justify-content-center align-items-center d-flex flex-column flex-md-column">
    <div class="col-md-6 welcome-section text-center">
      <h1 class="welcome-title">🌸 Reset Password</h1>
      <p class="welcome-subtitle">Now reset your password to get back to learning!</p>
    </div>
    <div class="col-md-6">
      <div class="card p-4 mx-auto" style="max-width: 600px;">
        <h2 class="text-center mb-4 signup-title">🔑 Reset Password</h2>

        <?php if (isset($error)): ?>
          <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
          <div class="alert alert-success text-center"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="/login/forgot-password/reset-password">
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
            <button type="submit" class="btn btn-primary w-100 mb-2">Reset Password</button>
            <a href="/login" class="btn btn-secondary w-100">Back to Login</a>
        </form>

        <p class="mt-4 text-center">
          Don’t have an account? <a href="/register">Register here</a>
        </p>
      </div>
    </div>
  </div>
</div>
