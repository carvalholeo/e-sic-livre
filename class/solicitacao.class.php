<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

//se a classe for chamada pela area restrita do lei de acesso
if (empty($varAreaRestrita))
{
	include_once("../inc/security.php");
}

include_once("../inc/funcoes.php");
include_once('../solicitacao/upload.php');


class Solicitacao {
		
	private $idsolicitacao;
	private $textosolicitacao;
	private $formaretorno;
        private $numeroprotocolo;
	private $situacao;
        private $idtiposolicitacao;
        private $instancia;
        private $idsolicitacaoorigem;
	private $idsolicitante;
	private $solicitante;
        private $datasolicitacao;
        private $datarecebimentosolicitacao;
        private $usuariorecebimento;
        private $dataprevisaoresposta;
        private $dataprorrogacao;
        private $usuarioprorrogacao;
        private $motivoprorrogacao;
        private $dataresposta;
        private $resposta;
        private $usuarioresposta;
        private $idsecretariaselecionada;
        private $idsecretariaresposta;
        private $erro;	
        
        //campos de movimenta��o
        private $idsecretariadestino;
        private $despacho;
        //campos de finaliza��o
        private $tiporesposta;
                
	//instancia
	public function getInstancia(){
		return $this->instancia;
	}
        
	//dataprevisaoresposta
	public function getDataPrevisaoResposta(){
		return $this->dataprevisaoresposta;
	}
	public function setDataPrevisaoResposta($valor){
		$this->dataprevisaoresposta = $valor;
	}

	//dataprorrogaca
	public function getDataProrrogacao(){
		return $this->dataprorrogacao;
	}
	public function setDataProrrogacao($valor){
		$this->dataprorrogacao = $valor;
	}

	//motivoprorrogacao
	public function getMotivoProrrogacao(){
		return $this->motivoprorrogacao;
	}
	public function setMotivoProrrogacao($valor){
		$this->motivoprorrogacao = str_replace("'","\'",$valor);
	}
        
	//idsolicitacaoorigem
	public function getUsuarioProrrogacao(){
		return $this->usuarioprorrogacao;
	}
	public function setUsuarioProrrogacao($valor){
		$this->usuarioprorrogacao = $valor;
	}
        
	//dataresposta
	public function getDataResposta(){
		return $this->dataresposta;
	}
	public function setDataResposta($valor){
		$this->dataresposta = $valor;
	}
        
	//resposta
	public function getResposta(){
		return $this->resposta;
	}
	public function setResposta($valor){
		$this->resposta = $valor;
	}
        
	//usuarioresposta
	public function getUsuarioResposta(){
		return $this->usuarioresposta;
	}
	public function setUsuarioResposta($valor){
		$this->usuarioresposta = $valor;
	}
        
	//idsecretariaresposta
	public function getIdSecretariaResposta(){
		return $this->idsecretariaresposta;
	}
	public function setIdSecretariaResposta($valor){
		$this->idsecretariaresposta = $valor;
	}
	

	//usuariorecebimento
	public function getUsuarioRecebimento(){
		return $this->usuariorecebimento;
	}
	public function setUsuarioRecebimento($valor){
		$this->usuariorecebimento = $valor;
	}
        
	//idsolicitacao
	public function getIdSolicitacao(){
		return $this->idsolicitacao;
	}
	
	public function setIdSolicitacao($valor){
		$this->idsolicitacao = $valor;
	}

        //datasolicitacao
	public function getDataSolicitacao(){
		return $this->datasolicitacao;
	}
	public function setDataSolicitacao($valor){
		$this->datasolicitacao = $valor;
	}
        
	//datarecebimentosolicitacao
	public function getDataRecebimentoSolicitacao(){
		return $this->datarecebimentosolicitacao;
	}
	public function setDataRecebimentoSolicitacao($valor){
		$this->datarecebimentosolicitacao = $valor;
	}
        
	//idsolicitacaoorigem
	public function getIdSolicitacaoOrigem(){
		return $this->idsolicitacaoorigem;
	}
	public function setIdSolicitacaoOrigem($valor){
		$this->idsolicitacaoorigem = $valor;
	}

	//idtiposolicitacao
	public function getIdTipoSolicitacao(){
		return $this->idtiposolicitacao;
	}
	public function setIdTipoSolicitacao($valor){
		$this->idtiposolicitacao = $valor;
	}
	//descri��o tipo solicita��o
	public static function getDescricaoTipoSolicitacao($idtiposolicitacao){
            
                $result = execQuery("SELECT nome FROM lda_tiposolicitacao WHERE idtiposolicitacao = $idtiposolicitacao");
		
		if(mysqli_num_rows($result)>0)
		{
                        $row = mysqli_fetch_array($result);
                        return $row["nome"];
                }	
                else
                    return "Tipo de solicita��o n�o encontrado";
                
	}
        
	//textosolicitacao
	public function getTextoSolicitacao(){
		return $this->textosolicitacao;
	}
	public function setTextoSolicitacao($valor){
		$this->textosolicitacao = str_replace("'","\'",$valor);
	}

       	//numprotocolo
	public function getNumeroProtocolo(){
		return $this->numeroprotocolo;
	}
	public function setNumeroProtocolo($numero,$ano){
		$this->numeroprotocolo = $numero."/".$ano;
	}

