<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

  include("../inc/autenticar.php");
  checkPerm("DELTIPOSOL");
  
  $codigo = $_REQUEST["codigo"];

  $sql = "delete from lda_tiposolicitacao where idtiposolicitacao = '$codigo'";

  if(!execQuery($sql))
  {
    echo "<script>alert('N�o foi possivel excluir este tipo de solicita��o. Esse registro pode estar em uso.');</script>";
  }
  else
  {
	logger("Tipo de solicita��o exclu�do com sucesso.");
  }

  echo "<script>document.location='?lda_tiposolicitacao';</script>";

?>