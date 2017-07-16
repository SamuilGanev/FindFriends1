<?php

     session_start();
     session_name("socialnetwork");
     session_destroy();
     setcookie('email', $email, time()-1);

     header("Location: login.php");

?><li><form action="index.php" method="POST"><input type="search" name="search" size="60"></form></li>