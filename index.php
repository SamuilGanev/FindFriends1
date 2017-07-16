<?php

    session_name("socialnetwork");
    session_start();

    $db = new mysqli("localhost", "root", "", "socialnetwork");

    if (isset($_COOKIE['email'])) {
        $info = $_COOKIE['email'];
        $connect = $db->query("SELECT * FROM users WHERE email='$info'");
        $database = $connect->fetch_assoc();
    } else {
        if (isset($_SESSION['email'])) {
            $info = $_SESSION['email'];
            $connect = $db->query("SELECT * FROM users WHERE email='$info'");
            $database = $connect->fetch_assoc();
        } else {
            header("Location: login.php");
        }
    }

    $myid = $database['id'];
    $profileimage = $database['profileimage'];

    if (isset($_POST['upload'])) {
        $post = $_POST['post'];
        $file_name = $_FILES['file']['name'];
        $tags = $_POST['tag'];
        $place = $_POST['place'];

        $post = htmlspecialchars($post);
        $post = strip_tags($post);
        $tags = htmlspecialchars($tags);
        $tags = strip_tags($tags);
        $place = htmlspecialchars($place);
        $place = strip_tags($place);

        if ($post !== '' or $file_name !== '') {

            $fname = $database['firstname'];
            $lname = $database['lastname'];
            $uploader = $fname.' '.$lname;

            if ($file_name != '') {
                $file_tmp = $_FILES['file']['tmp_name'];
                $file_size = $_FILES['file']['size'];
                $file_error = $_FILES['file']['error'];

                $file_ext = explode('.', $file_name);
                $file_ext = strtolower(end($file_ext));

                $allowed = array('png', 'jpg', 'gif', 'mp4');

                if (in_array($file_ext, $allowed)) {
                    if ($file_error === 0) {
                        if ($file_size <= 2097152) {
                            $file_name_new = uniqid('', true).'.'.$file_ext;
                            $file_destination = 'photosandvideos/'.$file_name_new;

                            if (move_uploaded_file($file_tmp, $file_destination)) {
                                $db->query("INSERT INTO posts (uploaderid, uploader, uploaderprofileimage, post, photoorvideo, tags, place, posted_at, likes) VALUES ('$myid', '$uploader', '$profileimage', '$post', '$file_destination', '$tags', '$place', NOW(), 0)");
                            }
                        } else {
                            die ("This file is too big!");
                        }
                    } else {
                        die ("There was a problem uploading a file!");
                    }
                } else {
                    die ("You can't upload this type of file!");
                }
            } else {
                $db->query("INSERT INTO posts (uploaderid, uploader, uploaderprofileimage, post, photoorvideo, tags, place, posted_at, likes) VALUES ('$myid', '$uploader', '$profileimage', '$post', '', '$tags', '$place', NOW(), 0)");
            }
        } else {
            die ("To upload post you need to write something or to upload photo!");
        }
    }

?>
<html>