        //secretaria selecionada (pelo usuario na hora de fazer a solicita��o)
	public function getIdSecretariaSelecionada(){
		return $this->idsecretariaselecionada;
	}
	public function setIdSecretariaSelecionada($valor){
		$this->idsecretariaselecionada = $valor;
	}

        //formaretorno
	public function getFormaRetorno(){
		return $this->formaretorno;
	}
	public function setFormaRetorno($valor){
		$this->formaretorno = $valor;
	}

	//descri��o tipo retorno
	public static function getDescricaoFormaRetorno($formaretorno){
		switch($formaretorno)       
                {
                    case "E": return "E-mail"; break;
                    case "F": return "Fax"; break; 
                    case "C": return "Correio"; break; 
                    default: return "Forma de retorno inexistente";
                }
	}

	//descri��o tipo de instancia
	public static function getDescricaoTipoInstancia($instancia){
		switch($instancia)       
                {
                    case "I": return "Inicial"; break;
                    case "S": return "Seguimento"; break; 
                    case "U": return "�ltima"; break; 
                    default: return "Instancia inexistente";
                }
	}
        
	//situacao
	public function getSituacao(){
		return $this->situacao;
	}
	public function setSituacao($valor){
		$this->situacao = $valor;
	}	
	//descri��o situacao
	public static function getDescricaoSituacao($situacao){
		switch($situacao)       
                {
                    case "A": return "Aberto"; break;
                    case "T": return "Em tramitacao"; break; 
                    case "N": return "Negado"; break; 
                    case "R": return "Solicita��o Respondida"; break;
                    default: return "Situa��o inexistente";
                }
	}
	
	
	//idsolicitante
	public function getIdSolicitante(){
		return $this->idsolicitante;
	}
	public function setIdSolicitante($valor){
		$this->idsolicitante = $valor;
	}
	
	//erro
	public function getErro(){
		return $this->erro;
	}
	

	public function getSolicitante(){
		return $this->solicitante;
	}
	
	
	//--------CONSTRUTOR---------------------------
	
	function Solicitacao($idsolicitacao=null)
	{
		if(!empty($idsolicitacao))
		{
			$this->getDados($idsolicitacao);
		}
	}
	
	//---------------------------------------------
	
