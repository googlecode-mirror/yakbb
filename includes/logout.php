<?php

if(!defined("SNAPONE")) exit;

$plugins->callhook("logout_start");

setcookie(DBPRE."user", "", 0);
setcookie(DBPRE."pass", "", 0);
session_regenerate_id();

$plugins->callhook("logout_end");

redirect("?");


?>