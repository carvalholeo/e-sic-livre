<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

include "manutencao.php";
include "../inc/topo.php";
//include "../inc/paginacaoIni.php";	

$sql = "select * from sis_param";

if ($_REQUEST['imprimir'])
{
	generateReport(array("!PATH" => "sis_Param.jasper", "@sql" => $sql, "@usuario" => $_SESSION['usuario'], "@titulo" => "Relat�rio de Parametros"));
}

$x = iniciaGrid("edita('%Sistema%','%diretorioarquivos%','%urlarquivos%')",null,"novo()"); 

// Set the query
$x->setQuery("sistema as Sistema, diretorioarquivos, urlarquivos", "sis_param","sistema");

//$rs = execQueryPag($sql);

?>
<h2>Par&acirc;metros</h2>
<br>
<!-- FORMULARIO -->
<?php include "formulario.php";?>
<br>
<!-- LISTAGEM -->
<div id="tabelapadrao" align="left"><?php  $x->printTable(); ?></div>

<?php
  include "../inc/rodape.php";
?>