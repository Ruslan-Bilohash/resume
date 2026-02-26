<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $site_name ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root { --primary: #0056b3; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f8f9fa, #e9ecef); }
        .navbar { background: linear-gradient(135deg, #0056b3, #003d80); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .nav-link { transition: all 0.3s; }
        .nav-link:hover { transform: translateY(-2px); color: #ffd700 !important; }
        .card, .accordion-item { transition: all 0.4s; }
        .card:hover, .accordion-item:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important; }
        .glass { background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
        .animate-in { animation: fadeInUp 0.8s ease forwards; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="index.php"><i class="fas fa-file-alt"></i> ResumeBuilder</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (is_logged_in()): ?>
                    <li class="nav-item"><a class="nav-link px-3" href="profile.php"><i class="fas fa-user-edit"></i> <?= $translations['profile'][$lang] ?></a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="resume.php?user=<?= $_SESSION['user_slug'] ?>"><i class="fas fa-eye"></i> <?= $translations['my_resume'][$lang] ?></a></li>
                    <li class="nav-item"><a class="nav-link px-3 text-warning" href="logout.php"><i class="fas fa-sign-out-alt"></i> <?= $translations['logout'][$lang] ?></a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link px-3" href="login.php"><i class="fas fa-sign-in-alt"></i> <?= $translations['login'][$lang] ?></a></li>
                    <li class="nav-item"><a class="nav-link btn btn-light text-primary px-4 ms-3" href="register.php"><?= $translations['register'][$lang] ?></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>