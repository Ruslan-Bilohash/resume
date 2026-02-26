<?php
// profile.php — ПОВНИЙ ФАЙЛ (цілий, без жодного обрізання, максимальна версія 2026)
// Сучасний преміум-дизайн, прогрес-бар, Live Preview, анімації, glass-ефекти, адаптивність 100%

require_once 'config.php';

if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$slug = $_SESSION['user_slug'];
$user = load_user($slug) ?? [
    'personal' => [
        'first_name' => '',
        'last_name'  => '',
        'birth'      => '',
        'location'   => '',
        'phone'      => '',
        'email'      => '',
        'civil_status'=> ''
    ],
    'photo' => '',
    'bio' => ['en'=>'','no'=>'','ua'=>''],
    'developer_description' => ['en'=>'','no'=>'','ua'=>''],
    'key_qualifications' => ['en'=>[],'no'=>[],'ua'=>[]],
    'work_experience' => ['en'=>[],'no'=>[],'ua'=>[]],
    'education' => ['en'=>[],'no'=>[],'ua'=>[]],
    'languages' => ['en'=>'','no'=>'','ua'=>''],
    'computer_skills' => ['en'=>'','no'=>'','ua'=>''],
    'certificates' => ['en'=>'','no'=>'','ua'=>''],
    'vocational_schools' => ['en'=>[],'no'=>[],'ua'=>[]],
    'references' => ['en'=>'','no'=>'','ua'=>''],
    'projects' => []
];

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Фото
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            $newname = $slug . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], UPLOADS_DIR . '/' . $newname);
            $user['photo'] = $newname;
        }
    }

    // Особисті дані
    $user['personal'] = [
        'first_name'  => trim($_POST['personal']['first_name'] ?? ''),
        'last_name'   => trim($_POST['personal']['last_name'] ?? ''),
        'birth'       => trim($_POST['personal']['birth'] ?? ''),
        'location'    => trim($_POST['personal']['location'] ?? ''),
        'phone'       => trim($_POST['personal']['phone'] ?? ''),
        'email'       => trim($_POST['personal']['email'] ?? ''),
        'civil_status'=> trim($_POST['personal']['civil_status'] ?? '')
    ];

    // Текстові поля (3 мови)
    foreach (['bio', 'developer_description', 'languages', 'computer_skills', 'certificates', 'references'] as $field) {
        foreach (['en','no','ua'] as $l) {
            $user[$field][$l] = trim($_POST[$field][$l] ?? '');
        }
    }

    // Списки (по одному пункту в рядок)
    foreach (['key_qualifications', 'work_experience', 'education', 'vocational_schools'] as $field) {
        foreach (['en','no','ua'] as $l) {
            $lines = explode("\n", trim($_POST[$field][$l] ?? ''));
            $user[$field][$l] = array_values(array_filter(array_map('trim', $lines)));
        }
    }

    // Проекти
    $user['projects'] = [];
    if (isset($_POST['project_name'])) {
        for ($i = 0; $i < count($_POST['project_name']); $i++) {
            if (!empty($_POST['project_name'][$i])) {
                $user['projects'][] = [
                    'name' => trim($_POST['project_name'][$i]),
                    'link' => trim($_POST['project_link'][$i] ?? ''),
                    'desc' => [
                        'en' => trim($_POST['project_desc_en'][$i] ?? ''),
                        'no' => trim($_POST['project_desc_no'][$i] ?? ''),
                        'ua' => trim($_POST['project_desc_ua'][$i] ?? '')
                    ]
                ];
            }
        }
    }

    save_user($slug, $user);
    $success = '✅ Усі зміни успішно збережено!';
}

$progress = calculate_progress($user);

