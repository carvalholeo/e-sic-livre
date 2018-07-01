<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

include("../inc/autenticar.php");
include_once("../class/solicitacao.class.php");
include("../inc/topo.php");
include("../inc/paginacaoPorPostIni.php");
     
$filtro = "";

$numprotocolo   = $_REQUEST["fltnumprotocolo"];
$idsolicitante  = getSession("uid");
$situacao       = $_REQUEST["fltsituacao"];

$parametrosIndex = "fltnumprotocolo=$numprotocolo&fltsituacao=$situacao"; //parametros a ser passado para a pagina de detalhamento, fazendo com que ao voltar para o index traga as informa��es passadas anteriormente

if (!empty($numprotocolo)) $filtro.= " and concat(sol.numprotocolo,'/',sol.anoprotocolo) = '$numprotocolo'";
if (!empty($situacao)) $filtro.= " and sol.situacao = '$situacao'";
    


//seleciona as solicita��es n�o respondidas e sua ultima movimenta��o (recupera variaveis de configuracao de prazos)
/*
 * Quando a situa��o for A ou T, trata da primeira tramita��o do processo. 
 */
$sql = "select sol.*, tip.nome as tiposolicitacao
        from lda_solicitacao sol, lda_tiposolicitacao tip
        where tip.idtiposolicitacao = sol.idtiposolicitacao
            and sol.idsolicitante = $idsolicitante
            $filtro 
        order by sol.anoprotocolo, sol.numprotocolo, sol.idsolicitacao";

/*if ($_REQUEST['imprimir']) {
    generateReport(array("!PATH" => "ouv_CategoriaProblema.jasper", "@sql" => $sql, "@usuario" => $_SESSION['usuario'], "@titulo" => "Listagem das Categorias dos Problemas"));
}*/

$rs = execQueryPag($sql);

?>
<h1>Consulta de Solicita��es Realizadas</h1>
<br><br>
<form action="<?php echo SITELNK;?>/acompanhamento/index.php" method="post" id="formulario">
<input type="hidden" name="pagina" id="pagina" value="<?php echo $pagina?>">
<fieldset style="width: 50%;">
<legend>Buscar:</legend>
    <table align="center">
        <tr>
            <td nowrap>N� do Protocolo (numero/ano):</td>
            <td><input type="text" name="fltnumprotocolo" id="fltnumprotocolo" value="<?php echo $numprotocolo; ?>" maxlength="50" size="30" /></td>
        </tr>
        <tr>
			<td>Situa��o:</td>
			<td><select name="fltsituacao" id="fltsituacao">
			  <option value="" <?php echo empty($situacao)?"selected":""; ?>>--Todos--</option>
			  <option value="A" <?php echo $situacao=="A"?"selected":""; ?>>Aberto</option>
			  <option value="T" <?php echo $situacao=="T"?"selected":""; ?>>Em tramita��o</option>
			  <option value="N" <?php echo $situacao=="N"?"selected":""; ?>>Negado</option>
			  <option value="R" <?php echo $situacao=="R"?"selected":""; ?>>Respondido</option>
		    </select></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <input type="submit" class="botaoformulario" value="Buscar" name="acao" />
                <input type="button" class="botaoformulario" value="Limpar" name="limpar" onclick="document.getElementById('fltnumprotocolo').value='';document.getElementById('fltsituacao').value='';" />
            </td>
        </tr>
    </table>
</fieldset>		

<br>
<table class="tabLista" style="width: 100%">
    <tr>
        <th>Protocolo</th>
        <th>Tipo de Solicita��o</th>
        <th>Data Solicita��o</th>
        <th>Previs�o Resposta</th>
        <th>Prorrogado?</th>
        <th>Situa��o</th>
        <th>Data Resposta</th>
    </tr>
    <?php
    $cor=false;
    while ($registro = mysql_fetch_array($rs)) {
            $click = "editar('".$registro["idsolicitacao"]."&$parametrosIndex','".SITELNK."acompanhamento/cadastro');";
            
            if($cor)
                $corLinha = "#dddddd";
            else
                $corLinha = "#ffffff";
            $cor = !$cor;
            ?>
            <tr onMouseOver="this.style.backgroundColor = getCorSelecao(true);" onMouseOut="this.style.backgroundColor = '<?php echo $corLinha;?>';" style="background-color:<?php echo $corLinha;?>;cursor:pointer; cursor:hand; ">
                <td onClick="<?php echo $click; ?>"><?php echo $registro["numprotocolo"]."/".$registro["anoprotocolo"]; ?></td>
                <td onClick="<?php echo $click; ?>"><?php echo $registro["tiposolicitacao"]; ?></td>
                <td onClick="<?php echo $click; ?>"><?php echo bdToDate($registro["datasolicitacao"]); ?></td>
                <td onClick="<?php echo $click; ?>"><?php echo bdToDate($registro["dataprevisaoresposta"]); ?></td>
                <td onClick="<?php echo $click; ?>"><?php echo (!empty($registro["dataprorrogacao"]))?"Sim":"N�o"; ?></td>
                <td onClick="<?php echo $click; ?>"><?php echo Solicitacao::getDescricaoSituacao($registro["situacao"]); ?></td>
                <td onClick="<?php echo $click; ?>"><?php echo (!empty($registro["dataresposta"]))?bdToDate($registro["dataresposta"]):"-"; ?></td>
            </tr>
            <?php 
    } ?>

    <tr>
        <td align="right" colspan="12">
            <?php include("../inc/paginacaoPorPostFim.php");?>
        </td>
    </tr>
</table>


</form>
<?php
	include "../inc/rodape.php";
?>
