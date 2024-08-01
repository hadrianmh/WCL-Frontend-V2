<?php
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function decode_jwt($jwt) {
    try {
		$key = "33b9b3de94a42d19f47df7021954eaa8";
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        return (array) $decoded;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
?>