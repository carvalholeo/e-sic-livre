<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

 include("manutencao.php");
 
 include("../inc/topo.php");
?>
<script language="JavaScript" src="<?php echo SITELNK;?>js/XmlHttpLookup.js"></script>



<h1>Alterar Cadastro</h1>
<br>
<form action="<?php echo SITELNK;?>solicitante/index.php" id="formulario" method="post">
<input type="hidden" name="idsolicitante" value="<?php echo $idsolicitante;?>">
<?php 
//include_once("../cadastro/formulario.php");
  include_once("./formulario.php"); //arquivo copiado para solicitante, para ser modificado sem alterar o cadastro de usuarios de /cadastro
?>                                                 
</form>
<?php 
getErro($erro);
include("../inc/rodape.php");
?>
