<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

include "manutencao.php";
include "../inc/topo.php";
include "../inc/paginacaoIni.php";	


$filtro = "";


if(!empty($nome)) $filtro .= " and nome like '%$nome%' ";
if(!empty($instancia)) $filtro .= " and instancia = '$instancia'";


$sql = "select t.*, prox.nome as proxima
        from lda_tiposolicitacao t
        left join lda_tiposolicitacao prox on prox.idtiposolicitacao = t.idtiposolicitacao_seguinte
	where 1=1 $filtro order by nome";

$rs = execQueryPag($sql);

?>
<div class="container-fluid">
    <header class="header-title">
	<h1>Cadastro de Inst&acirc;ncias</h1>
	<ol class="breadcrumb">
            <li><a href="<?php echo URL_BASE_SISTEMA; ?>index/">Dashboard</a></li>
            <li><a href="<?php echo URL_BASE_SISTEMA; ?>index/?lda_configuracao">Administra��o</a></li>
            <li class="active">Cadastro de Inst�ncias</li>
	</header>
<br>
<!-- FORMULARIO -->
<?php include "formulario.php";?>
<br>
<!-- FORMULARIO -->
<div class="alert alert-warning">
Observa&ccedil;&otilde;es:
<br>- Dever&aacute; ter cadastrado pelo menos um tipo de inst&acirc;ncia inicial e uma &uacute;ltima.
<br>- Inst&acirc;ncias cadastradas como &uacute;ltima n&atilde;o permitem associar uma inst&acirc;ncia seguinte.
<br>- Inst&acirc;ncias cadastradas como inicial n&atilde;o s&atilde;o listadas para servir como inst&acirc;ncia seguinte.
<br>
</div>

<!-- LISTAGEM -->
<div class="container-fluid">
    <div class="boll-table">
      <table class="table">
		<thead>
		  <tr>
			<th></th>
			<th>Codigo</th>
			<th>Nome</th>
			<th>Tipo de Inst&acirc;ncia</th>
			<th>Pr&oacute;xima Inst&acirc;ncia</th>
		  </tr>
		 </thead>
  <?php
  $cor = false;
  while($registro = mysql_fetch_array($rs)){
	$click = "edita('".$registro["idtiposolicitacao"]."','".$registro["nome"]."','".$registro["instancia"]."')";
        
        if($cor)
            $corLinha = "#dddddd";
        else
            $corLinha = "#ffffff";
        $cor = !$cor;

	?>
	<tr onMouseOver="this.style.backgroundColor = getCorSelecao(true);" onMouseOut="this.style.backgroundColor = '<?php echo $corLinha;?>';" style="background-color:<?php echo $corLinha;?>;cursor:pointer; cursor:hand; ">
		<td><img src="../img/drop.png" title="Excluir Registro" onClick="apagar('<?php echo $registro["idtiposolicitacao"]; ?>','lda_tiposolicitacao');"/></td>
		<td align="left" onClick="<?php echo $click;?>"><?php echo $registro["idtiposolicitacao"]; ?></td>
		<td align="left" onClick="<?php echo $click;?>"><?php echo $registro["nome"]; ?></td>
		<td onClick="<?php echo $click;?>"><?php echo Solicitacao::getDescricaoTipoInstancia($registro["instancia"]); ?></td>
		<td align="left">
                    <span id="show_<?php echo $registro["idtiposolicitacao"]; ?>">
                        <?php echo !empty($registro["proxima"])?$registro["proxima"]:"Nenhum"; ?> 
                        <?php if($registro['instancia']!="U"){ //se nao for a ultima instancia, permite cadastrar outras como proxima ?>
                            <a href="javascript: abreordenacao('<?php echo $registro["idtiposolicitacao"]; ?>');">[Alterar]</a>
                        <?php }?>
                    </span>
                    <span id="edit_<?php echo $registro["idtiposolicitacao"]; ?>" style="display:none">
                        <select name="proximainsntancia" id="proximainstancia" onchange="ordena(<?php echo $registro['idtiposolicitacao'];?>,this.value)">
                            <option value="-1">Nenhum</option>
                            <?php 
                                //seleciona as instancias que n�o seja de inicio e que n�o esteja sendo utilizada por outro tipo de solicita��o
                                $qry = "select * from lda_tiposolicitacao t
                                        where instancia != 'I' 
                                              and idtiposolicitacao != ".$registro['idtiposolicitacao']."
                                              and not exists(select p.idtiposolicitacao 
                                                             from lda_tiposolicitacao p 
                                                             where p.idtiposolicitacao_seguinte = t.idtiposolicitacao
                                                                   and p.idtiposolicitacao != ".$registro['idtiposolicitacao'].")";
                                echo $qry;
                                $rsInst = execQuery($qry);
                                while($rowInst = mysql_fetch_array($rsInst)){
                                    ?><option value="<?php echo $rowInst['idtiposolicitacao'];?>" <?php echo ($registro['idtiposolicitacao_seguinte']==$rowInst['idtiposolicitacao'])?"selected":"";?>><?php echo $rowInst['nome'];?></option><?php
                                }
                            ?>
                        </select>
                        <a href="javascript: cancelaordenacao('<?php echo $registro["idtiposolicitacao"]; ?>');">[Cancelar]</a>
                        
                    </span>
		</td>			
	</tr>
	<?php 
  } 
  
  ?>
		</table>
	</div>
</div>


<?php
  include "../inc/rodape.php";
?>