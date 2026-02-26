<?php
// resume.php — ПОВНИЙ ФАЙЛ (виправлений + модальне вікно + відправка на будь-яку пошту)
require_once 'config.php';

$slug = $_GET['user'] ?? '';
$user = load_user($slug);
if (!$user) {
    die('<h1 class="text-center mt-5 text-danger">Резюме не знайдено</h1>');
}

$lang = $_GET['lang'] ?? 'en';
if (!in_array($lang, ['en','no','ua'])) $lang = 'en';

$name = trim(($user['personal']['first_name'] ?? '') . ' ' . ($user['personal']['last_name'] ?? ''));
$photo = !empty($user['photo']) ? 'uploads/' . htmlspecialchars($user['photo']) : 'https://via.placeholder.com/180/0066cc/fff?text=Фото';

include 'header.php';
?>
<div class="resume-container container my-5 p-5 bg-white shadow rounded-4" id="pdf-content">

    <!-- Перемикач мов для перегляду -->
    <div class="text-end mb-4">
        <a href="?user=<?= $slug ?>&lang=en" class="btn <?= $lang=='en'?'btn-primary':'btn-outline-primary' ?>">EN</a>
        <a href="?user=<?= $slug ?>&lang=no" class="btn <?= $lang=='no'?'btn-primary':'btn-outline-primary' ?>">NO</a>
        <a href="?user=<?= $slug ?>&lang=ua" class="btn <?= $lang=='ua'?'btn-primary':'btn-outline-primary' ?>">UA</a>
    </div>

    <!-- Фото + Ім'я -->
    <div class="text-center mb-5">
        <img src="<?= $photo ?>" class="rounded-circle shadow-lg" width="170" height="170" style="object-fit:cover;border:6px solid #0056b3;">
        <h1 class="display-4 fw-bold mt-3"><?= htmlspecialchars($name) ?></h1>
        <p class="lead"><?= htmlspecialchars($user['bio'][$lang] ?? '') ?></p>
    </div>

    <!-- Контакти -->
    <?php if (!empty($user['personal']['location']) || !empty($user['personal']['phone']) || !empty($user['personal']['email'])): ?>
    <h2><i class="fas fa-address-book me-2"></i> Контакти</h2>
    <div class="row g-3 mb-5">
        <?php if (!empty($user['personal']['location'])): ?><div class="col-md-6"><i class="fas fa-map-marker-alt text-danger"></i> <?= htmlspecialchars($user['personal']['location']) ?></div><?php endif; ?>
        <?php if (!empty($user['personal']['phone'])): ?><div class="col-md-6"><i class="fas fa-phone text-success"></i> <a href="tel:<?= htmlspecialchars($user['personal']['phone']) ?>"><?= htmlspecialchars($user['personal']['phone']) ?></a></div><?php endif; ?>
        <?php if (!empty($user['personal']['email'])): ?><div class="col-md-6"><i class="fas fa-envelope text-primary"></i> <a href="mailto:<?= htmlspecialchars($user['personal']['email']) ?>"><?= htmlspecialchars($user['personal']['email']) ?></a></div><?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Про мене -->
    <?php if (!empty($user['developer_description'][$lang])): ?>
        <h2><i class="fas fa-user me-2"></i> Про мене</h2>
        <p class="fs-5"><?= nl2br(htmlspecialchars($user['developer_description'][$lang])) ?></p>
    <?php endif; ?>

    <!-- Ключові кваліфікації -->
    <?php if (count($user['key_qualifications'][$lang] ?? []) > 0): ?>
        <h2><i class="fas fa-star me-2"></i> Ключові кваліфікації</h2>
        <ul class="list-group list-group-flush mb-4">
            <?php foreach ($user['key_qualifications'][$lang] as $item): ?>
                <li class="list-group-item"><i class="fas fa-check text-success"></i> <?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Досвід роботи -->
    <?php if (count($user['work_experience'][$lang] ?? []) > 0): ?>
        <h2><i class="fas fa-briefcase me-2"></i> Досвід роботи</h2>
        <ul class="list-group list-group-flush mb-4">
            <?php foreach ($user['work_experience'][$lang] as $item): ?>
                <li class="list-group-item"><i class="fas fa-check text-success"></i> <?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Освіта та курси -->
    <?php if (count($user['education'][$lang] ?? []) > 0): ?>
        <h2><i class="fas fa-graduation-cap me-2"></i> Освіта та курси</h2>
        <ul class="list-group list-group-flush mb-4">
            <?php foreach ($user['education'][$lang] as $item): ?>
                <li class="list-group-item"><i class="fas fa-check text-success"></i> <?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Мови -->
    <?php if (!empty($user['languages'][$lang])): ?>
        <h2><i class="fas fa-language me-2"></i> Мови</h2>
        <p class="fs-5"><?= htmlspecialchars($user['languages'][$lang]) ?></p>
    <?php endif; ?>

    <!-- Навички роботи з комп'ютером -->
    <?php if (!empty($user['computer_skills'][$lang])): ?>
        <h2><i class="fas fa-laptop-code me-2"></i> Навички роботи з комп'ютером</h2>
        <p class="fs-5"><?= htmlspecialchars($user['computer_skills'][$lang]) ?></p>
    <?php endif; ?>

    <!-- Сертифікати -->
    <?php if (!empty($user['certificates'][$lang])): ?>
        <h2><i class="fas fa-certificate me-2"></i> Сертифікати</h2>
        <p><?= htmlspecialchars($user['certificates'][$lang]) ?></p>
    <?php endif; ?>

    <!-- Професійні школи -->
    <?php if (count($user['vocational_schools'][$lang] ?? []) > 0): ?>
        <h2><i class="fas fa-school me-2"></i> Професійні школи</h2>
        <ul class="list-group list-group-flush mb-4">
            <?php foreach ($user['vocational_schools'][$lang] as $item): ?>
                <li class="list-group-item"><i class="fas fa-check text-success"></i> <?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Рекомендації -->
    <?php if (!empty($user['references'][$lang])): ?>
        <h2><i class="fas fa-handshake me-2"></i> Рекомендації</h2>
        <p><?= htmlspecialchars($user['references'][$lang]) ?></p>
    <?php endif; ?>

    <!-- Проекти -->
    <?php if (count($user['projects'] ?? []) > 0): ?>
        <h2><i class="fas fa-project-diagram me-2"></i> Проекти</h2>
        <?php foreach ($user['projects'] as $proj): ?>
            <div class="mb-4 p-3 border rounded">
                <strong><a href="<?= htmlspecialchars($proj['link'] ?? '#') ?>" target="_blank"><?= htmlspecialchars($proj['name'] ?? '') ?></a></strong><br>
                <?= htmlspecialchars($proj['desc'][$lang] ?? '') ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Кнопка відкриття модального вікна -->
    <div class="text-center mt-5">
        <button onclick="openPdfModal()" class="btn btn-danger btn-lg px-5 py-3 shadow">
            <i class="fas fa-file-pdf me-2"></i> Завантажити або надіслати PDF
        </button>
    </div>
