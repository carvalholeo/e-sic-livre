<?php 
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

//PAGINACAO - PARTE INICIAL
	
	$total="";
	//retorna a quantidade de registros da consulta
	function execQueryPag($consulta){
		global $total;
		global $limit;

		$consulta = strtolower($consulta);
		
		/*$pos = strpos($consulta, "from") - 1;
		$sql = "select count(*) as total ".substr($consulta,$pos,strlen($consulta));
	
		$resultado = execQuery($sql);
		$row = mysql_fetch_assoc($resultado);
		$total = $row["total"];*/
		
		$rs = execQuery($consulta);
		$total = mysql_num_rows($rs);
		
		return execQuery($consulta.$limit);
		
	}
	
	// Declara��o da pagina inicial  
	$pagina = $_POST["pagina"];  
	if($pagina == "") 
	{  
		$pagina = "1";  
	} 

	// Maximo de registros por pagina  
	$maximo = 300;
	
	// Calculando o registro inicial  
	$inicio = $pagina - 1;  
	$inicio = $maximo * $inicio;

	$limit = " limit $inicio,$maximo";
	
/* exemplo do uso
	include "paginacaoIni.php";
	
	$sql = "select * from tabela";

	$resultado = execQueryPag($sql);
	
	//inclui o arquivo paginacaoFim.php onde serao exibidos os controles da pagina��o, tem q ser abaixo da inclusao desse e depois da consulta

*/
	
?>
