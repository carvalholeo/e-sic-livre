<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

require_once("../inc/security.php");

$idmenu	= $_GET["m"];

if(empty($idmenu))
    $idmenu = $_SESSION['idmenu'];

$_SESSION['idmenu'] = $idmenu;

include("../inc/topo.php"); 

$rs = execQuery("select nome from sis_menu where idmenu = '$idmenu'");
$row = mysql_fetch_array($rs);


?>
<h1><?php echo $row['nome'];?></h1>
<div align="left">
        <?php
        $sql = "select  distinct	
                        t.nome as tela, 
			t.pasta
                from    sis_tela t 
                join    sis_acao a on a.idtela = t.idtela
                join    sis_permissao p on p.idacao = a.idacao
                join    sis_grupo g on g.idgrupo = p.idgrupo
                join    sis_grupousuario gu on gu.idgrupo = g.idgrupo
                where 
                        t.idmenu = $idmenu
                        and t.ativo = 1 
                        and a.status = 'A'
                        and gu.idusuario = ".getSession('uid')."
                order by 
                        t.ordem, t.nome";

        $rs = execQuery($sql);
        $existe=false;
        while($row = mysql_fetch_array($rs)){
                ?><br>- <a href="../<?php echo $row['pasta'];?>"><?php echo $row['tela'];?></a><br><?php
                $existe = true;
        }
        if(!$existe)
        {
            echo "Voc� n�o tem permiss�o de acesso a esta p�gina.";
        }
        ?>
</div>

<?php include("../inc/rodape.php"); ?>
