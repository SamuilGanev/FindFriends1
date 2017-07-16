<?php

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $db = new mysqli("localhost", "root", "", "socialnetwork");
        $indb = $db->query("SELECT * FROM users WHERE email='$email'");
        $indb = $indb->fetch_assoc();
        if ($email === $indb['email']) {
            if ($password === $indb['password']) {
                if (isset($_POST['remember'])) {
                    setcookie("email", $email, time()+60*60*7);
                    $_COOKIE['email'] = $email;
                    header("Location: index.php");
                } else {
                    session_name("socialnetwork");
                    session_start();
                    $_SESSION['email'] = $indb['email'];
                    header("Location: index.php");
                }
            } else {
                die("Incorect password!");
            }
        } else {
            die("Incorect email!");
        }
    }

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>findFriends: Sign In</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Login.css">
    <link rel="shortcut icon" href="">
</head>

<body>
    <div class="login-card"><img src="assets/img/avatar_2x.png" class="profile-img-card">
        <p class="profile-name-card"> </p>
        <form class="form-signin" action="login.php" method="POST"><span class="reauth-email"> </span>
            <input class="form-control" type="email" name="email" required="" placeholder="Email address" autofocus="" id="inputEmail">
            <input class="form-control" type="password" name="password" required="" placeholder="Password" id="inputPassword">
            <div class="checkbox">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember">Remember me</label>
                </div>
            </div>
            <button class="btn btn-primary btn-block btn-lg btn-signin" type="submit" name="login">Sign in</button>
        </form><a href="create-account.php" class="forgot-password">I does not have an account?!</a></div>
    <script src="/js/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script id="bs-live-reload" data-sseport="54382" data-lastchange="1499855858033" src="/js/livereload.js"></script>
</body>

</html>