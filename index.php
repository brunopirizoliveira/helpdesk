<?php
require_once('controller/verificaAcesso.php');

$nvl = $_SESSION['nivel'];

if($nvl != 0){
	header('Location: controller/tech/home.php');
}else{
	header('Location: controller/user/home.php');
}