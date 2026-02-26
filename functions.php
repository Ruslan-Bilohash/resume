<?php
// functions.php — ПОВНИЙ ОНОВЛЕНИЙ
function slugify($text) {
    $text = mb_strtolower($text, 'UTF-8');
    $text = str_replace([' ', 'і', 'ї', 'є', 'ґ'], ['_', 'i', 'i', 'e', 'g'], $text);
    $text = preg_replace('/[^a-z0-9_]/', '', $text);
    return trim($text, '_');
}

function get_user_file($slug) {
    return USERS_DIR . '/' . $slug . '.json';
}

function load_user($slug) {
    $file = get_user_file($slug);
    return file_exists($file) ? json_decode(file_get_contents($file), true) : null;
}

function save_user($slug, $data) {
    $file = get_user_file($slug);
    file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

function send_welcome_email($email, $name, $slug) {
    $subject = "Дякуємо! Ваше резюме готове 🎉";
    $link = "https://" . $_SERVER['HTTP_HOST'] . "/resume.php?user=" . $slug . "&lang=" . $_SESSION['lang'];
    $message = "
    <html><body style='font-family:Arial,sans-serif;background:#f9f9f9;padding:40px;'>
        <div style='max-width:600px;margin:auto;background:white;padding:40px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,0.1);'>
            <h2 style='color:#0056b3;'>Вітаємо, $name!</h2>
            <p>Ви успішно створили професійне резюме на ResumeBuilder.</p>
            <p><a href='$link' style='background:#0056b3;color:white;padding:15px 30px;text-decoration:none;border-radius:50px;font-weight:bold;'>Переглянути резюме</a></p>
            <p>З повагою,<br><strong>Команда ResumeBuilder</strong></p>
        </div>
    </body></html>";

    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=utf-8\r\nFrom: noreply@resumebuilder.com";
    mail($email, $subject, $message, $headers);
}

function is_logged_in() {
    return isset($_SESSION['user_slug']);
}

function calculate_progress($user) {
    $filled = 0;
    $total = 12; // кількість основних полів
    if (!empty($user['personal']['first_name'])) $filled++;
    if (!empty($user['personal']['last_name'])) $filled++;
    if (!empty($user['personal']['phone'])) $filled++;
    if (!empty($user['personal']['email'])) $filled++;
    if (!empty($user['photo'])) $filled++;
    if (!empty($user['bio']['ua']) || !empty($user['bio']['en'])) $filled++;
    if (!empty($user['developer_description']['ua']) || !empty($user['developer_description']['en'])) $filled++;
    if (count($user['key_qualifications']['ua'] ?? []) > 0) $filled++;
    if (count($user['projects'] ?? []) > 0) $filled++;
    return round(($filled / $total) * 100);
}
?>