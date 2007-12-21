<?php

if(!defined("SNAPONE")) exit;

setcookie(DBPRE."user", "", 0);
setcookie(DBPRE."pass", "", 0);
session_regenerate_id();
redirect("?");


?>