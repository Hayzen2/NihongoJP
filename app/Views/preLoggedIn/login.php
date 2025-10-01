<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>JapaneseLearning</title>
        <link rel="stylesheet" href="../../public/css/styles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="col-md-6 d-none d-md-flex bg-light justify-content-center align-items-center">
            <h1 class="text-muted">Welcome to  NihongoJP!</h1>
        </div>

        <div class="container col-md-6 d-flex justify-content-center align-items-center">
            <div>
                <h2 class="text-center">Login</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
                <div class="card p-4" style="width: 300px;">
                    <div class="mo>
                    <div class="mb-3">
                        <form method="POST" action="/login">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                            <a class="btn btn-secondary" href="/forgot-password">Forget Password?</a>
                        </form>
                        <p class="mt-3">Don't have an account? <a href="/register">Register here</a></p>
                        <p class="mt-3"> Sign in with Google <a href="/auth/google"><img src="../../public/images/google_signin.png" alt="Google Sign-In" style="width: 150px;"></a></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
