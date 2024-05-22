<?php
require_once('funs.php');
session_start();
unset($_SESSION['username']);
session_destroy();

if(isset($_SESSION['username']))
echo 'Ошибка';
else
{
	header("location:index.php");
    exit();
}