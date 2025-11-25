<div class="container py-5">
  <div class="justify-content-center align-items-center d-flex flex-column flex-md-column">
    <div class="col-md-6 welcome-section text-center">
      <h1 class="welcome-title">🌸 Input your OTP</h1>
      <p class="welcome-subtitle">Please enter the OTP sent to your email to continue.</p>
    </div>

    <div class="col-md-6">
      <div class="card p-4 mx-auto" style="max-width: 600px;">
        <h2 class="text-center mb-4 signup-title">🔑 Verify OTP</h2>

        <?php if (isset($error)): ?>
          <div class="alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
          <div class="alert-success text-center"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="/login/forgot-password/verify-otp">
          <label for ="otp" class="form-label text-center w-100 mb-3">Enter One-Time Password</label>
          <div id="otp-inputs" class="d-flex justify-content-between">
            <input type="text" class="form-control text-center" id="otp1" name="otp1" maxlength="1" inputmode="numeric" required>
            <input type="text" class="form-control text-center" id="otp2" name="otp2" maxlength="1" inputmode="numeric" required>
            <input type="text" class="form-control text-center" id="otp3" name="otp3" maxlength="1" inputmode="numeric" required>
            <input type="text" class="form-control text-center" id="otp4" name="otp4" maxlength="1" inputmode="numeric" required>
            <input type="text" class="form-control text-center" id="otp5" name="otp5" maxlength="1" inputmode="numeric" required>
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-4 mb-2">Verify OTP</button>
          <a href="/login" class="btn btn-secondary w-100">Back to Login</a>
        </form>

        <p class="mt-4 text-center">
          Didn’t receive the OTP? <a href="/login/forgot-password">Resend</a><br>
          Don’t have an account? <a href="/register">Register here</a>
        </p>
      </div>
    </div>
  </div>
</div>
