<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informaзгo baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa й software livre; vocк pode redistribuн-lo e/ou
 modificб-lo sob os termos da Licenзa GPL2.
***********************************************************************************/

include_once("../inc/autenticar.php");
include_once("../class/solicitacao.class.php");

function validaDados()
{

	// Recuperamos os valores dos campos atravГ©s do mГ©todo POST
	global $erro;
	global $resposta;
	global $comentario;
	
	if (empty($resposta))
	{
		$erro = "Por favor selecione uma opзгo de resposta!";
		return false;
	}
	
	return true;
}

$erro = "";
$resposta = $_POST["resposta"];
$comentario = $_POST["comentario"];

if ($_POST["Enviar"]) {

    if(validaDados())
    {
        $sql="INSERT INTO lda_enquete
                (idsolicitante, resposta, comentario, dataresposta)
                VALUES
                (".getSession("uid").", '".$resposta."','".(str_replace("'","\'",$comentario))."',NOW())";

        if (!execQuery($sql))
            $erro = "Erro ao gravar enquete";

    }
}

?>