<div class="menu fixed">
    <div class="scrollbar-inner">
        <header>
            <!--img class="img-responsive" src="../assets/dist/images/logo2.png" alt="E-SIC LIVRE "-->
            
			<div class="media text-center">
                <!--figure-->
                    <img src="../assets/dist/images/user-default.png"  width="160" alt="<?= getSession("apelidousuario"); ?>">
                <!--/figure-->
				<h5><?= getSession("apelidousuario"); ?></h5>
                <div class="email"><span class="text-bold">Setor:</span> 
					<script>
						function trocaSic(sic)
						{
							location.href='../index/?sic='+sic;
						}       
					</script>
					<select id="idsecretaria" name="idsecretaria" class="selectpicker" onChange="if (confirm('ATENÇÃO: Esta operação cancelarÁ os trabalhos atuais em aberto.\nConfirma troca de SIC?')){trocaSic(this.value);}">
					
					<?php
                                                                        $sql = "SELECT siglasecretaria, idsecretaria FROM vw_secretariausuario
                                                                                WHERE idusuario = '".getSession('uid')."'";

                                                                        $result = execQuery($sql);
                                                                        
                                                                        while($row = mysqli_fetch_array($result)){
                                                                            ?><option value="<?php echo md5($row['idsecretaria']);?>" <?php echo (getSession('idsecretaria') == $row['idsecretaria'])?"selected":"";?>><?php echo $row['siglasecretaria'];?></option><?php 
                                                                        }?>
						
					</select>
					
				</div>
            </div>
        </header>
        <ul dropdown>
			<li>
				<a href="<?php echo URL_BASE_SISTEMA; ?>index/" class="waves-effect"><i class="material-icons">dashboard</i>Painel Gerencial</a>
			</li>
				<?php echo getMenu(); ?>
            <li>
                <a href="../index/logout.php" class="waves-effect"><i class="material-icons">exit_to_app</i>Sair</a>
            </li>
        </ul>
    </div>
</div>

<?php

function getMenu() {	
	
	$sql = "SELECT  distinct m.nome AS menu, t.nome as tela, t.pasta
				FROM    sis_tela t 
				JOIN    sis_menu m ON m.idmenu = t.idmenu
				JOIN    sis_acao a ON a.idtela = t.idtela
				JOIN    sis_permissao p ON p.idacao = a.idacao
				JOIN    sis_grupo g ON g.idgrupo = p.idgrupo
				JOIN    sis_grupousuario gu ON gu.idgrupo = g.idgrupo
				WHERE 
						t.ativo = 1 
						AND a.status = 'A'
						AND gu.idusuario = 16
				ORDER BY 
						m.ordem, menu, t.nome";

	$result = execQuery($sql);

	$menuTmp = array();	
	$agrupaMenu = "";
	$menu = "";
	
	while($row = mysqli_fetch_array($result)){

		if (empty($agrupaMenu)) {
			$agrupaMenu .= $row['menu'];
		}
	
		if ($agrupaMenu != $row['menu']) {
			$menu .= getMenuA($menuTmp);
			$menuTmp = array();
		}
		
		$agrupaMenu = $row['menu'];
		
		array_push($menuTmp, array($row['menu'], $row['pasta'], $row['tela']));
		
	}
	
	$menu .= getMenuA($menuTmp);
	return $menu;
}


function getMenuA($menuTmp) {
	$menu = "";
	$count =  count($menuTmp);

	if ($count == 1) {
		
		$menu .= '<li><a href="?'.$menuTmp[0][1].'" class="waves-effect"><i class="material-icons">'.$menuTmp[0][3].'</i>' . $menuTmp[0][0] .'</a></li>';
		
	} else if ($count > 1) {
		
		$menu .= '<li class="dropdown"><a href="#" class="waves-effect"><i class="material-icons">'.$menuTmp[0][3].'</i>' . $menuTmp[0][0] .'</a><ul>';
		
		for ($i = 0; $i < $count; $i++) {
			$menu .= '<li><a class="waves-effect" href="?'.$menuTmp[$i][1].'">' . $menuTmp[$i][2] .'</a></li>';
		}
		$menu .= "</ul></li>";
	}
	
	return $menu;
}

																		   
function getSics() {
	
                                                                        $sql = "SELECT siglasecretaria, idsecretaria FROM vw_secretariausuario
                                                                                WHERE idusuario = '".getSession('uid')."'";

                                                                        $result = execQuery($sql);
                                                                        
                                                                        while($row = mysqli_fetch_array($result)){
                                                                           echo "<option value="; echo md5($row['idsecretaria']);  
																		   echo (getSession('idsecretaria') == $row['idsecretaria'])?"selected":">";
																		   echo $row['siglasecretaria']; 
																		   echo "</option>";
                                                                        }
	
	
	
	/*
	$sic = getSession('sic');
	$ret = "";
	$sql = "SELECT siglasecretaria, idsecretaria FROM vw_secretariausuario
			WHERE idusuario = '".getSession('uid')."'";
	
	$rs = execQuery($sql);
	
	//for ($i = 0; $i <= count($sic); $i++) {
	foreach($sic as $rs) {
		$selected =  (md5(getSession('idsecretaria')) == $rs[0])?"selected":"";
		$ret .= "<option value='" . $rs[0] . "' " . $selected . ">" . $rs[1]. "</option>";
	}
	
	return $ret;*/
}
?>
