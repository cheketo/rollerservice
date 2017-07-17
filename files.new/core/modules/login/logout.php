<?php
session_name("rollerservice");
session_cache_expire(15800);
session_start();
session_destroy();
//Unset Cookies
setcookie("rollerservice", "", 0 ,"/");
setcookie("user_id", "", 0 ,"/");
setcookie("profile_id", "", 0 ,"/");
setcookie("first_name", "", 0 ,"/");
setcookie("last_name", "", 0 ,"/");
setcookie("user", "", 0 ,"/");
setcookie("password", "", 0 ,"/");
die();
?>