<?php


//if($_SERVER['REQUEST_URI'] == '/crm_for_telegramBot/index.php') {
//    header('Location: crm_for_telegramBot/');
//    exit();
//}

$title = 'Home page';
ob_start();
?>

<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>

