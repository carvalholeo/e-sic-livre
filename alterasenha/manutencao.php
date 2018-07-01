<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

	include_once("../inc/autenticar.php");
	include_once("../class/solicitante.class.php");
	
	
	$erro   = "";	//grava o erro, se houver, e exibe por meio de alert (javascript) atraves da funcao getErro() chamada no arquivo do formulario. ps: a fun��o � declara em inc/security.php


	//se tiver sido postado informa��o do formulario
	if($_POST['acao'])
	{

		$idsolicitante	= getSession("uid");
		$senhaatual	= $_POST["senhaatual"];
		$novasenha	= $_POST["novasenha"];
		$confirmasenha	= $_POST["confirmasenha"];

		
		$solicitante = new Solicitante();
		
		if (!$solicitante->alteraSenha($idsolicitante,$senhaatual,$novasenha,$confirmasenha))
			$erro = $solicitante->getErro();
		else
			echo "<script>location.href='".SITELNK."index.php';</script>";
		
		$solicitante = null;
	}


?>
