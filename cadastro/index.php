<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

 require_once("manutencao.php");
 require_once("../inc/topo.php");
?>

<div align="center">
<h1>Cadastro do Solicitante</h1>
</div>
<br>
<?php if(!empty($_GET['r'])){?>
          Cadastro Realizado com sucesso! <br><br>
          Em instantes voc� receber� uma solicita��o de confirma��o do cadastro no seu e-mail: <?php echo $_GET['r']?>.<br>
<?php }else{?>
        <form action="<?php echo SITELNK;?>cadastro/index.php" id="formulario" method="post">
        <?php include_once("../cadastro/formulario.php");?>
        </form>
        <?php 
        getErro($erro);
}
include("../inc/rodape.php"); 
?>
