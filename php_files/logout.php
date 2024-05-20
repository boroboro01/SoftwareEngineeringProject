<?php

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_start();

session_unset();

session_destroy();

echo "<script>alert('Logout was successfull!');
    window.location.href = './main.php';</script>";
exit;
?>