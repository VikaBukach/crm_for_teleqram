<?php

function tt($str) {
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}

function tte($str) {
    echo "<pre>";
    print_r($str);
    echo "</pre>";
    exit();
}

//config.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'mysql');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'crm_for_tgBot');

define('START_ROLE', 1);


