<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

  include("../inc/autenticar.php");
  checkPerm("DELSEC");
  
  $codigo = $_REQUEST["codigo"];

  $sql = "delete from sis_secretaria where idsecretaria = '$codigo'";

  if(!execQuery($sql))
  {
    echo "<script>alert('Nao foi possivel excluir este SIC. Esse registro pode estar em uso.');</script>";
  }
  else
  {
	logger("Excluiu SIC");
	
  }

  echo "<script>document.location='?sis_secretaria';</script>";

?>