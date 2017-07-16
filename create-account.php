<?php

    session_name("socialnetwork");
    session_start();

    if (isset($_POST['create-account'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordagain = $_POST['passwordagain'];
        $day = $_POST['day'];
        $mouth = $_POST['mouth'];
        $year = $_POST['year'];
        $sex = $_POST['sex'];

        $firstname = htmlspecialchars($firstname);
        $firstname = strip_tags($firstname);
        $lastname = htmlspecialchars($lastname);
        $lastname = strip_tags($lastname);
        $password = htmlspecialchars($password);
        $password = strip_tags($password);

        if (strlen($firstname) >= 3) {
            if (strlen($lastname) >= 3) {
                if (strlen($password) >= 6) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        if ($day != 0 and $mouth != 0 and $year != 0 and $sex) {
                            if($password === $passwordagain) {
                                $db = new mysqli("localhost", "root", "", "socialnetwork");
                                $i = $db->query("SELECT * FROM users");
                                $emailindb = $i->fetch_assoc();
                                    if ($email !== $emailindb['email']) {
                                        $profileimg = "photosandvideos/user.png";
                                        $db->query("INSERT INTO users (firstname, lastname, email, password, day, mouth, year, sex, profileimage) VALUES('$firstname', '$lastname', '$email', '$password', '$day', '$mouth', '$year', '$sex', '$profileimg')");
                                        $_SESSION['email'] = $email;
                                        header("Location: index.php");
                                    } else {
                                        die("Email already exist!");
                                    }
                            } else {
                                die("Passwords doesn't match!");
                            }
                        } else {
                            die("Enter full information!");
                        }
                    } else {
                        die("Invalid email!");
                    }
                } else {
                    die("Too short password!");
                }
            } else {
                die("Too short lastname!");
            }
        } else {
            die("Too short firstname!");
        }
    }

?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>findFriends: Sign Up</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Login.css">
    <link rel="shortcut icon" href="">
</head>

<body>
    <div class="login-card"><img src="assets/img/avatar_2x.png" class="profile-img-card">
        <p class="profile-name-card"> </p>
        <form class="form-signin" action="create-account.php" method="POST"><span class="reauth-email"> </span>
            <input class="form-control" type="text" name="firstname" placeholder="First name" autofocus="">
            <input class="form-control" type="text" name="lastname" placeholder="Last name">
            <input class="form-control" type="email" name="email" placeholder="Email address">
            <input class="form-control" type="password" name="password" placeholder="Password">
            <input class="form-control" type="password" name="passwordagain" placeholder="Password again">
            Birthday:
            <select name="day">
                <option value="0">Day</option>
                <?php
                    $day = 1;
                    while ($day <= 31) {
                        echo '<option value="'.$day.'">'.$day.'</option>';
                        $day++;
                    }
                ?>
            </select>
            <select name="mouth">
                <option value="0">Mouth</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">Octomber</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
            <select name="year">
                <option value="0">Year</option>
                <?php
                    $year = date("Y");
                    $end = $year-100;
                    while ($year >= $end) {
                        echo '<option value="'.$year.'">'.$year.'</option>';
                        $year--;
                    }
                ?>
            </select>
            <span>
                <span>
                    <input type="radio" name="sex" value="1">
                    <label>Women</label>
                </span>
                <span>
                    <input type="radio" name="sex" value="2">
                    <label>Man</label>
                </span>
            </span>
            <button class="btn btn-primary btn-block btn-lg btn-signin" type="submit" name="create-account">Sign up</button>
        </form><a href="login.php" class="forgot-password">Already have an account?!</a></div>
    <script src="/js/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script id="bs-live-reload" data-sseport="54382" data-lastchange="1499855858033" src="/js/livereload.js"></script>
</body>

</html>