<?php
session_start();
session_destroy();
setcookie('access_token', '', time() + (59 * 60), "/");
setcookie('refresh_token', '', time() + (1380 * 60), "/");
setcookie('base_url_api', '', time() + (59 * 60), "/");
setcookie('base_path_api', '', time() + (59 * 60), "/");
setcookie('base_port_api', '', time() + (59 * 60), "/");
setcookie('base_dashboard_api', '', time() + (59 * 60), "/");
header("Location: /");
?>