	function getDados($idsolicitacao)
	{
		$sql = "SELECT t.*, c.nome AS solicitante, 
                        urec.nome AS usuariorecebimento, upro.nome AS usuarioprorrogacao, 
                        ures.nome AS usuarioresposta, ts.instancia
                        FROM lda_solicitacao t
                        JOIN lda_solicitante c ON c.idsolicitante = t.idsolicitante
                        JOIN lda_tiposolicitacao ts ON ts.idtiposolicitacao = t.idtiposolicitacao
                        LEFT JOIN sis_usuario urec ON urec.idusuario = t.idusuariorecebimento
                        LEFT JOIN sis_usuario upro ON upro.idusuario = t.idusuarioprorrogacao
                        LEFT JOIN sis_usuario ures ON ures.idusuario = t.idusuarioresposta
                        WHERE idsolicitacao = $idsolicitacao";
		
		$result = execQuery($sql);
		
		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_array($result);

			$this->idsolicitacao		= $row["idsolicitacao"];
                        $this->idsolicitacaoorigem      = $row["idsolicitacaoorigem"];
			$this->textosolicitacao 	= $row["textosolicitacao"];
			$this->situacao			= $row["situacao"];
			$this->formaretorno		= $row["formaretorno"];		
			$this->idsolicitante		= $row["idsolicitante"];
			$this->solicitante		= $row["solicitante"];
                        $this->idtiposolicitacao	= $row["idtiposolicitacao"];
                        $this->instancia                = $row["instancia"];
                        $this->setNumeroProtocolo($row["numprotocolo"],$row["anoprotocolo"]);
                        $this->datasolicitacao          = bdToDate($row["datasolicitacao"]);
                        $this->datarecebimentosolicitacao = bdToDate($row["datarecebimentosolicitacao"]);
                        $this->usuariorecebimento         = $row["usuariorecebimento"];
                        $this->dataprevisaoresposta       = bdToDate($row["dataprevisaoresposta"]);
                        $this->dataprorrogacao            = bdToDate($row["dataprorrogacao"]);
                        $this->motivoprorrogacao          = $row["motivoprorrogacao"];
                        $this->usuarioprorrogacao         = $row["usuarioprorrogacao"];
                        $this->dataresposta               = bdToDate($row["dataresposta"]);
                        $this->resposta                   = $row["resposta"];
                        $this->usuarioresposta            = $row["usuarioresposta"];
                        $this->idsecretariaselecionada    = $row["idsecretariaselecionada"];
                        $this->idsecretariaresposta       = $row["idsecretariaresposta"];
                        
			
		}
		else
			die("Solicita��o nao informada");
	}

	public static function getParametrosConfiguracao()
        {
                //recupera parametros de configuracao
                $sql = "SELECT * FROM lda_configuracao";
                $result = execQuery($sql);

                return mysqli_fetch_array($result);

        }


        //recupera a instancia do tipo de solicita��o passado: [I]inicial - [S]eguimento - [U]ltima
       	public static function getInstaciaTipoSolicitacao($idtiposolicitacao)
        {
                //recupera a instancia do tipo de solicita��o
                $sql = "SELECT instancia FROM lda_tiposolicitacao WHERE idtiposolicitacao = $idtiposolicitacao";
                $result = execQuery($sql);

                $row = mysqli_fetch_array($result);
                
                return $row['instancia'];

        }
        
        
        //recupera o proximo tipo de solicita��o para uma solicita��o informada
       	public function getProximoTipoSolicitacao($idsolicitacao="",&$idtiposolicitacao, &$erro="")
        {
            $erro="";
            
            //se for passado uma solicita��o
            if(!empty($idsolicitacao))
            {
                //recupera o proximo tipo de solicitacao
                $sql = "SELECT idtiposolicitacao_seguinte FROM lda_tiposolicitacao
                        WHERE idtiposolicitacao = (SELECT idtiposolicitacao 
                                                    FROM lda_solicitacao 
                                                    WHERE idsolicitacao = $idsolicitacao)";
                $result = execQuery($sql);

                if (mysqli_num_rows($result)>0)
                {
                    $row = mysqli_fetch_array($result);
                    $idtiposolicitacao = $row['idtiposolicitacao_seguinte'];

                    //se n�o for encontrado novo tipo de solicita��o
                    if(empty($idtiposolicitacao))
                    {
                        $erro = "N�o � poss�vel inserir novo recurso para essa solicita��o, pois essa solicita��o j� est� na �ltima inst�ncia.";
                        return false;
                    }
                }
                else
                {
                    $erro = "N�o foi encontrado tipo de solicita��o para essa solicita��o";
                    return false;
                }
                
            }
            else
            {
                //recupera a solicita��o inicial
                $sql = "SELECT idtiposolicitacao FROM lda_tiposolicitacao WHERE instancia = 'I'";
                $result = execQuery($sql);

                if (mysqli_num_rows($result)>0)
                {
                    $row = mysqli_fetch_array($result);
                    $idtiposolicitacao = $row['idtiposolicitacao'];
                }                
                else
                {
                    $erro = "N�o foi encontrado inst�ncia inicial cadastrada no sistema.";
                    return false;
                }
            }

            return true;
        }

        
	function validaDados($instancia="I")
	{
					
                if($instancia != "I")
		{
                        if (empty($this->idsolicitacaoorigem))
                        {
                            $this->erro = "Processo n�o informado.";
                            return false;
                        }
                    
		}
                    
		if (empty($this->textosolicitacao))
		{
			$this->erro = "Especifica��o da solicita��o n�o informada.";
			return false;
		}
		elseif (empty($this->formaretorno))
		{
			$this->erro = "Forma de retorno n�o informado";
			return false;
		}
		elseif (empty($this->idsolicitante))
		{
			$this->erro = "Solicitante n�o informado";
			return false;
		}
		
                if ($this->formaretorno == "C" or $this->formaretorno == "F")
                {
                    $result = execQuery("SELECT logradouro, uf, cidade, telefone, dddtelefone FROM lda_solicitante WHERE idsolicitante = $this->idsolicitante");
                    $row = mysqli_fetch_array($result);
                    
                    //se a forma de retorno for correio, verifica se existe endere�o cadastrado
                    if($this->formaretorno == "C" and (empty($row['logradouro']) or empty($row['uf']) or empty($row['cidade'])))
                    {
                            $this->erro = "Para forma de retorno via correio � necess�rio atualizar o endere�o no seu cadastro.";
                            return false;
                    }
                    //se a forma de retorno for telefone
                    elseif($this->formaretorno == "F" and (empty($row['telefone']) or empty($row['dddtelefone'])))
                    {
                            $this->erro = "Para forma de retorno via correio � necess�rio atualizar o telefone no seu cadastro.";
                            return false;
                    }
                }
                
		//verifica se ja existe registro cadastrado com a informa�ao passada ---
		if (!empty($this->idsolicitacao))
			$sql = "SELECT * FROM lda_solicitacao 
					WHERE textosolicitacao = '$this->textosolicitacao' 
						  AND formaretorno = '$this->formaretorno' 
						  AND idsolicitante = $this->idsolicitante 
						  AND idsolicitacao <> $this->idsolicitacao";
		else
			$sql = "SELECT * FROM lda_solicitacao
					WHERE textosolicitacao = '$this->textosolicitacao' 
						  AND formaretorno = '$this->formaretorno' 
						  AND idsolicitante = $this->idsolicitante ";

				
		if(mysqli_num_rows(execQuery($sql)) > 0)
		{
			$this->erro = "Essa solicita��o j� est� cadastrada.";
			return false;
		}
		//-----------------------------------------------------------------------
		
		return true;
	}

        
        //envia email de aviso de nova solicita��o para o SIC 
        //secretaria -> recebe o idsecretaria ou sigla
        //tipomsg    -> identifica o tipo de mensagem: [M]ovimenta��o - [N]ova solicita��o - [R]ecurso
        public static function enviaEmailSic($secretaria, $tipomsg="M")
        {
                //recupera o email do SIC
                $sql = "SELET emailsic
                        FROM sis_secretaria
                        WHERE sigla = '$secretaria' OR idsecretaria = '$secretaria'";

                $result = execQuery($sql);

                if(mysqli_num_rows($result)>0)
                {
                        $row = mysql_fetch_array($result);
                        $emailsic = $row["emailsic"];
                }

                //se houver email cadastrado, faz o envio
                if(!empty($emailsic))
                {
                    //se for movimenta��o
                    if($tipomsg == "M")
                    {
                        $titulo = "Movimenta��o de solicita��o de informa��o";
                        $body="Prezado(a) colaborador(a),<br> <br>
                                Foi movimentada uma solicita��o de informa��o para o seu �rg�o. Favor verificar a demanda no sistema ".SISTEMA_NOME." no endere�o: <a href='".URL_BASE_SISTEMA."'>".URL_BASE_SISTEMA."</a><br><br>
                                Mensagem Autom�tica do Sistema ".SISTEMA_NOME;
                    }
                    //se for nova solicita��o
                    else if($tipomsg == "N")
                    {
                        $titulo = "Nova solicita��o de informa��o - ";
                        $body="Prezado(a) colaborador(a),<br> <br>
                                Foi aberta uma solicita��o de informa��o. Favor verificar a demanda no sistema ".SISTEMA_NOME." no endere�o: ".URL_BASE_SISTEMA."<br><br>
                                Mensagem Autom�tica do Sistema ".SISTEMA_NOME;
								
						/*$titulo = "Nova de solicita��o de informa��o - ".$demanda->getNumeroProtocolo()."";
						$body	= "Prezado(a) colaborador(a),<br> <br> 
							Foi aberta uma solicita��o de informa��o com n�mero de protocolo <b> ".$demanda->getNumeroProtocolo()."</b>, com a seguinte descri��o: <br>"
							. '"' .$demanda->getTextoSolicitacao(). '"' . "<br><br>
							Favor verificar a solicita��o e dar encaminahmentos necess�rios atrav�s do <a href='".URL_BASE_SISTEMA."'>".URL_BASE_SISTEMA."</a>.<br>
							Mensagem Autom�tica do Sistema ".SISTEMA_NOME.".";*/
                    }
                    //se for novo recurso
                    else if($tipomsg == "R")
                    {
                        $titulo = "Novo recurso de solicita��o de informa��o";
                        $body="Prezado(a) colaborador(a),<br> <br>
                                Foi aberta uma solicita��o de informa��o. Favor verificar a demanda no sistema ".SISTEMA_NOME." no endere�o: <a href='".URL_BASE_SISTEMA."'>".URL_BASE_SISTEMA."</a><br><br>
                                Mensagem Autom�tica do Sistema ".SISTEMA_NOME;
                    }
                    else
                    {
                        $titulo = "Solicita��o de informa��o";
                        $body="Prezado(a) colaborador(a),<br> <br>
                                Foi aberta uma solicita��o de informa��o. Favor verificar a demanda no sistema ".SISTEMA_NOME." no endere�o: <a href='".URL_BASE_SISTEMA."'>".URL_BASE_SISTEMA."</a><br><br>
                                Mensagem Autom�tica do Sistema ".SISTEMA_NOME;
                    }
                    
                    if (!sendmail($emailsic,$titulo,$body))
                    {   
                            //caso de erro loga o erro
                            logger("Lei de acesso - N�o foi poss�vel enviar e-mail para o SIC: $emailsic");
                    }
                }
            
        }
        
        //envia email de aviso para o solicitante
        //secretaria -> recebe o idsecretaria ou sigla
        //tipomsg    -> identifica o tipo de mensagem: [N]ova solicita��o - [P]rorrogada - [R]esposta da solicita��o
        public static function enviaEmailSolicitante($idsolicitacao, $tipomsg="N")
        {
                //recupera o email do solicitante
                $sql = "SELECT pes.email, pes.nome, sol.dataprevisaoresposta, sol.numprotocolo, sol.anoprotocolo
                        FROM lda_solicitacao sol, lda_solicitante pes
                        WHERE sol.idsolicitante = pes.idsolicitante
                              AND sol.idsolicitacao = $idsolicitacao";

                $result = execQuery($sql);

                if(mysqli_num_rows($result)>0)
                {
                        $row = mysqli_fetch_array($result);
                        $email = $row["email"];
                        $nome =  $row["nome"];
                        $processo = $row["numprotocolo"]."/".$row["anoprotocolo"];
                        $dataprevisaoresposta = bdToDate($row['dataprevisaoresposta']);
                        
                }

                //se houver email cadastrado, faz o envio
                if(!empty($email))
                {
                    //se for nova solicita��o
                    if($tipomsg == "N")
                    {
                        $titulo = "Solicita��o cadastrada";
                        $body="Prezado(a) $nome,<br> <br>
                                Sua solicita��o <b>$processo</b> foi cadastrada com sucesso.<br><br>
                                Data prevista para resposta: $dataprevisaoresposta
                                <br><br>Para acompanhar o andamento, acesse o sistema ".SISTEMA_NOME." no endere�o: <a href='".SITELNK."'>".SITELNK."</a>.<br><br>
                                Mensagem Autom�tica do Sistema ".SISTEMA_NOME;
                    }
                    //se for resposta
                    else if($tipomsg == "R")
                    {
                        $titulo = "Sua solicita��o foi respondida";
                        $body="Prezado(a) $nome,<br> <br>
                                Sua solicita��o <b>$processo</b> foi respondida. Para mais informa��es acesse o sistema ".SISTEMA_NOME." no endere�o: <a href='".SITELNK."'>".SITELNK."</a>.<br><br>
                                Mensagem Autom�tica do Sistema ".SISTEMA_NOME;
                    }
                    //se for prorrogada
                    else if($tipomsg == "P")
                    {
                        $titulo = "A resposta a sua solicita��o foi prorrogada";
                        $body="Prezado(a) $nome,<br> <br>
                                O atendimento a sua solicita��o <b>$processo</b> foi prorrogado, data de previs�o de resposta: $dataprevisaoresposta.<br><br>
								Para mais informa��es acesse o sistema ".SISTEMA_NOME.".<br><br>
                                Mensagem Autom�tica do Sistema ".SISTEMA_NOME;
                    }
                    
                    if (!sendmail($email,$titulo,$body))
                    {   
                            //caso de erro loga o erro
                            logger("Lei de acesso - N�o foi poss�vel enviar e-mail para o solicitante: $email");
                    }
                }
            
        }

        
	public function cadastra()
	{
		if($this->validaDados())
		{
						
                        $configuracao = $this->getParametrosConfiguracao();
                        $prazoresposta = $configuracao['prazoresposta'];
                        
                        //recupera o proximo tipo de solicita��o, caso retorne falso, deu erro
                        if(!$this->getProximoTipoSolicitacao("",$idtiposolicitacao,$this->erro))
                                return false;
                        
			$sql="INSERT INTO lda_solicitacao (
						textosolicitacao,
						formaretorno,
						idsolicitante,
                                                datasolicitacao,
                                                dataprevisaoresposta,
                                                idtiposolicitacao,
                                                idsecretariaselecionada
				) VALUES (
						'$this->textosolicitacao',
						'$this->formaretorno',
						'$this->idsolicitante',
                                                NOW(),
                                                date_add(NOW(), interval $prazoresposta DAY ),
                                                $idtiposolicitacao,
                                                ".(!empty($this->idsecretariaselecionada)?$this->idsecretariaselecionada:"null")."
				)";
				
			$con = db_open();
			
			//verificacao de upload
			global $upload;
			$upload = verificadados();
						
			if ($upload == 1){
				return false;
			}
			//-----------------------
			
			if (!$con->query($sql)) 
			{
                            
				$this->erro = "Erro ao inserir solicita��o".$sql;
				$sucesso = false;
			}
			else
			{
				$this->idsolicitacao = $con->insert_id;

                                //recupera o numero do protocolo
                                $sql = "SELECT numprotocolo, anoprotocolo
                                        FROM lda_solicitacao t
                                        WHERE idsolicitacao = $this->idsolicitacao";

                                $result = execQuery($sql);

                                if(mysqli_num_rows($result)>0)
                                {
                                    $row = mysqli_fetch_array($result);
                                    $this->setNumeroProtocolo($row["numprotocolo"],$row["anoprotocolo"]);
                                }

                                //se tiver sido selecionado um SIC
                                if(!empty($this->idsecretariaselecionada))
                                {
                                    //envia email de aviso de nova solicita��o ao SIC centralizador
                                    Solicitacao::enviaEmailSic($rec['idsecretaria'],"N");
                                }
                                else
                                {
                                    //envia email para os SIC's centralizadores
                                    $sql = "SELECT idsecretaria
                                            FROM sis_secretaria
                                            WHERE siccentral = 1";

                                    $result = execQuery($sql);

                                    if(mysqli_num_rows($result)>0)
                                    {    
                                        while($rec = mysqli_fetch_array($result))
                                        {
                                            //envia email de aviso de nova solicita��o ao SIC centralizador
                                            Solicitacao::enviaEmailSic($rec['idsecretaria'],"N");

                                        }
                                    }
                                }
                                
                                //envia email de aviso de cadastro de solicita��o ao solicitante
                                Solicitacao::enviaEmailSolicitante($this->idsolicitacao,"N");
                                
                                
				$this->erro = "";
				$sucesso = true;
			
			}
			//db_close($con);
			
			return $sucesso;
		}
		else
			return false;
	}

        
        
	public function cadastraRecurso($idtiposolicitacao)
	{
		if($this->validaDados($tiposolicitacao))
		{
                        $configuracao = Solicitacao::getParametrosConfiguracao();
                        $prazoresposta = $configuracao['prazorespostarecurso'];
                        
			$sql="INSERT INTO lda_solicitacao (
						textosolicitacao,
						formaretorno,
						idsolicitante,
                                                datasolicitacao,
                                                dataprevisaoresposta,
                                                idtiposolicitacao,
                                                idsolicitacaoorigem,
                                                idsecretariaselecionada
				) VALUES (
						'$this->textosolicitacao',
						'$this->formaretorno',
						'$this->idsolicitante',
                                                NOW(),
                                                date_add(NOW(), interval $prazoresposta DAY ),
                                                '$idtiposolicitacao',
                                                $this->idsolicitacaoorigem,
                                                ".(!empty($this->idsecretariaselecionada)?$this->idsecretariaselecionada:"null")."
				)";
				
			$con = db_open();
			
			if (!$con->query($sql)) 
			{
                            
				$this->erro = "Erro ao inserir solicita��o".$sql;
				$sucesso = false;
			}
			else
			{
                            
				$this->erro = "";
				$sucesso = true;
                                
                                //se tiver sido selecionado um SIC
                                if(!empty($this->idsecretariaselecionada))
                                {
                                    //envia email de aviso de nova solicita��o ao SIC centralizador
                                    Solicitacao::enviaEmailSic($rec['idsecretaria'],"N");
                                }
                                else
                                {
                                    //envia email para os SIC's centralizadores
                                    $sql = "SELECT idsecretaria
                                            FROM sis_secretaria
                                            WHERE siccentral = 1";

                                    $result = execQuery($sql);

                                    if(mysqli_num_rows($result)>0)
                                    {    
                                        while($rec = mysqli_fetch_array($result))
                                        {
                                            //envia email de aviso de nova solicita��o ao SIC centralizador
                                            Solicitacao::enviaEmailSic($rec['idsecretaria'],"N");

                                        }
                                    }
                                }
                                
                                //envia email de aviso de cadastro de solicita��o ao solicitante
                                Solicitacao::enviaEmailSolicitante($this->idsolicitacaoorigem,"N");
                                
            
			}
			//db_close($con);
			
			return $sucesso;
		}
		else
			return false;
	}
	
        static public function movimenta($idsolicitacao, $secretariadestino, $despacho, $arquivo)
        {
                if(empty($secretariadestino))
                {
                    return "O campo secretaria de destino deve ser preenchido";
                }
                if(empty($despacho))
                {
                    return "O campo despacho deve ser preenchido";
                }
                
                //verifica se existe alguma movimentacao
                $sql="SELECT COUNT(*) AS tot FROM lda_movimentacao WHERE idsolicitacao = $idsolicitacao";
                
                $row = mysqli_fetch_array(execQuery($sql));
                //se existir movimenta��o
                if($row["tot"] > 0)
                {
                    //nao permite movimentar se a ultima movimenta��o n�o tiver sido dado o recebimento
                    $sql="SELECT COUNT(*) FROM lda_movimentacao 
                        WHERE idmovimentacao = (SELECT MAX(idmovimentacao) FROM lda_movimentacao WHERE idsolicitacao = $idsolicitacao)
                              AND datarecebimento IS null";
                    
                    $row = mysqli_fetch_array(execQuery($sql));
                    
                    //se ultima movimenta��o nao tiver sido recebida
                    if($row["tot"] > 0)
                        return "N�o � poss�vel movimentar solicita��o que ainda n�o foi recebida!";
                    
                }

                //recupera o status da demanda
                $sql="SELECT situacao FROM lda_solicitacao WHERE idsolicitacao = $idsolicitacao";
                $row = mysqli_fetch_array(execQuery($sql));
                $status = $row["situacao"];

                
                $con = db_open_trans();
                $all_query_ok=true;

		$sql="INSERT INTO lda_movimentacao 
                        (
                         idsolicitacao,
                         idsecretariaorigem,
                         idsecretariadestino,
                         dataenvio,
                         idusuarioenvio,
                         despacho
                        ) VALUES (
                         $idsolicitacao,
                         ".getSession("idsecretaria").",
                         $secretariadestino,
                         NOW(),
                         ".getSession("uid").",
                         '".str_replace("'","\'",$despacho)."'
                        )
                    ";

		if (!$con->query($sql)) 
                {
                    $con->rollback();
                    return "Erro na movimenta��o da solicita��o.";
                }
                else
                {
                    //se houver arquivo faz upload
                    if(!empty($arquivo["tmp_name"]))
                    {
                        $idmovimentacao = $con->insert_id;
                        $dir = getDiretorio("lda")."/";
                        $ext = getExtensaoArquivo($arquivo['name']);

                        $fullArquivo = "lda_".$idsolicitacao."_mov_".$idmovimentacao.".".$ext;

                        if (!@move_uploaded_file($arquivo["tmp_name"], $dir.$fullArquivo))
                        {
                                $erro = "Ocorreu um erro ao efetuar o upload do arquivo ".$dir.$fullArquivo."; nome:".$arquivo["tmp_name"];
                                $all_query_ok=false;
                        }
                        else
                        {
                                $sql = "UPDATE lda_movimentacao SET arquivo = '$fullArquivo' WHERE idmovimentacao = $idmovimentacao";
                                if (!$con->query($sql))
                                {
                                        $erro = "Ocorreu um erro ao efetuar atualizar nome do arquivo";
                                        $all_query_ok=false;
                                }
                        }
                    }
                    
                    //se o status da demanda for "aberto" altera para "em tramita��o"
                    if($status == "A")
                    {
                        $sql = "UPDATE lda_solicitacao SET situacao = 'T' WHERE idsolicitacao=$idsolicitacao";
                        if (!$con->query($sql)) 
                        {
                            $con->rollback();
                            return "Ocorreu um erro ao atualizar a situa��o da solicita��o";
                        }
                    }
                    $con->commit();
                    
                    //envia email de aviso de nova solicita��o ao SIC de destino
                    Solicitacao::enviaEmailSic($secretariadestino,"M");
                    
                }
                return "";
        }

        static public function recebe($idsolicitacao)
        {
                $sql = "SELECT situacao FROM lda_solicitacao WHERE idsolicitacao = $idsolicitacao";
                $row = mysqli_fetch_array(execQuery($sql));
                $situacao = $row['situacao'];
                
                //se a situa��o for aberta (n�o houve tramita��o), da o recebimento inicial da solicita��o
                if ($situacao == "A")
                {
                    $sql="UPDATE lda_solicitacao SET
                            datarecebimentosolicitacao = NOW(),
                            idusuariorecebimento = ".getSession("uid")."
                          WHERE idsolicitacao = $idsolicitacao";

                    if (!execQuery($sql)) 
                        //die($sql);
                        return "Erro no recebimento da solicita��o";

                }
                else
                {
                
                    //verifica se j� houve recebimento
                    $sql="SELECT COUNT(*) AS tot FROM lda_movimentacao 
                        WHERE idmovimentacao = (SELECT MAX(idmovimentacao) FROM lda_movimentacao WHERE idsolicitacao = $idsolicitacao)
                                AND datarecebimento IS null";

                    $row = mysqli_fetch_array(execQuery($sql));

                    //se a ultima movimenta��o nao tiver sido recebida, executa o recebimento
                    if($row["tot"] > 0)
                    {    

                        //verifica se o usuario � da unidade de destino
                        $sql="SELECT COUNT(*) AS tot FROM lda_movimentacao 
                            WHERE idmovimentacao = (SELECT MAX(idmovimentacao) FROM lda_movimentacao WHERE idsolicitacao = $idsolicitacao)
                                AND idsecretariadestino = ".getSession("idsecretaria");

                        $row = mysqli_fetch_array(execQuery($sql));

                        //se o ususario pertencer a unidade de destino, da o recebimento
                        if($row["tot"] > 0)
                        {    
                            $sql="UPDATE lda_movimentacao SET
                                    datarecebimento = NOW(),
                                    idusuariorecebimento = ".getSession("uid")."
                                  WHERE idsolicitacao = $idsolicitacao
                                      AND datarecebimento IS null";

                            if (!execQuery($sql)) 					   
                                return "Erro no recebimento da solicita��o";

                        }
                        else
                        {
                            return "Usu�rio n�o pertence a secretaria de destino";
                        }


                    }
                }
                
                return "";
        }

        //retorna se existe sic centralizador
        public static function existeSicCentralizador(){
            $sql = "SELECT COUNT(*) AS tot FROM sis_secretaria WHERE ativado = 1 AND siccentral = 1";
            $result = execQuery($sql);
            $row = mysqli_fetch_array($result);
            return ($row['tot']>0);
        }
        
        //retorna consulta de movimenta��es da solicita��o
        public static function getMovimentacao($idsolicitacao)
        {
            
            $sql = "SELECT m.*, sOri.sigla AS origem, sDes.sigla AS destino, usrDes.nome AS usuariorecebimento, usrOri.nome AS usuarioenvio
                    FROM lda_movimentacao m
                    JOIN sis_secretaria sOri ON sOri.idsecretaria = m.idsecretariaorigem
                    JOIN sis_secretaria sDes ON sDes.idsecretaria = m.idsecretariadestino
                    JOIN sis_usuario usrOri ON usrOri.idusuario = m.idusuarioenvio
                    LEFT JOIN sis_usuario usrDes ON usrDes.idusuario = m.idusuariorecebimento
                    WHERE idsolicitacao=$idsolicitacao ";
            
            $sql .= " ORDER BY idmovimentacao"; //ordena
            
            return execQuery($sql);
        }

        //retorna consulta com os recursos da solicita��o
        public static function getRecursos($idsolicitacao)
        {
            
            $sql = "SELECT sol.*, tip.nome AS tiposolicitacao
                    FROM lda_solicitacao sol, lda_tiposolicitacao tip
                    WHERE sol.idtiposolicitacao = tip.idtiposolicitacao
                            AND sol.idsolicitacaoorigem=$idsolicitacao 
                     ORDER BY sol.idsolicitacao";
            
            return execQuery($sql);
        }

        //retorna se ainda cabe recurso para a solicita��o
        public static function getPodeRecurso($idsolicitacao, $idsolicitacaoorigem)
        {
            //recupera o id do tipo de solicita��o seguinte a atual
            $sql = "SELECT idtiposolicitacao_seguinte 
                    FROM lda_tiposolicitacao 
                    WHERE idtiposolicitacao = (SELECT idtiposolicitacao 
                                               FROM lda_solicitacao 
                                               WHERE idsolicitacao= $idsolicitacao)";
            
            $result = execQuery($sql);
            $row = mysqli_fetch_array($result);
            $idtiposolicitacaoseguinte = $row['idtiposolicitacao_seguinte'];
            
            //se n�o houver solicita��o seguinte, � a de ultima instancia, portanto n�o cabe mais recurso
            if(empty($idtiposolicitacaoseguinte))
                return false;
            else
            {
                //verifica se existe recurso pedido para a solicita��o passada
                $sql = "SELECT *
                        FROM lda_solicitacao
                        WHERE idsolicitacaoorigem = $idsolicitacaoorigem 
                            AND idtiposolicitacao = $idtiposolicitacaoseguinte
                        ";
                
                //se nao encontrar recurso, pode solicitar
                return (mysqli_num_rows(execQuery($sql))==0);
            }
            
        }
        
	static public function finaliza($idsolicitacao,$tiporesposta,$resposta, $arquivos)
	{
                if(empty($tiporesposta))
                {
                    return "O campo Tipo de Resposta deve ser preenchido";
                }

                if(empty($resposta))
                {
                    return "O campo Observa��o deve ser preenchido";
                }
                
                
		$sql="UPDATE lda_solicitacao SET 
			situacao = '$tiporesposta',
                        idusuarioresposta = ".getSession("uid").",
                        dataresposta = NOW(),
                        resposta = '".str_replace("'","\'",$resposta)."',
                        idsecretariaresposta = ".getSession("idsecretaria")."
                      WHERE idsolicitacao = '$idsolicitacao'";

                $con = db_open_trans();
                $all_query_ok=true;
                
                $erro="";
                if (!$con->query($sql))
                {
                    $erro = "Ocorreu um erro ao atualizar solicita��o";
                    $all_query_ok = false;
                }
                else
                {
                    if(!empty($arquivos))
                    {
                            $numeroCampos = 3; //numero de campos de arquivo
                            $dir = getDiretorio("lda")."/";

                            for($i=0;$i<$numeroCampos;$i++) 
                            {

                                    if(!empty($arquivos["tmp_name"][$i]))
                                    {
                                            //insere arquivos
                                            $sql="INSERT INTO lda_anexo (
                                                                    idsolicitacao,
                                                                    nome,
                                                                    idusuarioinclusao,
                                                                    datainclusao
                                                            ) VALUES (
                                                                    '$idsolicitacao',
                                                                    null,
                                                                    ".getSession("uid").",
                                                                    NOW()
                                                            )";

                                            if ($con->query($sql))
                                            {
                                                    $idarquivo = $con->insert_id;
                                                    $ext = getExtensaoArquivo($arquivos["name"][$i]);
		
                                                    $fullArquivo = "lda_".$idsolicitacao."_file_".$idarquivo.".".$ext;

                                                    if (!@move_uploaded_file($arquivos["tmp_name"][$i], $dir.$fullArquivo))
                                                    {
                                                            $erro = "Ocorreu um erro ao efetuar o upload do arquivo ".$dir.$fullArquivo."; nome:".$arquivos["tmp_name"][$i];
                                                            $all_query_ok=false;
                                                            break;
                                                    }
                                                    else
                                                    {
                                                            $sql = "UPDATE lda_anexo SET nome = '$fullArquivo' WHERE idanexo = $idarquivo";
                                                            if (!$con->query($sql))
                                                            {
                                                                    $erro = "Ocorreu um erro ao efetuar atualizar nome do arquivo";
                                                                    $all_query_ok=false;
                                                                    break;
                                                            }
                                                    }
                                            }
                                            else
                                            {
                                                    $erro = "Erro ao inserir arquivo";
                                                    $all_query_ok = false;					
                                                    break;
                                            }		
                                    }
                            }
                    }
                }
                
		if (!$all_query_ok) 	
                {
                    $con->rollback();
                    return $erro;
                }
                else
                {
                    $con->commit();
                    
                    //envia email de aviso de cadastro de solicita��o ao solicitante
                    Solicitacao::enviaEmailSolicitante($idsolicitacao,"R");

                    return "";
                }
                    
                
		
	}

	static public function prorrogar($idsolicitacao,$motivo)
	{
                if(empty($motivo))
                {
                    return "O campo motivo deve ser preenchido";
                }
                
                $configuracao = Solicitacao::getParametrosConfiguracao();
                
                
                $sql = "SELECT t.instancia FROM lda_solicitacao s, lda_tiposolicitacao t
                        WHERE s.idtiposolicitacao = t.idtiposolicitacao
                               AND s.idsolicitacao = $idsolicitacao";
                $result = execQuery($sql);
                $row = mysqli_fetch_array($result);
                
                //se n�o for prorroga��o de primeira instancia
                if($row['instancia'] != "I")
                    $prazoresposta = $configuracao['qtdeprorrogacaorecurso'];
                else
                    $prazoresposta = $configuracao['qtdprorrogacaoresposta'];
                        
                
                $sql="UPDATE lda_solicitacao SET 
                        idusuarioprorrogacao = ".getSession("uid").",
                        dataprorrogacao = NOW(),
                        motivoprorrogacao = '".str_replace("'","\'",$motivo)."',
                        dataprevisaoresposta = date_add(dataprevisaoresposta, interval $prazoresposta DAY )
                      WHERE idsolicitacao = '$idsolicitacao'";

		if (!execQuery($sql)) 		
                {
                    return "Erro na prorroga��o da solicita��o";
                }
                else
                {
                    //envia email de aviso de cadastro de solicita��o ao solicitante
                    Solicitacao::enviaEmailSolicitante($idsolicitacao,"P");

                }
                
		return "";
	}
        
                      
} //fecha a classe