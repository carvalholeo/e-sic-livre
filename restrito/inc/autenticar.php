<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

    require_once("../inc/security.php");
    isauth();
    /*session_start();
    if(! isset($_SESSION["logado"]))
	{
		header("Location: ../index.php");
		die();
	}*/
?>
