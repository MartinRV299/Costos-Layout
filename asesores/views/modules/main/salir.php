<?php

session_start();
unset($_COOKIE['configColorScreen']);
setcookie('configColorScreen', null, time()-3600);
unset($_COOKIE['configSideLeft']);
setcookie('configSideLeft', null, time()-3600);
unset($_COOKIE['configScreenSize']);
setcookie('configScreenSize', null, time()-3600);
session_destroy();
header("location:interfazIngreso");