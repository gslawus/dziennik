<?php
$isAdmin = false;
if (isset($_SESSION['user'])) {
    $login = $_SESSION['user'];
    $db = $registry->db;
//    echo 'Witaj <b>' . $login . '</b> | ';
//    $isAdmin = $db::isUserInRole($login, 'admin');
//    if ($isAdmin) {
//        echo '(admin) |';
//    }
    ?>
    Witaj 
    <a href="/<?= APP_ROOT ?>/account/edit"><b><?= $login ?><b></a>&nbsp;|
    <a href="/<?= APP_ROOT ?>/account/logout">Wyloguj</a>
    <?php
} else {
    ?>
    <a href="/<?= APP_ROOT ?>/account/login">Logowanie</a> &nbsp; |  	
    <a href="/<?= APP_ROOT ?>/account/register">Rejestracja</a>
    <?php
}
?>