</div>

<!-- МОДАЛЬНЕ ВІКНО -->
<div class="modal fade" id="pdfModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-file-pdf"></i> Завантажити або надіслати PDF</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4">

                <p class="fs-5 text-center mb-3"><strong>Оберіть мову для PDF-файлу:</strong></p>
                <div class="d-grid gap-2 mb-4">
                    <button onclick="downloadWithLang('en')" class="btn btn-outline-primary btn-lg py-3">🇬🇧 English</button>
                    <button onclick="downloadWithLang('no')" class="btn btn-outline-primary btn-lg py-3">🇳🇴 Norsk</button>
                    <button onclick="downloadWithLang('ua')" class="btn btn-outline-primary btn-lg py-3">🇺🇦 Українська</button>
                </div>

                <hr>

                <p class="fs-5 text-center mt-4 mb-3"><strong>Надіслати PDF на будь-яку пошту:</strong></p>
                <div class="input-group input-group-lg">
                    <input type="email" id="emailInput" class="form-control" placeholder="Введіть email" value="<?= htmlspecialchars($user['personal']['email'] ?? '') ?>">
                    <button onclick="sendPdfToEmail()" class="btn btn-success">Надіслати</button>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function openPdfModal() {
    new bootstrap.Modal(document.getElementById('pdfModal')).show();
}

function downloadWithLang(lang) {
    const element = document.getElementById('pdf-content');
    html2pdf().from(element).set({
        margin: [15,15,15,15],
        filename: '<?= str_replace(' ', '_', $name) ?>_Resume_' + lang.toUpperCase() + '.pdf',
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    }).save();
    bootstrap.Modal.getInstance(document.getElementById('pdfModal')).hide();
}

async function sendPdfToEmail() {
    const email = document.getElementById('emailInput').value.trim();
    if (!email) {
        alert('Введіть email адресу!');
        return;
    }

    const btn = event.currentTarget;
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Відправка...';
    btn.disabled = true;

    const element = document.getElementById('pdf-content');
    const pdfBase64 = await html2pdf().from(element).set({
        margin: [15,15,15,15],
        filename: 'Resume.pdf',
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4' }
    }).output('datauristring');

    const formData = new FormData();
    formData.append('email', email);
    formData.append('pdfBase64', pdfBase64);
    formData.append('name', '<?= addslashes($name) ?>');

    fetch('send-pdf.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) alert('✅ PDF надіслано на ' + email);
            else alert('Помилка: ' + (data.message || 'Спробуйте пізніше'));
        })
        .catch(() => alert('Помилка з’єднання'))
        .finally(() => {
            btn.innerHTML = original;
            btn.disabled = false;
            bootstrap.Modal.getInstance(document.getElementById('pdfModal')).hide();
        });
}
</script>

<?php include 'footer.php'; ?>