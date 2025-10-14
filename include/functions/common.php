<?php
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}

function db_connect()
{
    $dbh = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