<head>
        <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>findFriends: Timeline</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Login.css">
    <link rel="stylesheet" href="assets/css/Pretty-Header.css">
    <script type="text/javascript" src="ajax.js"></script>
    <style>
        body {
            background-color: #cccccc;
        }
        #newpost {
            background-color: red;
            color: #fff;
            width: 150px;
            height: 40px;
            border: 0px;
            border-radius:3px;
            font-size: 18px;
            font: Arial, sans-serif;
            box-shadow: 0px 1px 1px 0px red;
        }
        #newpost:hover {
            background-color: orange;
            box-shadow: 0px 1px 1px 0px orange;
        }
        .createpost textarea {
            width: 95%;
            max-width: 95%;
            height: 200px;
            max-height: 200px;
            border-radius: 5px;
        }
        .createpost {
            width: 45%;
            margin-top: -25px;
            height: 45%;
            text-align: center;
            position: absolute;
            position: fixed;
            max-width: 85%;
            background-color: #d9d9d9;
            border-radius: 5px;
            box-shadow: 0px 1px 1px 0px;
            top: 140px;
            margin-left: 35px;
        }
        .options {
            width: 150px;
            height: 30px;
            border: 0px;
            border-radius: 5px;
            color: #fff;
            box-shadow: 0px 1px 1px 0px;
            font: bold;
            font-size: 18px;
        }
        .options:hover {
            background-color: #e6e6e6;
        }
        #upload {
            background-color: #3399ff;
            border: 0px;
            border-radius: 3px;
            color: #fff;
            width: 60px;
        }
        #upload:hover {
            background-color: #0073e6;
        }
        #close {
            background-color: red;
            border: 0px;
            border-radius: 3px;
            color: #fff;
            width: 60px;
        }
        #close:hover {
            background-color: orange;
        }
        .post {
            border-radius: 5px;
            background-color: white;
            width: 80%;
        }
        #profilepicture {
            border-radius: 50%;
        }
        .like {
            color: #000;
            font: bold, sans-serif;
            font-size: 18px;
            background: url("https://cdn.pixabay.com/photo/2016/08/29/13/55/heart-1628313__340.png") no-repeat;
            background-size: 17px 17px;
            padding-left: 17px;
            border: none;
        }
        .like:hover {
            color: red;
        }
        .unlike {
            color: red;
            font: bold, sans-serif;
            font-size: 18px;
            background: url("https://cdn.pixabay.com/photo/2016/08/29/13/55/heart-1628313__340.png") no-repeat;
            background-size: 17px 17px;
            padding-left: 17px;
            border: none;
        }
        .alllikes {
            width: 45%;
            background-color: #ffffff;
            border-radius: 10px;
            position: absolute;
            position: fixed;
        }
        .num {
            background-color: #ffffff;
            border: 0px;
            color: #000;
        }
        .num:hover {
            color: red;
        }
        @media screen and (max-width: 780px) {
            .createpost {
                width: 90%;
                height: 65%;
                text-align: center;
                position: absolute;
                position: fixed;
                max-width: 85%;
                background-color: #d9d9d9;
                border-radius: 5px;
                box-shadow: 0px 1px 1px 0px;
                top: 140px;
                margin-left: -50px;
            }
        }
    </style>
</head>

