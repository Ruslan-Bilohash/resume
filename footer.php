<?php
// footer.php — ПОВНИЙ ФАЙЛ з вибором мови в футері
?>
<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        
        <!-- Вибір мови в футері -->
        <div class="text-center mb-4">
            <div class="d-inline-flex gap-2 p-2 bg-dark border border-secondary rounded-pill">
                <a href="?lang=en" class="btn <?= $lang === 'en' ? 'btn-light text-dark shadow-sm' : 'btn-outline-light' ?> px-4 py-2 fw-medium">
                    🇬🇧 EN
                </a>
                <a href="?lang=no" class="btn <?= $lang === 'no' ? 'btn-light text-dark shadow-sm' : 'btn-outline-light' ?> px-4 py-2 fw-medium">
                    🇳🇴 NO
                </a>
                <a href="?lang=ua" class="btn <?= $lang === 'ua' ? 'btn-light text-dark shadow-sm' : 'btn-outline-light' ?> px-4 py-2 fw-medium">
                    🇺🇦 UA
                </a>
            </div>
        </div>

        <!-- Основна інформація -->
        <div class="text-center">
            <p class="mb-3">
                <a href="https://bilohash.com" target="_blank" class="text-white text-decoration-none fs-5 fw-bold">
                    ❤️ BILOHASH RUSLAN
                </a>
            </p>
            <p class="mb-2 small opacity-75">
                &copy; <?= date('Y') ?> ResumeBuilder. All rights reserved.
            </p>
            <p class="mb-0">
                <a href="https://github.com/Ruslan-Bilohash/resume" target="_blank" class="text-white mx-3">
                    <i class="fab fa-github fa-2x"></i>
                </a>
            </p>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</body>
</html>
