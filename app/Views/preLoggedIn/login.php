<div class="container py-5">
  <div class="row justify-content-center align-items-center">

    <div class="col-md-6 order-2 order-md-1">
      <div class="card p-4 mx-auto" style="max-width: 600px;">
        <h2 class="text-center mb-4 signup-title">🌸 Login 🌸</h2>

        <?php if (isset($error)): ?>
          <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="/login/local">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>

          <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
          <a class="btn btn-secondary w-100 mb-3" href="/login/forgot-password">Forgot Password?</a>

          <div class="text-center mb-3">— or —</div>

          <!-- Google Sign-In -->
          <div id="g_id_onload"
              data-client_id="<?= ($_ENV['GOOGLE_CLIENT_ID']) ?>"
              data-callback="handleCredentialResponse"
              data-locale="en"
              data-auto_prompt="false"></div>

          <div class="d-flex justify-content-center align-items-center my-3">
            <div class="g_id_signin shadow-lg rounded-4"
                data-type="standard"
                data-shape="pill"
                data-theme="filled_black"
                data-text="sign_in_with"
                data-size="large"
                data-logo_alignment="left"
                data-width="300"
                data-locale="en"></div>
          </div>
        </form>

        <p class="mt-4 text-center">
          Don't have an account? <a href="/register">Register here</a>
        </p>
      </div>
    </div>

    <!-- Welcome section (right) -->
    <div class="col-md-5 welcome-section d-none d-md-block order-1 order-md-2">
      <h1 class="welcome-title">🌸 Welcome back to <span>NihongoJP!</span></h1>
      <p class="welcome-subtitle">Continue your Japanese learning journey</p>
    </div>

  </div>
</div>