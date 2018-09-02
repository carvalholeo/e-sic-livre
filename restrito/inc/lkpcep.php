<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

  //include("../inc/autenticar.php");
  include("../inc/security.php");
  
  $q = $_REQUEST['q'];

  $sql = "SELECT cep, logradouro, bairro, cidade AS municipio, uf AS estado
		  FROM vw_cep
		  WHERE cep LIKE '$q%'
		  AND cidade = 'Natal'
		  AND uf = 'RN'
		  LIMIT 0,10";

  $resultado = execQuery($sql);
  $codigos = "";
  while ($row = mysqli_fetch_array($resultado)){
	
	$codigos .= '"'.$row['cep'].'",' ;
	$nomes .= '"'.htmlentities($row['logradouro']).' - '.htmlentities($row['bairro']).', '.htmlentities($row['municipio']).'/'.$row['estado'].'",' ;
	
  
  }
  $codigos = substr($codigos,0,strlen($codigos)-1);
  $nomes = substr($nomes,0,strlen($nomes)-1);
  ?>
  
  showQueryDiv("<?php echo $q;?>", new Array(<?php echo $codigos;?>), new Array(<?php echo $nomes;?>))
  
  
  
