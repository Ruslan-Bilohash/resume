<?php
// login.php — повний файл
require_once 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $files = glob(USERS_DIR . '/*.json');
    foreach ($files as $file) {
        $data = json_decode(file_get_contents($file), true);
        if (isset($data['auth']['email']) && $data['auth']['email'] === $email &&
            password_verify($password, $data['auth']['password'] ?? '')) {
            
            $slug = basename($file, '.json');
            $_SESSION['user_slug'] = $slug;
            $_SESSION['user_name'] = ($data['personal']['first_name'] ?? '') . ' ' . ($data['personal']['last_name'] ?? '');
            
            header('Location: profile.php');
            exit;
        }
    }
    $error = 'Невірний email або пароль';
}

include 'header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4"><?= $translations['login_title'][$lang] ?? 'Увійти в резюме' ?></h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-4">
                            <label class="form-label"><?= $translations['email'][$lang] ?? 'Email' ?></label>
                            <input type="email" name="email" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label"><?= $translations['password'][$lang] ?? 'Пароль' ?></label>
                            <input type="password" name="password" class="form-control form-control-lg" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100"><?= $translations['login'][$lang] ?? 'Увійти' ?></button>
                    </form>
                    <div class="text-center mt-4">
                        <a href="register.php" class="text-decoration-none"><?= $translations['register'][$lang] ?? 'Створити новий акаунт' ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>