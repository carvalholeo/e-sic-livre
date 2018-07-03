<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

require_once("../inc/security.php");

$idmenu	= $_GET["m"];

if(empty($idmenu))
    $idmenu = $_SESSION['idmenu'];

$_SESSION['idmenu'] = $idmenu;

include("../inc/topo.php"); 

$result = execQuery("SELECT nome FROM sis_menu WHERE idmenu = '$idmenu'");
$row = mysqli_fetch_array($result);


?>
<h1><?php echo $row['nome'];?></h1>
<div align="left">
        <?php
        $sql = "SELECT DISTINT	
                        t.nome AS tela, 
			t.pasta
                FROM    sis_tela t 
                JOIN    sis_acao a ON a.idtela = t.idtela
                JOIN    sis_permissao p ON p.idacao = a.idacao
                JOIN    sis_grupo g ON g.idgrupo = p.idgrupo
                JOIN    sis_grupousuario gu ON gu.idgrupo = g.idgrupo
                WHERE 
                        t.idmenu = $idmenu
                        AND t.ativo = 1 
                        AND a.status = 'A'
                        AND gu.idusuario = ".getSession('uid')."
                ORDER BY
                        t.ordem, t.nome";

        $result = execQuery($sql);
        $existe=false;
        while($row = mysqli_fetch_array($result)){
                ?><br>- <a href="../<?php echo $row['pasta'];?>"><?php echo $row['tela'];?></a><br><?php
                $existe = true;
        }
        if(!$existe)
        {
            echo "Você não tem permissão de acesso a esta página.";
        }
        ?>
</div>

<?php include("../inc/rodape.php"); ?>
