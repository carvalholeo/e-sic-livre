<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

//retorna erro de uma a��o se houver (em alert do javascript) 
getErro($erro);

?>
<script>
	function edita(sistema, diretorioarquivos, urlarquivos){
		document.getElementById("sistema").value = sistema;
		document.getElementById("diretorioarquivos").value = diretorioarquivos;
		document.getElementById("urlarquivos").value = urlarquivos;
		document.getElementById("acao").value = "Alterar";
		document.getElementById("sistema").focus();
		document.getElementById("sistema").readOnly = true;
	}
	
	function limpaCampos()
	{
		document.getElementById("sistema").value = "";
		document.getElementById("diretorioarquivos").value = "";
		document.getElementById("urlarquivos").value = "";
		document.getElementById("acao").value = "Incluir";
		document.getElementById("sistema").readOnly = false;
		document.getElementById("sistema").focus();	
		
	}
</script>

<form method="post">
<!--input type="hidden" name="sistema" value="<?php echo $sistema;?>" id="sistema"-->
<table class="lista">
  <tr>
    <td>Sistema:</td>
    <td>
		<input type="text" name="sistema" value="<?php echo $sistema;?>" maxlength="8" size="10" id="sistema" />
	</td>
  </tr>
  <tr>
    <td>Diret&oacute;rio Arquivos:</td>
    <td>
		<input type="text" name="diretorioarquivos" value="<?php echo $diretorioarquivos;?>" maxlength="100" size="50" id="diretorioarquivos" />
	</td>
  </tr>
  <tr>
    <td>URL Arquivos:</td>
    <td>
		<input type="text" name="urlarquivos" value="<?php echo $urlarquivos;?>" maxlength="100" size="50" id="urlarquivos" />
	</td>
  </tr>
  <tr>
	<td align="center" colspan="2">
		<input type="submit" value="<?php echo $acao;?>" name="acao" id="acao" />
		<input type="button" value="Limpar" onClick="limpaCampos()" name="limpar" id="limpar" /></td>
  </tr>
</table>
</form>

<script>
	//seta o foco
	document.getElementById("sistema").focus();
</script>