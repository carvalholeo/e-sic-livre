<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

	include_once("../class/solicitante.class.php");	
	
	
	
	$erro="";
	
	if($_POST['btsub'])
	{
		$cpfcnpj = $_POST['cpfcnpj'];
		
		$solicitante = new Solicitante();
		
		if (!$solicitante->resetaSenha($cpfcnpj))
			$erro = $solicitante->getErro();
		else
			$erro = "Caro(a) ".$solicitante->getNome().", sua senha foi redefinida com sucesso. A nova senha foi enviada para o seu e-mail: ".$solicitante->getEmail();

		$solicitante = null;
	}
?>

