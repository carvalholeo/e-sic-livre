<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

include "../inc/security.php";

if ($_SESSION[SISTEMA_CODIGO]) {
	$_SESSION = array();
	session_destroy();
}
Redirect("../index.php");

