<?php
// config.php — ПОВНИЙ ОНОВЛЕНИЙ
session_start();
header('Content-Type: text/html; charset=utf-8');

define('BASE_DIR', __DIR__);
define('USERS_DIR', BASE_DIR . '/users');
define('UPLOADS_DIR', BASE_DIR . '/uploads');

foreach ([USERS_DIR, UPLOADS_DIR] as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0755, true);
}

$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'en');
if (!in_array($lang, ['en', 'no', 'ua'])) $lang = 'en';
$_SESSION['lang'] = $lang;

$site_name = "ResumeBuilder — Створи своє резюме";

require_once 'lang.php';
require_once 'functions.php';
?>