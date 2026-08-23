<?php
require 'auth.php';
if (current_user()) redirect('dashboard.php');

$errors = [];
$messages = flashes();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if ($u && password_verify($password, $u['password'])) {
        $_SESSION['user'] = [
            'id' => $u['id'],
            'name' => $u['name'],
            'email' => $u['email'],
            'role' => $u['role']
        ];
        redirect('dashboard.php');
    }

    $errors[] = 'Invalid email or password.';
}

$page_title = 'Login';
require 'partials/header.php';
?>

<style>
/* ResolveHub Login Page Enhancement */
.rh-login-page {
    min-height: calc(100vh - 80px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 50px 24px;
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(circle at 15% 20%, rgba(112, 92, 255, .18), transparent 32%),
        radial-gradient(circle at 88% 78%, rgba(67, 150, 255, .14), transparent 30%),
        #061222;
}

.rh-login-page::before,
.rh-login-page::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    filter: blur(2px);
    pointer-events: none;
}

.rh-login-page::before {
    width: 420px;
    height: 420px;
    top: -190px;
    right: -120px;
    background: rgba(116, 91, 255, .13);
}

.rh-login-page::after {
    width: 360px;
    height: 360px;
    bottom: -180px;
    left: -120px;
    background: rgba(36, 125, 255, .10);
}

.rh-login-shell {
    width: min(1050px, 100%);
    min-height: 610px;
    display: grid;
    grid-template-columns: 1fr 1.05fr;
    position: relative;
    z-index: 1;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 28px;
    background: rgba(10, 27, 46, .82);
    box-shadow: 0 30px 80px rgba(0,0,0,.35);
    backdrop-filter: blur(18px);
}

.rh-login-brand {
    padding: 58px 52px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    background:
        linear-gradient(145deg, rgba(112,92,255,.22), rgba(27,83,156,.12)),
        rgba(13, 31, 54, .82);
    border-right: 1px solid rgba(255,255,255,.08);
}

.rh-login-brand::after {
    content: "";
    position: absolute;
    width: 280px;
    height: 280px;
    right: -100px;
    bottom: -100px;
    border: 1px solid rgba(139,124,255,.25);
    border-radius: 50%;
    box-shadow:
        0 0 0 35px rgba(139,124,255,.04),
        0 0 0 70px rgba(139,124,255,.025);
}

.rh-logo {
    display: flex;
    align-items: center;
    gap: 13px;
    font-size: 25px;
    font-weight: 800;
    color: #f7f8ff;
}

