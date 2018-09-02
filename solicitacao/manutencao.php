<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/
		
	    include_once("../inc/security.php");
            include_once("../class/solicitacao.class.php");
		
	
	
	$erro   = "";	//grava o erro, se houver, e exibe por meio de alert (javascript) atraves da funcao getErro() chamada no arquivo do formulario. ps: a fun��o � declara em inc/security.php

        $acao 	= "";
        
	
	//se tiver sido postado informa��o do formulario
	if($_POST['acao'])
	{

		$idsolicitante 	= $_POST["idsolicitante"];
		$textosolicitacao = $_POST["textosolicitacao"];
                $formaretorno	= $_POST["formaretorno"];
                $idsecretariaselecionada = $_POST['idsecretariaselecionada'];

		$solicitacao = new Solicitacao();
		
		$solicitacao->setIdSolicitante($idsolicitante);
		$solicitacao->setTextoSolicitacao($textosolicitacao);
		$solicitacao->setFormaRetorno($formaretorno);
                $solicitacao->setIdSecretariaSelecionada($idsecretariaselecionada);

            if (!$solicitacao->cadastra()){
		$erro = $solicitacao->getErro();
				
				if ($upload == 1){
					echo "<script>alert('".$alerta."');</script>";
				}	
			
		}else{
		
				enviadados();
				echo "<script>alert('Solicita��o enviada com sucesso!');location.href='index.php?ok=1';</script>";
				
				$solicitante = null;
		
		}		
	}
        else
        {
            $idsolicitante = getSession("uid");
        }
	
        

?>
