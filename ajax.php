<?php
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
    echo '<response>';
    $likes = $_GET['likes'];
    echo htmlentities($likes);
    echo '</response>';
?>