.rh-logo-mark {
    width: 46px;
    height: 46px;
    display: grid;
    place-items: center;
    border-radius: 13px;
    color: white;
    font-size: 23px;
    font-weight: 800;
    background: linear-gradient(135deg, #8b7cff, #6257e9);
    box-shadow: 0 10px 25px rgba(101,88,238,.32);
}

.rh-brand-copy {
    position: relative;
    z-index: 1;
}

.rh-brand-copy .eyebrow {
    display: inline-block;
    margin-bottom: 20px;
    padding: 7px 12px;
    border: 1px solid rgba(139,124,255,.35);
    border-radius: 999px;
    color: #b9b2ff;
    background: rgba(139,124,255,.08);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.rh-brand-copy h2 {
    margin: 0 0 18px;
    max-width: 430px;
    color: #f5f7ff;
    font-size: clamp(34px, 4vw, 48px);
    line-height: 1.08;
    letter-spacing: -.035em;
}

.rh-brand-copy h2 span {
    color: #a9a2ff;
}

.rh-brand-copy p {
    max-width: 430px;
    margin: 0;
    color: #aebed3;
    font-size: 16px;
    line-height: 1.75;
}

.rh-features {
    display: grid;
    gap: 12px;
    margin-top: 34px;
    position: relative;
    z-index: 1;
}

.rh-feature {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #d8e2f0;
    font-size: 14px;
}

.rh-feature-icon {
    width: 34px;
    height: 34px;
    display: grid;
    place-items: center;
    border-radius: 10px;
    color: #bdb6ff;
    background: rgba(139,124,255,.12);
    border: 1px solid rgba(139,124,255,.18);
}

.rh-login-form {
    padding: 58px 58px;
    display: flex;
    align-items: center;
    background: rgba(7, 20, 35, .72);
}

.rh-form-inner {
    width: 100%;
    max-width: 450px;
    margin: 0 auto;
}

.rh-form-heading h1 {
    margin: 0 0 9px;
    color: #f5f7ff;
    font-size: 38px;
    letter-spacing: -.025em;
}

.rh-form-heading p {
    margin: 0 0 34px;
    color: #91a6bf;
    font-size: 15px;
}

.rh-alert {
    margin-bottom: 18px;
    padding: 12px 14px;
    border-radius: 12px;
    font-size: 14px;
}

.rh-alert.error {
    color: #ffc5ce;
    border: 1px solid rgba(255,100,120,.22);
    background: rgba(255,70,95,.08);
}

.rh-alert.success {
    color: #baf4df;
    border: 1px solid rgba(46,211,153,.22);
    background: rgba(46,211,153,.08);
}

.rh-field {
    margin-bottom: 20px;
}

.rh-field label {
    display: block;
    margin-bottom: 9px;
    color: #dce5f2;
    font-size: 13px;
    font-weight: 700;
}

.rh-input-wrap {
    position: relative;
}

.rh-input {
    width: 100%;
    box-sizing: border-box;
    height: 54px;
    padding: 0 48px 0 16px;
    border: 1px solid rgba(145,166,191,.18);
    border-radius: 13px;
    outline: none;
    color: #f4f7ff;
    background: rgba(4, 16, 29, .86);
    font-size: 15px;
    transition: .2s ease;
}

.rh-input::placeholder {
    color: #657b94;
}

.rh-input:focus {
    border-color: #8175ff;
    box-shadow: 0 0 0 4px rgba(129,117,255,.11);
    background: rgba(5, 19, 34, .98);
}

.rh-password-toggle {
    position: absolute;
    right: 13px;
    top: 50%;
    transform: translateY(-50%);
    border: 0;
    padding: 5px;
    cursor: pointer;
    color: #8195ad;
    background: transparent;
    font-size: 15px;
}

.rh-password-toggle:hover {
    color: #bcb5ff;
}

.rh-form-options {
    display: flex;
    justify-content: flex-end;
    margin: -4px 0 22px;
}

.rh-forgot {
    color: #a9a2ff;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
}

.rh-forgot:hover {
    color: #c5c0ff;
    text-decoration: underline;
}

.rh-login-btn {
    width: 100%;
    height: 55px;
    border: 0;
    border-radius: 13px;
    cursor: pointer;
    color: white;
    font-size: 15px;
    font-weight: 800;
    background: linear-gradient(135deg, #7c70ff, #6257e9);
    box-shadow: 0 12px 28px rgba(99,86,233,.24);
    transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
}

.rh-login-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.06);
    box-shadow: 0 16px 34px rgba(99,86,233,.34);
}

.rh-login-btn:active {
    transform: translateY(0);
}

.rh-register {
    margin: 24px 0 0;
    text-align: center;
    color: #8398b2;
    font-size: 14px;
}

.rh-register a {
    color: #aaa4ff;
    font-weight: 700;
    text-decoration: none;
}

.rh-register a:hover {
    text-decoration: underline;
}

@media (max-width: 850px) {
    .rh-login-shell {
        grid-template-columns: 1fr;
        max-width: 580px;
    }

    .rh-login-brand {
        display: none;
    }

    .rh-login-form {
        padding: 48px 32px;
    }
}

@media (max-width: 480px) {
    .rh-login-page {
        padding: 24px 14px;
    }

    .rh-login-shell {
        border-radius: 20px;
    }

    .rh-login-form {
        padding: 38px 22px;
    }

    .rh-form-heading h1 {
        font-size: 32px;
    }
}
</style>

<div class="rh-login-page">
    <div class="rh-login-shell">

        <section class="rh-login-brand">
            <div class="rh-logo">
                <div class="rh-logo-mark">R</div>
                <span>ResolveHub</span>
            </div>

            <div class="rh-brand-copy">
                <span class="eyebrow">Complaint Management Platform</span>
                <h2>Turn every complaint into a <span>clear resolution.</span></h2>
                <p>
                    Submit complaints, track progress and stay informed
                    through one simple and transparent platform.
                </p>

                <div class="rh-features">
                    <div class="rh-feature">
                        <span class="rh-feature-icon">✓</span>
                        <span>Easy complaint tracking</span>
                    </div>
                    <div class="rh-feature">
                        <span class="rh-feature-icon">↗</span>
                        <span>Real-time status updates</span>
                    </div>
                    <div class="rh-feature">
                        <span class="rh-feature-icon">⌁</span>
                        <span>Secure account access</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="rh-login-form">
            <div class="rh-form-inner">

                <div class="rh-form-heading">
                    <h1>Welcome back</h1>
                    <p>Sign in to continue to your ResolveHub account.</p>
                </div>

                <?php foreach ($messages as $m): ?>
                    <div class="rh-alert <?= e($m['type']) ?>">
                        <?= e($m['message']) ?>
                    </div>
                <?php endforeach; ?>

                <?php foreach ($errors as $err): ?>
                    <div class="rh-alert error">
                        <?= e($err) ?>
                    </div>
                <?php endforeach; ?>

                <form method="post">
                    <div class="rh-field">
                        <label for="rh-email">Email address</label>
                        <input
                            id="rh-email"
                            class="rh-input"
                            type="email"
                            name="email"
                            placeholder="you@example.com"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div class="rh-field">
                        <label for="rh-password">Password</label>
                        <div class="rh-input-wrap">
                            <input
                                id="rh-password"
                                class="rh-input"
                                type="password"
                                name="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >
                            <button
                                type="button"
                                class="rh-password-toggle"
                                id="rh-toggle-password"
                                aria-label="Show password"
                            >◉</button>
                        </div>
                    </div>

                    <div class="rh-form-options">
                        <a class="rh-forgot"
                        href="forgot_password.php"
                        title="Reset your password">
                        Forgot password?
                        </a>
                    </div>

                    <button class="rh-login-btn" type="submit">
                        Sign in to ResolveHub →
                    </button>
                </form>

                <p class="rh-register">
                    New to ResolveHub?
                    <a href="register.php">Create an account</a>
                </p>

            </div>
        </section>

    </div>
</div>

<script>
(function () {
    const password = document.getElementById('rh-password');
    const toggle = document.getElementById('rh-toggle-password');

    if (!password || !toggle) return;

    toggle.addEventListener('click', function () {
        const isPassword = password.type === 'password';
        password.type = isPassword ? 'text' : 'password';
        toggle.textContent = isPassword ? '◉' : '◉';
        toggle.setAttribute(
            'aria-label',
            isPassword ? 'Hide password' : 'Show password'
        );
    });
})();
</script>

<?php require 'partials/footer.php'; ?>
