<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

	include_once("../inc/autenticar.php");

        // Verifica se o grupo foi selecionado
        function estaSelecionado($pGrupo)
        {
            global $gruposselecionados;
            
            foreach ($gruposselecionados as $grupo)
            {
                if($grupo == $pGrupo)
                    return true;
            }
            
            return false;
        }

        // Verifica se o SIC foi selecionado
        function estaSelecionadoSIC($pSic)
        {
            global $sicselecionados;
            
            foreach ($sicselecionados as $sic)
            {
                if($sic == $pSic)
                    return true;
            }
            
            return false;
        }        
        
	// Fun��o de valida��o dos dados do formul�rio do cadastro de usu�rio ----------
	function validaDados()
	{
		global $erro;
		global $acao;
		global $idusuario;
		global $nome;
		global $login;
		global $status;
		global $idsecretaria;
        global $chave;
		global $matricula;
		global $cpfusuario;
        global $gruposselecionados;
                
		
		if (empty($nome))
		{
			$erro = "Nome n�o informado.";
			return false;
		}
		elseif (empty($login))
		{
			$erro = "Login n�o informado.";
			return false;
		}
		elseif (empty($status))
		{
			$erro = "Status n�o informado";
			return false;
		}
		elseif (empty($idsecretaria))
		{
			$erro = "SIC n�o informado";
			return false;
		}
		elseif (empty($cpfusuario) || !isCpf($cpfusuario))
		{
			$erro = "CPF Inv�lido.";
			return false;
		}
                if (empty($gruposselecionados))
		{
			$erro = "Perfil(is) n�o informado(s)";
			return false;
		}
                if (empty($idusuario) and empty($chave))
		{
			$erro = "Senha de acesso precisa ser informada.";
			return false;
		}


		//verifica se ja existe registro cadastrado com a informa�ao passada ---
		
		if ($acao=="Incluir")
			$sql = "SELECT * FROM sis_usuario WHERE cpfusuario = '$cpfusuario'";
		else
			$sql = "SELECT * FROM sis_usuario WHERE cpfusuario = '$cpfusuario' AND idusuario <> $idusuario";
			
		if(mysqli_num_rows(execQuery($sql)) > 0)
		{
			//$erro = "J� existe usuario cadastrado com o login informado";
			$erro = "J� existe usuario cadastrado com o CPF informados";
			return false;
		}

		if ($acao=="Incluir")
			$sql = "SELECT * FROM sis_usuario WHERE login = '$login'";
		else
			$sql = "SELECT * FROM sis_usuario WHERE login = '$login' AND idusuario <> $idusuario";
			
		if(mysqli_num_rows(execQuery($sql)) > 0)
		{
			$erro = "J� existe usuario cadastrado com o login informado";
			return false;
		}

		if ($acao=="Incluir")
			$sql = "SELECT * FROM sis_usuario WHERE matricula = '$matricula'";
		else
			$sql = "SELECT * FROM sis_usuario WHERE matricula = '$matricula' AND idusuario <> $idusuario";
			
		if(mysqli_num_rows(execQuery($sql)) > 0)
		{
			$erro = "J� existe usuario cadastrado com a matricula informada";
			return false;
		}
                
                //-----------------------------------------------------------------------
		
		return true;
	}
	//------------------------------------------------------------------------------------------
	
	$codigo = $_GET["codigo"];
	$acao	= "Incluir";
	
	//se for passado codigo para edi��o e nao tiver sido postado informa��o do formulario busca dados do banco
	if(!$_POST['Alterar'] and !empty($codigo))
	{
		$acao	= "Alterar";
				
		$sql = "SELECT * FROM sis_usuario WHERE idusuario = $codigo";

		$resultado = execQuery($sql);
		$registro  = mysqli_fetch_array($resultado);

		$idusuario      = $registro['idusuario'];
		$nome 		= $registro['nome'];
		$login 		= $registro['login'];
		$status 	= $registro['status'];
		$idsecretaria   = $registro['idsecretaria'];
		$matricula 	= $registro['matricula'];
		$cpfusuario     = $registro['cpfusuario'];
             
                $sql = "SELECT nome FROM sis_grupousuario gu, sis_grupo g 
                        WHERE gu.idgrupo = g.idgrupo 
                              AND gu.idusuario = $codigo";
                
                $i=0;
		$resultado = execQuery($sql);
                while($registro = mysqli_fetch_array($resultado))
                {
                    $gruposselecionados[$i] = $registro['nome'];
                    $i++;
                }	
                
            
                $sql = "SELECT idsecretaria FROM sis_usuariosecretaria WHERE idusuario = $idusuario";

                $i=0;
                $resultado = execQuery($sql);
                while($registro = mysqli_fetch_array($resultado))
                {
                    $sicselecionados[$i] = $registro['idsecretaria'];
                    $i++;
                }	            
                
	}
	else
	{
		//recupera valores do formulario
		
		$idusuario          = $_POST['idusuario'];
		$nome               = $_POST['nome'];
		$login              = $_POST['login'];
		$status             = $_POST['status'];
		$idsecretaria       = $_POST['idsecretaria'];
		$matricula          = $_POST['matricula'];
		$cpfusuario         = $_POST['cpfusuario'];
                $gruposselecionados = $_POST['gruposselecionados'];
                $sicselecionados    = $_POST['sicselecionados'];
                $chave              = $_POST['chave'];
	}
	
	$erro="";

	//se for uma inclusao
	if ($_POST['Incluir'])
	{
		checkPerm("ADDUSR");	
		
		if(validaDados())
		{
                        $con = db_open_trans();
                        $all_query_ok=true;
                        
			$sql = "INSERT INTO sis_usuario(nome, login, cpfusuario, matricula, status, idsecretaria, idusuarioinclusao, datainclusao, chave)
				  VALUES
				  ('$nome','$login','$cpfusuario','$matricula','$status','$idsecretaria', ".getSession('uid').", NOW(), md5('$chave'))";

                        $con->query($sql) ? null : $all_query_ok=false;
                        
                        if ($all_query_ok)
			{
                                //insere usuario no grupo
                                $idusuario = $con->insert_id;
                                
                                foreach ($gruposselecionados as $grupo)
                                {
                                    $sql = "SELECT idgrupo FROM sis_grupo WHERE nome = '$grupo'";
                                    $result = execQuery($sql);
                                    $row = mysqli_fetch_array($result);
                                    $idgrupo = $row['idgrupo'];
                                    
                                    $sql = "INSERT INTO sis_grupousuario (idgrupo, idusuario)
                                            VALUES ('$idgrupo', $idusuario)";
                                        
                                    if (!$con->query($sql)) 
                                    {
                                        $all_query_ok=false;
                                        $erro = "Ocorreu um erro ao inserir usuario no perfil.";
                                        //echo $sql;
                                        break;
                                    }
                                    
                                }

                                //insere usuario no sic
                                foreach ($sicselecionados as $sic)
                                {
                                    $sql = "INSERT INTO sis_usuariosecretaria (idsecretaria, idusuario)
                                            VALUES ($sic, $idusuario)";

                                    if (!$con->query($sql)) 
                                    {
                                        $all_query_ok=false;
                                        $erro = "Ocorreu um erro ao associar SIC do usuario.";
                                        //echo $sql;
                                        break;
                                    }

                                }
                                
                                
			}
			else
			{
				$erro = "Ocorreu um erro ao incluir usuario.";
                                
			}

                        if (!$all_query_ok)
                        {
                            $con->rollback();
                        }
                        else
                        {
                            $con->commit();

                            logger("Adicionou Usuario");  
                            header("Location: index.php");
                        }
                        
                        $con->close();
			
		}
	}
	//se for uma altera��o
	elseif ($_POST['Alterar'])
	{
		$acao	= "Alterar";	
		checkPerm("UPTUSR");	
		
		if(validaDados())
		{
			$sql = "UPDATE sis_usuario SET 
					nome				='$nome', 
					login				='$login',
					matricula			='$matricula',
					cpfusuario			='$cpfusuario',
					status				='$status', 
					idsecretaria		='$idsecretaria',
					idusuarioalteracao	= ".getSession('uid')."
                                        ".(!empty($chave)?",chave = md5('$chave')":"")."
					WHERE idusuario ='$idusuario' ";	

                        $con = db_open_trans();
                        $all_query_ok=true;

                        $con->query($sql) ? null : $all_query_ok=false;

			if ($all_query_ok)
			{
                                //exclui grupos do usuario para posterior inclusao
                                $sql = "DELETE FROM sis_grupousuario WHERE idusuario=$idusuario";
                                
                                $con->query($sql) ? null : $all_query_ok=false;
                                
                                //insere email no alias
                                foreach ($gruposselecionados as $grupo)
                                {
                                    $sql = "SELECT idgrupo FROM sis_grupo WHERE nome = '$grupo'";
                                    $result = execQuery($sql);
                                    $row = mysql_fetch_array($result);
                                    $idgrupo = $row['idgrupo'];
                                    
                                    $sql = "INSERT INTO sis_grupousuario (idgrupo, idusuario)
                                            VALUES ('$idgrupo', $idusuario)";
                                    
                                    if (!$con->query($sql)) 
                                    {
                                        $all_query_ok=false;
                                        $erro = "Ocorreu um erro ao inserir usuario no perfil.";
                                        break;
                                    }
                                    
                                }
                                
                                //exclui sics do usuario para posterior inclusao
                                $sql = "DELETE FROM sis_usuariosecretaria WHERE idusuario=$idusuario";
                                
                                $con->query($sql) ? null : $all_query_ok=false;
                                
                                //insere usuario no sic
                                foreach ($sicselecionados as $sic)
                                {
                                    $sql = "INSERT INTO sis_usuariosecretaria (idsecretaria, idusuario)
                                            VALUES ($sic, $idusuario)";

                                    if (!$con->query($sql)) 
                                    {
                                        $all_query_ok=false;
                                        $erro = "Ocorreu um erro ao associar SIC do usuario.";
                                        //echo $sql;
                                        break;
                                    }

                                }
                                
                                
			}
			else
			{
				$erro = "Ocorreu um erro ao alterar usuario.";
                                //echo $sql;
			}

                        if (!$all_query_ok)
                        {
                            $con->rollback();
                        }
                        else
                        {
                            $con->commit();

                            logger("Alterou Usuario");  
                            header("Location: index.php");
                        }
                        
                        $con->close();                        
		}
	}
?>
