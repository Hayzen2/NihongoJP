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

        <form method="POST" action="/login/forgot-password/reset?email=<?php echo urlencode($email); ?>">
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required placeholder="Enter your new password">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirm your new password">
            </div>
          <a href="/login" class="btn btn-secondary w-100">Back to Login</a>
        </form>

        <p class="mt-4 text-center">
          Don’t have an account? <a href="/register">Register here</a>
        </p>
      </div>
    </div>
  </div>
</div>
