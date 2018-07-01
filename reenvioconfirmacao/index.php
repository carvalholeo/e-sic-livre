<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

	require_once("../class/solicitante.class.php");
                            
        $idsolicitante = getSession("uid");
        
        $solicitante = new Solicitante($idsolicitante);
                
        if($solicitante->reenvioConfirmacao())
        {
            $msg = "<br>Prezado(a) ".$solicitante->getNome().", 
                    <br><br>Seu cadastro precisa ser completado. Foi reenviado um e-mail para o seu endere�o <b>".$solicitante->getEmail()."</b> solicitando a 
                        confirma��o do cadastro. 
                        <br><br>Ap�s a confirma��o, seu acesso ser� liberado.";
        }

        $_SESSION = array();
        session_destroy();
	$solicitante = null;
	
	include_once("../inc/topo.php");

	echo "<h1>ATEN��O</h1>";
	
	echo $msg."<br><br>&nbsp;";

	include_once("../inc/rodape.php");	
?>



