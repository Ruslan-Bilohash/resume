<?php
require_once 'config.php';
if ($_POST) {
    $first = trim($_POST['first_name']);
    $last = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $slug = slugify($first . '_' . $last);

    $data = [
        'auth' => ['email' => $email, 'password' => $pass],
        'personal' => ['first_name' => $first, 'last_name' => $last, 'phone' => $phone, 'email' => $email, 'birth'=>'','location'=>'','civil_status'=>''],
        'photo' => '',
        'bio' => ['en'=>'', 'no'=>'', 'ua'=>''],
        'developer_description' => ['en'=>'', 'no'=>'', 'ua'=>''],
        'key_qualifications' => ['en'=>[], 'no'=>[], 'ua'=>[]],
        'work_experience' => ['en'=>[], 'no'=>[], 'ua'=>[]],
        'education' => ['en'=>[], 'no'=>[], 'ua'=>[]],
        'languages' => ['en'=>'', 'no'=>'', 'ua'=>''],
        'computer_skills' => ['en'=>'', 'no'=>'', 'ua'=>''],
        'certificates' => ['en'=>'', 'no'=>'', 'ua'=>''],
        'vocational_schools' => ['en'=>[], 'no'=>[], 'ua'=>[]],
        'references' => ['en'=>'', 'no'=>'', 'ua'=>''],
        'projects' => [],
        'created' => date('Y-m-d')
    ];

    save_user($slug, $data);
    $_SESSION['user_slug'] = $slug;
    send_welcome_email($email, $first . ' ' . $last, $slug);

    header("Location: profile.php");
    exit;
}
include 'header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card glass shadow-xl border-0 animate-in">
                <div class="card-body p-5">
                    <h3 class="card-title text-center mb-4"><?= $translations['reg_title'][$lang] ?></h3>
                    <form method="post">
                        <div class="mb-3"><input type="text" name="first_name" class="form-control form-control-lg" placeholder="<?= $translations['first_name'][$lang] ?>" required></div>
                        <div class="mb-3"><input type="text" name="last_name" class="form-control form-control-lg" placeholder="<?= $translations['last_name'][$lang] ?>" required></div>
                        <div class="mb-3"><input type="email" name="email" class="form-control form-control-lg" placeholder="<?= $translations['email'][$lang] ?>" required></div>
                        <div class="mb-3"><input type="tel" name="phone" class="form-control form-control-lg" placeholder="<?= $translations['phone'][$lang] ?>" required></div>
                        <div class="mb-3"><input type="password" name="password" class="form-control form-control-lg" placeholder="<?= $translations['password'][$lang] ?>" required></div>
                        <button type="submit" class="btn btn-primary btn-lg w-100"><?= $translations['register_btn'][$lang] ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>