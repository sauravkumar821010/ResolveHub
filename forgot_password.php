<?php

session_start();

require_once __DIR__ . '/db.php';

$pdo = db();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    // Validate email
    if ($email === '') {

        $error = 'Please enter your email address.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = 'Please enter a valid email address.';

    } else {

        // Find the user
        $stmt = $pdo->prepare(
            "SELECT id, name FROM users WHERE email = ? LIMIT 1"
        );

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            // Generate a secure random token
            $token = bin2hex(random_bytes(32));

            // Store only the SHA-256 hash in the database
            $tokenHash = hash('sha256', $token);

            // Delete any previous reset tokens for this user
            $delete = $pdo->prepare(
                "DELETE FROM password_resets WHERE user_id = ?"
            );

            $delete->execute([
                $user['id']
            ]);

            // Store the new reset token
            // Token will expire after 30 minutes
            $insert = $pdo->prepare(
                "INSERT INTO password_resets
                (user_id, token_hash, expires_at)
                VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))"
            );

            $insert->execute([
                $user['id'],
                $tokenHash
            ]);

            // Create reset link for local development
           $resetLink =
    'https://resolvehub.great-site.net/reset_password.php?token='
    . urlencode($token);

            // Display reset link on screen
            $message =
                "Password reset link generated successfully.<br><br>"
                . "<a href=\"" . htmlspecialchars($resetLink) . "\">"
                . "Click here to reset your password"
                . "</a>";

        } else {

            // Do not reveal whether the email exists
            $message =
                "If an account exists with this email, "
                . "a password reset link has been generated.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Forgot Password | ResolveHub</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            font-family: Arial, sans-serif;

            background:
                radial-gradient(
                    circle at top right,
                    rgba(108, 120, 255, 0.12),
                    transparent 35%
                ),
                #071426;

            color: #ffffff;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;
        }

        .card {

            width: 420px;
            max-width: 100%;

            padding: 40px;

            border-radius: 22px;

            background: rgba(13, 29, 50, 0.96);

            border: 1px solid #243650;

            box-shadow:
                0 25px 70px rgba(0, 0, 0, 0.45);

            animation: cardIn 0.5s ease;
        }

        @keyframes cardIn {

            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {

            width: 60px;
            height: 60px;

            border-radius: 16px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #7c83ff,
                    #626ff5
                );

            font-size: 28px;
            font-weight: bold;

            margin-bottom: 22px;

            box-shadow:
                0 10px 30px
                rgba(108, 120, 255, 0.3);
        }

        h1 {

            margin: 0 0 12px;

            font-size: 30px;

            letter-spacing: -0.5px;
        }

        p {

            margin: 0;

            color: #9eafc7;

            line-height: 1.65;

            font-size: 15px;
        }

        label {

            display: block;

            margin-top: 28px;
            margin-bottom: 9px;

            font-weight: 600;

            color: #e9effa;
        }

        input {

            width: 100%;

            padding: 15px 16px;

            border-radius: 12px;

            border: 1px solid #2a3d58;

            background: #071426;

            color: white;

            font-size: 15px;

            transition:
                border-color 0.2s,
                box-shadow 0.2s,
                transform 0.2s;
        }

        input::placeholder {
            color: #65758d;
        }

        input:focus {

            outline: none;

            border-color: #6c78ff;

            box-shadow:
                0 0 0 4px
                rgba(108, 120, 255, 0.12);

            transform: translateY(-1px);
        }

        button {

            width: 100%;

            margin-top: 22px;

            padding: 15px;

            border: none;

            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    #747dff,
                    #626ff5
                );

            color: white;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

            transition:
                transform 0.2s,
                box-shadow 0.2s,
                filter 0.2s;
        }

        button:hover {

            transform: translateY(-2px);

            box-shadow:
                0 10px 25px
                rgba(108, 120, 255, 0.28);

            filter: brightness(1.05);
        }

        button:active {
            transform: translateY(0);
        }

        .message {

            margin-top: 20px;

            padding: 14px 16px;

            border-radius: 12px;

            background:
                rgba(16, 45, 41, 0.85);

            border:
                1px solid
                rgba(126, 231, 207, 0.2);

            color: #7ee7cf;

            line-height: 1.5;
        }

        .message a {

            color: #9da7ff;

            font-weight: 600;

            text-decoration: none;
        }

        .message a:hover {
            text-decoration: underline;
        }

        .error {

            margin-top: 20px;

            padding: 14px 16px;

            border-radius: 12px;

            background:
                rgba(53, 27, 34, 0.85);

            border:
                1px solid
                rgba(255, 155, 155, 0.2);

            color: #ff9b9b;
        }

        .back {

            display: block;

            margin-top: 25px;

            text-align: center;

            color: #9da7ff;

            text-decoration: none;

            font-size: 15px;
        }

        .back:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>

<div class="card">

    <div class="logo">R</div>

    <h1>Forgot Password?</h1>

    <p>

        Enter the email address associated with your
        ResolveHub account and we'll help you reset
        your password.

    </p>

    <form method="POST">

        <label for="email">
            Email Address
        </label>

        <input
            type="email"
            id="email"
            name="email"
            placeholder="you@example.com"
            required
        >

        <button type="submit">
            Generate Reset Link
        </button>

    </form>

    <?php if ($message): ?>

        <div class="message">

            <?= $message ?>

        </div>

    <?php endif; ?>

    <?php if ($error): ?>

        <div class="error">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>

    <a
        class="back"
        href="login.php"
    >
        ← Back to Login
    </a>

</div>

</body>

</html>