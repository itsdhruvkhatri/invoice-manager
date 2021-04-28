<?php

$uri = "http://kodlyweb.com/server/updates.php?system=$system&version=$version";

$update = file_get_contents($uri);

if ($update == "An update is available"){
    $_SESSION['warning'] = "An update is available!";
}