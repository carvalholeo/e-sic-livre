<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informaзгo baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa й software livre; vocк pode redistribuн-lo e/ou
 modificб-lo sob os termos da Licenзa GPL2.
***********************************************************************************/

	include_once("../inc/autenticar.php");
	checkPerm("INSGRU");
	
        // Verifica se o grupo foi selecionado
        function estaSelecionado($pLogin)
        {
            global $selecionados;
            
            foreach ($selecionados as $login)
            {
                if($login == $pLogin)
                    return true;
            }
            
            return false;
        }        
        
	//funзгo de validaзгo dos dados do formulario do cadastro de usuario -------------------
	function validaDados()
	{
		global $erro;
		global $acao;
		global $selecionados;
		global $idgrupo;
				
		return true;
	}
	
	//------------------------------------------------------------------------------------------
	$acao	= "Salvar";
	$erro	= "";
	
	//se tiver sido postado informaзгo do formulario
	if ($_POST['acao'])
	{
	
		//recupera valores do formulario
		$acao		= $_POST["acao"];
		$idgrupo	= $_POST["idgrupo"];
		$nomegrupo	= $_POST["nomegrupo"];
		$selecionados   = $_POST["selecionados"];
		
		

                if(validaDados())
                {
                
                        $con = db_open_trans();
                        $all_query_ok=true;
                        
                        $sql = "delete from sis_grupousuario where idgrupo = $idgrupo";
                        
                        if (!$con->query($sql)) 
                        {
                            $all_query_ok=false;
                            $erro = "Ocorreu um erro ao excluir usuario do grupo.";
                        }
                        else
                        {
                            foreach ($selecionados as $usr)
                            {
                                $sql = "select idusuario from sis_usuario where login = '$usr'";
                                $rs = execQuery($sql);
                                $row = mysql_fetch_array($rs);
                                $idusuario = $row['idusuario'];

                                $sql = "insert into sis_grupousuario (idgrupo, idusuario)
                                        values ('$idgrupo', $idusuario)";

                                if (!$con->query($sql)) 
                                {
                                    $all_query_ok=false;
                                    $erro = "Ocorreu um erro ao inserir usuario no grupo.";
                                    //echo $sql;
                                    break;
                                }

                            }
                        }

                        if (!$all_query_ok)
                        {
                            $con->rollback();
                        }
                        else
                        {
                            $con->commit();

                            logger("Adicionou Usuario a grupo");  
                            header("Location: index.php");
                        }
                        
                        $con->close();
			                    
                }
	}
        else
        {
            $idgrupo = $_GET["idgrupo"];
            $sql = "select nome from sis_grupo where idgrupo = $idgrupo";
            $result = execQuery($sql);
            $row = mysql_fetch_array($result);
            
            $nomegrupo = $row["nome"];
            
            $sql = "select login from sis_grupousuario gu, sis_usuario u
                        where gu.idusuario = u.idusuario and gu.idgrupo = $idgrupo";

            $i=0;
            $resultado = execQuery($sql);
            while($registro = mysql_fetch_array($resultado))
            {
                $selecionados[$i] = $registro['login'];
                $i++;
            }	            
            
        }

?>