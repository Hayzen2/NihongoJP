<div class="container py-5">
  <div class="justify-content-center align-items-center d-flex flex-column flex-md-column">
    <div class="col-md-6 welcome-section text-center">
      <h1 class="welcome-title">🌸 Forgot your password?</h1>
      <p class="welcome-subtitle">Don’t worry! We’ll help you reset it and get back to learning!</p>
    </div>
    <div class="col-md-6">
      <div class="card p-4 mx-auto" style="max-width: 600px;">
        <h2 class="text-center mb-4 signup-title">🔑 Forgot Password</h2>

        <?php if (isset($error)): ?>
          <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
          <div class="alert alert-success text-center"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="/login/forgot-password">
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your registered email">
          </div>

          <button type="submit" class="btn btn-primary w-100 mb-2">Send OTP</button>
          <a href="/login" class="btn btn-secondary w-100">Back to Login</a>
        </form>

        <p class="mt-4 text-center">
          Don’t have an account? <a href="/register">Register here</a>
        </p>
      </div>
    </div>
  </div>
</div>
