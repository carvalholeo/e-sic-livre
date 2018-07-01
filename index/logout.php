<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informaзгo baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa й software livre; vocк pode redistribuн-lo e/ou
 modificб-lo sob os termos da Licenзa GPL2.
***********************************************************************************/

include "../inc/security.php";

if ($_SESSION[SISTEMA_CODIGO]) {
	$_SESSION = array();
	session_destroy();
}
Redirect("../index.php");