<body>
<div class="body">
    <nav class="navbar navbar-default custom-header">
        <div class="container-fluid">
            <div class="navbar-header"><a class="navbar-brand navbar-link" href="#">find<span>Friends </span> </a>
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav links">
                    <li role="presentation"><a href="index.php"> <b>Timeline</b></a></li>
                    <li role="presentation"><a href=profile.php?userid=<?php echo $database['id']; ?>><b><?php echo $database['firstname']; ?></b></a></li>
                    <li role="presentation"><a href="messages.php"><b>Messages</b> </a></li>
                    <li role="presentation"><a href="notifi.php" class="custom-navbar"><b>Notifications</b> </a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#"> <span class="caret"></span><img src=<?php echo $database['profileimage']; ?> class="dropdown-image"></a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li role="presentation"><a href="settings.php"><b>Settings </b></a></li>
                            <li role="presentation"><a href="create-page.php"><b>Create page</b></a></li>
                            <li role="presentation" class="active"><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <div style="margin-top: 60px;"></div>
    <table border="0" width="90%" height="100%" align="center">
        <tr>
            <td width="20%" rowspan="2">
                <!-- Settings, Create page and more ... -->
            </td>
            <td width="50%" align="center" rowspan="2">
                <!-- Posts -->
                <?php

                $postid = $db->query("SELECT * FROM followers WHERE followerid='$myid'");
                while ($whatpost = $postid->fetch_assoc()) {
                $followed = $whatpost['followedid'];
                $posts = $db->query("SELECT * FROM posts WHERE uploaderid='$myid' or uploaderid='$followed' Order By id DESC");
                    while ($row = $posts->fetch_assoc()) {
                    $idofpost = $row['id'];
                    echo '<div class="post">';
                    echo '<table border="0" width="95%" height="95%" align="center"><tbody>';
                        echo '<tr>';
                            echo '<td style="padding-top: 5px;" width="40" align="center"><img src="'.$row['uploaderprofileimage'].'" id="profilepicture" width="50" height="50"/></td>';
                            echo '<td>'.$row['uploader'].'</td>';
                            if ($row['tags']) {
                                echo '<td style="padding-right: 120px;">'.'with '.$row['tags'].'</td>';
                            }
                        echo '</tr>';
                        echo '<tr>';
                        if ($row['tags']) {
                                $colspan = 3;
                            } else {
                                $colspan = 2;
                            }
                            $textofpost = $row['post'];
                            $image = $row['photoorvideo'];
                            if ($image === '') {
                                //Samo text
                                echo '<td colspan="'.$colspan.'" style="margin-top: 0px;">';
                                    echo $row['post'];
                                    echo '</td>';
                            } else {
                                if ($textofpost !== '') {
                                    //I text i snimka
                                    echo '<td colspan="'.$colspan.'" style="margin-top: 0px;">';
                                        echo $row['post'].'<br>';
                                        echo '<img src="'.$image.'" width="100%" style="border-radius: 10px;" height="460"/>';
                                        echo '</td>';
                                } else {
                                    //Snimka bez text
                                    echo '<td colspan="'.$colspan.'" style="margin-top: 0px;">';
                                        echo '<img src="'.$image.'" width="100%" style="border-radius: 10px;" height="460"/>';
                                    echo '</td>';
                                }
                            }
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td colspan="'.$colspan.'" style="margin-top: 0px;">';
                                echo '<div class="postoptions"  style="margin-bottom: -16px; padding-top: 10px;">';
                                    $like = $db->query("SELECT * FROM postslikes WHERE postid='$idofpost'");
                                    $liker = $like->fetch_assoc();
                                    if ($liker['likerid'] != $myid) {
                                        echo '<form action=index.php?postid='.$idofpost.' id="like" method="POST"><input type="submit" class="like" name="like" value="Love"></form>';
                                    } else {
                                        echo '<form action=index.php?postid='.$idofpost.' method="POST"><input type="submit" class="unlike" name="unlike" value="Love"></form>';
                                    }
                                    echo '<form action=index.php?postid='.$idofpost.' method="POST" style="margin-left: 60px; margin-top: -38px;"><input id="wholikethis" type="submit" name="wholikethis" class="num" value="'.$row['likes'].'"></form>';
                                echo '</div>';
                            echo '</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td colspan="'.$colspan.'">';
                                echo '<div style="padding-top: 10px;" />';
                            echo '</td>';
                        echo '</tr>';
                        echo '</tbody></table>';
                    echo '</div>';
                    echo '<div style="margin-top: 20px;"></div>';
                    }
                }

                if (isset($_POST['like'])) {
                    $idofpost = $_GET['postid'];
                    $db->query("INSERT INTO postslikes (postid, likerid) VALUES ('$idofpost', '$myid')");
                    $db->query("UPDATE posts SET likes=likes+1 WHERE id='$idofpost'");
                }
                if (isset($_POST['unlike'])) {
                    $idofpost = $_GET['postid'];
                    $db->query("DELETE FROM postslikes WHERE postid='$idofpost' AND likerid='$myid'");
                    $db->query("UPDATE posts SET likes=likes-1 WHERE id='$idofpost'");
                }
                if (isset($_POST['wholikethis'])) {
                    $idpost = $_GET['postid'];
                }

                ?>
                <center>
                    <div class="createpost">
                        <form action="index.php" method="POST" enctype="multipart/form-data"><div style="padding-top: 20px;"></div>
                            <textarea name="post" placeholder="Post ..."></textarea><br><br>
                            <div align="left" style="padding-left: 17px;"><input type="file" name="file"/><br>
                            Tag someone: <input type="text" name="tag" placeholder="Tags"/>
                            Add a place: <input type="text" name="place" placeholder="Place"/>
                            <input type="submit" name="upload" value="Post" id="upload"><br>
                            <div align="right" style="padding-right: 4px;"><button id="close">Close</button></div>
                        </form>
                    </div>
                    <div class="alllikes">
                        <h1>People who likes this post: </h1>
                        <?php
                            echo $idpost;
                        ?>
                    </div>
                </center>
            </td>
            <td width="20%">
                <!-- Create new post -->
                <center><div align="center" style="margin-right: 0px;"><button id="newpost" style="margin-top: 0px;">NEW POST</button></div></center>
            </td>
        </tr>
        <tr>
            <td height="100%">

            </td>
        </tr>
    </table>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript">
//Add new post
 $(document).ready(function(){
     $('.createpost').hide();
     $('#close').click(function(){
         $('.createpost').slideUp();
     });
     $('#newpost').click(function(){
         $('.createpost').slideDown();
     });
});
</script>
<script type="text/javascript">
//Show who like this
$(document).ready(function() {
    $('.alllikes').hide();
    $('#wholikethis').click(function() {
         $('.alllikes').slideDown();
     });
});
</script>
<script type="text/javascript" src="ajax.js"></script>
</body>
</html>