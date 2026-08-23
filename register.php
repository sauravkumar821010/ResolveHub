<?php
require 'auth.php';
if (current_user()) redirect('dashboard.php');
$errors=[];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $phone=trim($_POST['phone']??''); $password=$_POST['password']??''; $confirm=$_POST['confirm']??'';
    if ($name==='') $errors[]='Name is required.';
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Enter a valid email.';
    if (strlen($password)<6) $errors[]='Password must be at least 6 characters.';
    if ($password!==$confirm) $errors[]='Passwords do not match.';
    if (!$errors) {
        $stmt=db()->prepare("SELECT id FROM users WHERE email=?"); $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[]='An account with this email already exists.';
        else {
            $stmt=db()->prepare("INSERT INTO users(name,email,phone,password,role) VALUES(?,?,?,?, 'citizen')");
            $stmt->execute([$name,$email,$phone,password_hash($password,PASSWORD_DEFAULT)]);
            flash('success','Account created successfully. Please log in.');
            redirect('login.php');
        }
    }
}
$page_title='Create Account'; require 'partials/header.php';
?>
<div class="auth-wrap"><div class="auth panel">
<h1>Create your account</h1><p>Join ResolveHub and keep your complaints organized.</p>
<?php foreach($errors as $err): ?><div class="alert error"><?=e($err)?></div><?php endforeach; ?>
<form method="post">
<div class="form-group"><label>Full Name</label><input class="input" name="name" required value="<?=e($_POST['name']??'')?>"></div>
<div class="form-group"><label>Email</label><input class="input" type="email" name="email" required value="<?=e($_POST['email']??'')?>"></div>
<div class="form-group"><label>Phone (optional)</label><input class="input" name="phone" value="<?=e($_POST['phone']??'')?>"></div>
<div class="form-group"><label>Password</label><input class="input" type="password" name="password" required></div>
<div class="form-group"><label>Confirm Password</label><input class="input" type="password" name="confirm" required></div>
<button class="btn btn-primary" style="width:100%">Create Account</button>
</form><p style="margin-top:18px">Already registered? <a href="login.php" style="color:#aeb6ff">Login</a></p>
</div></div>
<?php require 'partials/footer.php'; ?>
