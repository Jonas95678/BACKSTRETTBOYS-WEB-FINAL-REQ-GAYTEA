<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user']      = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BSB Admin – Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    :root { --gold:#c9a84c; }
    body { background:linear-gradient(135deg,#0a0a0a,#1a1a2e,#0a0a0a); min-height:100vh;
           display:flex; align-items:center; justify-content:center; font-family:'Segoe UI',sans-serif; }
    .login-card { background:rgba(22,33,62,.95); border:1px solid rgba(201,168,76,.2);
                  border-radius:20px; padding:50px 44px; width:100%; max-width:420px;
                  box-shadow:0 30px 80px rgba(0,0,0,.6); }
    .brand { font-size:2.2rem; font-weight:900; letter-spacing:4px;
             background:linear-gradient(135deg,#c9a84c,#ffd700);
             -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
    .form-control { background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1);
                    color:#fff; border-radius:10px; padding:12px 16px; }
    .form-control:focus { background:rgba(255,255,255,.09); border-color:#c9a84c;
                          box-shadow:0 0 0 3px rgba(201,168,76,.15); color:#fff; }
    .form-control::placeholder { color:#666; }
    .btn-login { background:linear-gradient(135deg,#c9a84c,#ffd700); color:#000;
                 border:none; font-weight:700; padding:13px; border-radius:10px;
                 width:100%; font-size:1rem; letter-spacing:1px; transition:transform .2s,box-shadow .2s; }
    .btn-login:hover { transform:translateY(-1px); box-shadow:0 6px 24px rgba(201,168,76,.4); color:#000; }
    .form-label { color:#aaa; font-size:.85rem; letter-spacing:.5px; }
    .input-group-text { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1);
                        border-right:none; color:#666; border-radius:10px 0 0 10px; }
    .input-group .form-control { border-left:none; border-radius:0 10px 10px 0; }
  </style>
</head>
<body>
<div class="login-card text-center">
  <div class="brand mb-1">BSB</div>
  <p class="text-muted mb-4" style="font-size:.85rem;letter-spacing:2px;text-transform:uppercase;">Admin Dashboard</p>

  <?php if ($error): ?>
  <div class="alert alert-danger py-2 mb-3" style="border-radius:10px;font-size:.85rem;">
    <i class="fas fa-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
  </div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3 text-start">
      <label class="form-label">Username</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-user"></i></span>
        <input type="text" name="username" class="form-control" placeholder="admin" required>
      </div>
    </div>
    <div class="mb-4 text-start">
      <label class="form-label">Password</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-lock"></i></span>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>
    </div>
    <button type="submit" class="btn btn-login">
      <i class="fas fa-sign-in-alt me-2"></i>Sign In
    </button>
  </form>
  <p class="text-muted mt-4 mb-0" style="font-size:.75rem;">Default: admin / admin123</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
