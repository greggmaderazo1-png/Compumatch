<?php
// =============================================================
// CompuMatch — SAMPLE settings — copy to config.php and fill in (passwords and API keys).
// Every secret lives here and nowhere else, so this is the ONE file to
// protect. Direct web access to it is blocked by .htaccess, and PHP
// never prints it. NEVER share this file or post it online (GitHub,
// group chats, thesis appendix). Share config.sample.php instead.
//
// Each value can also come from a server environment variable with the
// same name; that always wins over what is written here.
// =============================================================
if (!function_exists('cm_env')) {
    function cm_env($name, $fallback) {
        $v = getenv($name);
        return ($v !== false && $v !== '') ? $v : $fallback;
    }
}

// ---- Database (InfinityFree MySQL) ----
define('DB_HOST', cm_env('DB_HOST', 'CHANGE_ME'));
define('DB_PORT', cm_env('DB_PORT', '3306'));
define('DB_NAME', cm_env('DB_NAME', 'CHANGE_ME'));
define('DB_USER', cm_env('DB_USER', 'CHANGE_ME'));
define('DB_PASS', cm_env('DB_PASS', 'CHANGE_ME'));
define('DB_SSL_CA', cm_env('DB_SSL_CA', ''));

// ---- Email (Brevo API) ----
define('BREVO_API_KEY', cm_env('BREVO_API_KEY', 'CHANGE_ME'));

// ---- System Admin login ----
// The password is stored ONLY as a hash. Change it from the System Admin
// page ("Change Password") — that saves a new hash in the database and
// overrides the one below.
define('SYSTEMADMIN_USER', cm_env('SYSTEMADMIN_USER', 'admin'));
define('SYSTEMADMIN_PASS_HASH', cm_env('SYSTEMADMIN_PASS_HASH', 'CHANGE_ME (run: php -r "echo password_hash(\'your-password\', PASSWORD_DEFAULT);")'));

// ---- Store permit check ----
// The words on an uploaded permit are read in the store's own browser,
// so a tech-savvy user could send fake text. With this OFF (false), a
// permit that passes the check is only MARKED "recommended" and still
// waits for the System Admin to approve it. Set to true to approve those
// stores instantly (faster, but trusts the browser).
define('PERMIT_AUTO_APPROVE', filter_var(cm_env('PERMIT_AUTO_APPROVE', 'false'), FILTER_VALIDATE_BOOLEAN));
