<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

  //include("../inc/autenticar.php");
  include("../inc/security.php");
  
  $q = $_REQUEST['q'];

  $sql = "select cep, logradouro, bairro, cidade as municipio, uf as estado
		  from vw_cep
		  where cep like '$q%'
		  and cidade = 'Natal'
		  and uf = 'RN'
		  limit 0,10";

  $resultado = execQuery($sql);
  $codigos = "";
  while ($row = mysql_fetch_array($resultado)){
	
	$codigos .= '"'.$row['cep'].'",' ;
	$nomes .= '"'.htmlentities($row['logradouro']).' - '.htmlentities($row['bairro']).', '.htmlentities($row['municipio']).'/'.$row['estado'].'",' ;
	
  
  }
  $codigos = substr($codigos,0,strlen($codigos)-1);
  $nomes = substr($nomes,0,strlen($nomes)-1);
  ?>
  
  showQueryDiv("<?php echo $q;?>", new Array(<?php echo $codigos;?>), new Array(<?php echo $nomes;?>))
  
  
  
