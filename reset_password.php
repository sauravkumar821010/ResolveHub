<?php

session_start();

require_once __DIR__ . '/db.php';

$pdo = db();

$token = trim($_GET['token'] ?? '');

$error = '';
$message = '';

$userId = null;
$resetId = null;

/*
 * Step 1:
 * Check whether a token was provided.
 */
if ($token === '') {

    $error = 'Invalid or missing password reset link.';

} else {

    /*
     * Hash the token received from the URL.
     * The database stores only the hash.
     */
    $tokenHash = hash('sha256', $token);

    /*
     * Find a valid, unused and non-expired reset token.
     */
    $stmt = $pdo->prepare(
        "SELECT id, user_id
         FROM password_resets
         WHERE token_hash = ?
           AND expires_at > NOW()
           AND used_at IS NULL
         LIMIT 1"
    );

    $stmt->execute([$tokenHash]);

    $reset = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reset) {

        $userId = $reset['user_id'];
        $resetId = $reset['id'];

    } else {

        $error =
            'This password reset link is invalid, expired, or has already been used.';
    }
}


/*
 * Step 2:
 * Handle the new password submission.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId !== null) {

    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    /*
     * Validate password.
     */
    if ($password === '' || $confirmPassword === '') {

        $error = 'Please enter your new password.';

    } elseif (strlen($password) < 8) {

        $error = 'Password must be at least 8 characters long.';

    } elseif ($password !== $confirmPassword) {

        $error = 'Passwords do not match.';

    } else {

        /*
         * Hash the new password securely.
         */
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        /*
         * Update user's password.
         */
        $updateUser = $pdo->prepare(
            "UPDATE users
             SET password = ?
             WHERE id = ?"
        );

        $updateUser->execute([
            $hashedPassword,
            $userId
        ]);

        /*
         * Mark this reset token as used.
         */
        $updateToken = $pdo->prepare(
            "UPDATE password_resets
             SET used_at = NOW()
             WHERE id = ?"
        );

        $updateToken->execute([
            $resetId
        ]);

        /*
         * Password changed successfully.
         */
        $message =
            'Your password has been reset successfully.';

        /*
         * Prevent the same token from being submitted again.
         */
        $userId = null;
        $resetId = null;
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

    <title>Reset Password | ResolveHub</title>

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

            line-height: 1.6;
        }

        label {

            display: block;

            margin-top: 25px;

            margin-bottom: 8px;

            font-weight: 600;
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
                box-shadow 0.2s;
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
        }

        button {

            width: 100%;

            margin-top: 25px;

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
                box-shadow 0.2s;
        }

        button:hover {

            transform: translateY(-2px);

            box-shadow:
                0 10px 25px
                rgba(108, 120, 255, 0.28);
        }

        .message {

            margin-top: 20px;

            padding: 15px;

            border-radius: 12px;

            background: rgba(16, 45, 41, 0.85);

            border: 1px solid
                rgba(126, 231, 207, 0.2);

            color: #7ee7cf;

            line-height: 1.5;
        }

        .error {

            margin-top: 20px;

            padding: 15px;

            border-radius: 12px;

            background: rgba(53, 27, 34, 0.85);

            border: 1px solid
                rgba(255, 155, 155, 0.2);

            color: #ff9b9b;

            line-height: 1.5;
        }

        .back {

            display: block;

            margin-top: 25px;

            text-align: center;

            color: #9da7ff;

            text-decoration: none;
        }

        .back:hover {

            text-decoration: underline;
        }

    </style>

</head>

<body>

<div class="card">

    <div class="logo">R</div>

    <?php if ($message): ?>

        <h1>Password Updated!</h1>

        <p>
            Your ResolveHub password has been changed successfully.
            You can now log in using your new password.
        </p>

        <div class="message">
            Password reset completed successfully.
        </div>

        <a
            class="back"
            href="login.php"
        >
            ← Go to Login
        </a>

    <?php elseif ($error): ?>

        <h1>Reset Password</h1>

        <p>
            We couldn't continue with this password reset.
        </p>

        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>

        <a
            class="back"
            href="forgot_password.php"
        >
            ← Request a New Reset Link
        </a>

    <?php else: ?>

        <h1>Reset Password</h1>

        <p>
            Create a new password for your ResolveHub account.
        </p>

        <form method="POST">

            <label for="password">
                New Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter new password"
                minlength="8"
                required
            >

            <label for="confirm_password">
                Confirm New Password
            </label>

            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="Confirm your new password"
                minlength="8"
                required
            >

            <button type="submit">
                Reset Password
            </button>

        </form>

        <a
            class="back"
            href="login.php"
        >
            ← Back to Login
        </a>

    <?php endif; ?>

</div>

</body>

</html>