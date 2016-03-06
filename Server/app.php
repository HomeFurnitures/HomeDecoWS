<?php

if (isset($_POST)) {
    if (empty($_POST['username'])) {
        echo "Error , no username specified";
    } else if (empty($_POST['password'])) {
        echo "Error , no password specified";
    } else {
        $data = "{username:" . $_POST['username'] .
            ",password:" . $_POST['password'] . "}";
        header('Content-Type: application/json');
        echo json_encode($data);
    }
} else {
    echo "Error , post is not set";
}

