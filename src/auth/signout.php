<?php
session_start();
session_destroy();
setcookie('access_token', '', time() + (59 * 60), "/");
setcookie('refresh_token', '', time() + (1380 * 60), "/");
setcookie('base_url_api', '', time() + (59 * 60), "/");
setcookie('base_path_api', '', time() + (59 * 60), "/");
setcookie('base_port_api', '', time() + (59 * 60), "/");
setcookie('base_dashboard_api', '', time() + (59 * 60), "/");

$base_url = !empty($_COOKIE['base_url']) ? $_COOKIE['base_url'] : 'http://localhost';
$base_path = !empty($_COOKIE['base_path']) ? $_COOKIE['base_path'] : '';
$base_port = !empty($_COOKIE['base_port']) ? $_COOKIE['base_port'] : '80';

header("Location: ". rawurldecode($base_url) .":". $base_port . rawurldecode($base_path));
?>