include 'header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            <!-- Заголовок + Live Preview -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                <h1 class="display-5 fw-bold text-primary mb-0">
                    <i class="fas fa-user-edit"></i> Редагування резюме
                </h1>
                <a href="resume.php?user=<?= htmlspecialchars($slug) ?>" target="_blank" 
                   class="btn btn-outline-primary btn-lg mt-3 mt-md-0">
                    <i class="fas fa-eye"></i> Live Preview
                </a>
            </div>

            <!-- Прогрес-бар -->
            <div class="progress mb-2" style="height:12px;border-radius:9999px;overflow:hidden;">
                <div class="progress-bar bg-success progress-bar-striped" 
                     style="width:<?= $progress ?>%;"><?= $progress ?>% заповнено</div>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success text-center"><?= $success ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">

                <!-- Фото -->
                <div class="text-center mb-5">
                    <img id="photo-preview" 
                         src="<?= $user['photo'] ? 'uploads/' . htmlspecialchars($user['photo']) : 'https://via.placeholder.com/240/0066cc/ffffff?text=Фото' ?>" 
                         class="rounded-circle shadow-lg border border-5 border-primary" 
                         width="240" height="240" style="object-fit:cover;">
                    <br><br>
                    <input type="file" name="photo" accept="image/*" 
                           onchange="previewPhoto(this)" 
                           class="form-control w-auto mx-auto">
                </div>

                <!-- АКОРДЕОН (всі секції) -->
                <div class="accordion" id="profileAccordion">

                    <!-- 1. Особиста інформація -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonal">
                                <i class="fas fa-user me-3"></i> Особиста інформація
                            </button>
                        </h2>
                        <div id="collapsePersonal" class="accordion-collapse collapse show" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-md-6"><input type="text" name="personal[first_name]" class="form-control" value="<?= htmlspecialchars($user['personal']['first_name'] ?? '') ?>" placeholder="Ім'я" required></div>
                                    <div class="col-md-6"><input type="text" name="personal[last_name]" class="form-control" value="<?= htmlspecialchars($user['personal']['last_name'] ?? '') ?>" placeholder="Прізвище" required></div>
                                    <div class="col-md-6"><input type="text" name="personal[birth]" class="form-control" value="<?= htmlspecialchars($user['personal']['birth'] ?? '') ?>" placeholder="Дата народження (1993.12.04)"></div>
                                    <div class="col-md-6"><input type="text" name="personal[location]" class="form-control" value="<?= htmlspecialchars($user['personal']['location'] ?? '') ?>" placeholder="Місце проживання"></div>
                                    <div class="col-md-6"><input type="tel" name="personal[phone]" class="form-control" value="<?= htmlspecialchars($user['personal']['phone'] ?? '') ?>" placeholder="Телефон"></div>
                                    <div class="col-md-6"><input type="email" name="personal[email]" class="form-control" value="<?= htmlspecialchars($user['personal']['email'] ?? '') ?>" placeholder="Email"></div>
                                    <div class="col-12"><input type="text" name="personal[civil_status]" class="form-control" value="<?= htmlspecialchars($user['personal']['civil_status'] ?? '') ?>" placeholder="Сімейний стан"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Про мене -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAbout">
                                <i class="fas fa-info-circle me-3"></i> Про мене
                            </button>
                        </h2>
                        <div id="collapseAbout" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <div class="mb-4">
                                    <label class="form-label fw-bold"><i class="fas fa-comment-dots"></i> Коротка біографія</label>
                                    <?php foreach (['en','no','ua'] as $l): ?>
                                        <label class="form-label small text-muted"><?= strtoupper($l) ?></label>
                                        <textarea name="bio[<?= $l ?>]" class="form-control mb-3" rows="3"><?= htmlspecialchars($user['bio'][$l] ?? '') ?></textarea>
                                    <?php endforeach; ?>
                                </div>
                                <div>
                                    <label class="form-label fw-bold"><i class="fas fa-user"></i> Повний опис</label>
                                    <?php foreach (['en','no','ua'] as $l): ?>
                                        <label class="form-label small text-muted"><?= strtoupper($l) ?></label>
                                        <textarea name="developer_description[<?= $l ?>]" class="form-control mb-3" rows="8"><?= htmlspecialchars($user['developer_description'][$l] ?? '') ?></textarea>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Ключові кваліфікації -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKey">
                                <i class="fas fa-star me-3"></i> Ключові кваліфікації
                            </button>
                        </h2>
                        <div id="collapseKey" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <?php foreach (['en','no','ua'] as $l): ?>
                                    <label class="form-label fw-bold"><?= strtoupper($l) ?> (по 1 пункту в рядок)</label>
                                    <textarea name="key_qualifications[<?= $l ?>]" class="form-control mb-4" rows="6"><?= implode("\n", $user['key_qualifications'][$l] ?? []) ?></textarea>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Досвід роботи -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWork">
                                <i class="fas fa-briefcase me-3"></i> Досвід роботи
                            </button>
                        </h2>
                        <div id="collapseWork" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <?php foreach (['en','no','ua'] as $l): ?>
                                    <label class="form-label fw-bold"><?= strtoupper($l) ?> (по 1 пункту в рядок)</label>
                                    <textarea name="work_experience[<?= $l ?>]" class="form-control mb-4" rows="6"><?= implode("\n", $user['work_experience'][$l] ?? []) ?></textarea>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Освіта та курси -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEdu">
                                <i class="fas fa-graduation-cap me-3"></i> Освіта та курси
                            </button>
                        </h2>
                        <div id="collapseEdu" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <?php foreach (['en','no','ua'] as $l): ?>
                                    <label class="form-label fw-bold"><?= strtoupper($l) ?> (по 1 пункту в рядок)</label>
                                    <textarea name="education[<?= $l ?>]" class="form-control mb-4" rows="6"><?= implode("\n", $user['education'][$l] ?? []) ?></textarea>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Мови -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLang">
                                <i class="fas fa-language me-3"></i> Мови
                            </button>
                        </h2>
                        <div id="collapseLang" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <?php foreach (['en','no','ua'] as $l): ?>
                                    <label class="form-label fw-bold"><?= strtoupper($l) ?></label>
                                    <textarea name="languages[<?= $l ?>]" class="form-control mb-3" rows="3"><?= htmlspecialchars($user['languages'][$l] ?? '') ?></textarea>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 7. Навички роботи з комп'ютером -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSkills">
                                <i class="fas fa-laptop-code me-3"></i> Навички роботи з комп'ютером
                            </button>
                        </h2>
                        <div id="collapseSkills" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <?php foreach (['en','no','ua'] as $l): ?>
                                    <label class="form-label fw-bold"><?= strtoupper($l) ?></label>
                                    <textarea name="computer_skills[<?= $l ?>]" class="form-control mb-3" rows="4"><?= htmlspecialchars($user['computer_skills'][$l] ?? '') ?></textarea>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 8. Сертифікати -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCert">
                                <i class="fas fa-certificate me-3"></i> Сертифікати
                            </button>
                        </h2>
                        <div id="collapseCert" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <?php foreach (['en','no','ua'] as $l): ?>
                                    <label class="form-label fw-bold"><?= strtoupper($l) ?></label>
                                    <textarea name="certificates[<?= $l ?>]" class="form-control mb-3" rows="4"><?= htmlspecialchars($user['certificates'][$l] ?? '') ?></textarea>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 9. Професійні школи -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVoc">
                                <i class="fas fa-school me-3"></i> Професійні школи
                            </button>
                        </h2>
                        <div id="collapseVoc" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <?php foreach (['en','no','ua'] as $l): ?>
                                    <label class="form-label fw-bold"><?= strtoupper($l) ?> (по 1 пункту в рядок)</label>
                                    <textarea name="vocational_schools[<?= $l ?>]" class="form-control mb-4" rows="6"><?= implode("\n", $user['vocational_schools'][$l] ?? []) ?></textarea>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 10. Рекомендації -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRef">
                                <i class="fas fa-handshake me-3"></i> Рекомендації
                            </button>
                        </h2>
                        <div id="collapseRef" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <?php foreach (['en','no','ua'] as $l): ?>
                                    <label class="form-label fw-bold"><?= strtoupper($l) ?></label>
                                    <textarea name="references[<?= $l ?>]" class="form-control mb-3" rows="3"><?= htmlspecialchars($user['references'][$l] ?? '') ?></textarea>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 11. Проекти -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProjects">
                                <i class="fas fa-project-diagram me-3"></i> Проекти
                            </button>
                        </h2>
                        <div id="collapseProjects" class="accordion-collapse collapse" data-bs-parent="#profileAccordion">
                            <div class="accordion-body">
                                <button type="button" class="btn btn-primary btn-sm mb-3" onclick="addProjectRow()">
                                    <i class="fas fa-plus"></i> Додати проект
                                </button>
                                <div id="projects-container">
                                    <?php foreach ($user['projects'] as $p): ?>
                                    <div class="project-row border rounded p-3 mb-3">
                                        <div class="row g-2">
                                            <div class="col-12"><input type="text" name="project_name[]" class="form-control" value="<?= htmlspecialchars($p['name'] ?? '') ?>" placeholder="Назва проекту"></div>
                                            <div class="col-12"><input type="url" name="project_link[]" class="form-control" value="<?= htmlspecialchars($p['link'] ?? '') ?>" placeholder="Посилання (GitHub тощо)"></div>
                                            <div class="col-md-4"><input type="text" name="project_desc_en[]" class="form-control" value="<?= htmlspecialchars($p['desc']['en'] ?? '') ?>" placeholder="Опис EN"></div>
                                            <div class="col-md-4"><input type="text" name="project_desc_no[]" class="form-control" value="<?= htmlspecialchars($p['desc']['no'] ?? '') ?>" placeholder="Опис NO"></div>
                                            <div class="col-md-4"><input type="text" name="project_desc_ua[]" class="form-control" value="<?= htmlspecialchars($p['desc']['ua'] ?? '') ?>" placeholder="Опис UA"></div>
                                        </div>
                                        <button type="button" class="btn btn-danger btn-sm mt-3" onclick="this.parentElement.remove()">Видалити проект</button>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Кнопка збереження -->
                <button type="submit" class="btn btn-success btn-lg w-100 mt-5 py-3 shadow-lg">
                    <i class="fas fa-save"></i> Зберегти всі зміни
                </button>

            </form>
        </div>
    </div>
</div>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photo-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function addProjectRow() {
    const container = document.getElementById('projects-container');
    const row = document.createElement('div');
    row.className = 'project-row border rounded p-3 mb-3';
    row.innerHTML = `
        <div class="row g-2">
            <div class="col-12"><input type="text" name="project_name[]" class="form-control" placeholder="Назва проекту"></div>
            <div class="col-12"><input type="url" name="project_link[]" class="form-control" placeholder="Посилання (GitHub тощо)"></div>
            <div class="col-md-4"><input type="text" name="project_desc_en[]" class="form-control" placeholder="Опис EN"></div>
            <div class="col-md-4"><input type="text" name="project_desc_no[]" class="form-control" placeholder="Опис NO"></div>
            <div class="col-md-4"><input type="text" name="project_desc_ua[]" class="form-control" placeholder="Опис UA"></div>
        </div>
        <button type="button" class="btn btn-danger btn-sm mt-3" onclick="this.parentElement.remove()">Видалити проект</button>
    `;
    container.appendChild(row);
}
</script>

<?php include 'footer.php'; ?>