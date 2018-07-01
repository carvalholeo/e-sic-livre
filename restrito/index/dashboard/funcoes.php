<?php 

/*
	Retorna total de demandas por status
	$status : filtro por status. Exemplo: "'A', 'R'"
	$origem : 1-E-Sic; 2-Ouvidoria; 3-Eu Inspetor
	$opc : 1 = total (sintetico); 2 = total por sistema
*/
function getSolTotal($status = "", $origem = "", $opc = 1, $sicCentral_) {
		
	if ($opc == 1) {
		$sqlTmp	= "select count(*) as total ";
		$group	= "";
	}
	else if ($opc == 2){
		$sqlTmp	= "select count(*) as total, origem, situacao ";
		$group	= " GROUP BY origem, situacao ";	
	}
	else if ($opc == 3){
		$sqlTmp	= "select origem, month(datasolicitacao) as mesSol, month(dataresposta) as mesRes ";
		$group	= "";
	}
	
	$filtro = "";
	
	//Se nao for um sic central, limita a visualizacao ao sic logado e seus vinculados
	if ($sicCentral_ == "0") {		
		
		$idSicUsuario 	= getSession('idsecretaria');		//Sic logado
		$sicsUsuarios 	= getSession('sic');				//Todos os sics do usuario
		$sicVinculado 	= $sicsUsuarios[$idSicUsuario][3];	//Sic(s) vinculado(s) ao sic logado
		$sicVincTmp		= "";
		
		//Considera na query todos os sics vinculados ao sic logado. 
		//Ou seja, o Sic "pai" visualiza demandas de todos os sics "filhos".
		for ($i = 0; $i < count($sicVinculado); $i++) {
			if ($i > 0) 
				$sicVincTmp .= ",";
			$sicVincTmp .= "" . $sicVinculado[$i][0] . "";
		}
		
		if (empty($sicVincTmp)) 
			$filtro .= " and (mov.idsecretariadestino = $idSicUsuario or mov.idsecretariaorigem = $idSicUsuario) ";
		else
			$filtro .= " and (mov.idsecretariadestino IN ($idSicUsuario, $sicVincTmp) or mov.idsecretariaorigem IN ($idSicUsuario, $sicVincTmp) ";
		
	}
	//Critica status 
	if (!empty($status))
		$filtro .= " and situacao IN ($status) ";
	
	//Critica o sistema de origem da demanda
	if (!empty($origem))
		$filtro .= " and origem = $origem ";
	
	$sql = $sqlTmp .
			"from 
				lda_solicitacao sol			
			left join 
				lda_movimentacao mov on mov.idmovimentacao = (select max(m.idmovimentacao) from lda_movimentacao m where m.idsolicitacao = sol.idsolicitacao)
			left join 
				sis_secretaria secDestino on secDestino.idsecretaria = mov.idsecretariadestino				
			where 
				1=1
				$filtro
			$group";

	$rs = execQuery($sql);	

	if ($opc == 1) {
		$ret = mysql_fetch_assoc($rs);
		return $ret;
	} else
		return $rs;
}


/*
	Retorna resumo de demandas por sistema e situacao formatado em uma table
*/
function getSolResSis($rs) {
	
	$ret = "";
	$resumo = array();
		
	while ($reg = mysql_fetch_array($rs)) {	
		
		$situacao = $reg["situacao"];									
		$total = $reg["total"];
		$origem = $reg["origem"];
		
		$situacaoLink = "'" . $reg["situacao"] . "'";
		$link = ' <a class="btn btn-info waves-effect" href="?lda_consulta&fltorigem='.$reg["origem"].'">Ver</a>';
		
		$resumo[$origem]['link'] = $link;
		$resumo[$origem][$situacao] = $resumo[$origem][$situacao] + $total;
		$resumo[$origem]['total'] = $resumo[$origem]['total'] + $total;
	}	
	
	$resumo[1]['A'] = ($resumo[1]['A']) ?: 0;
	$resumo[1]['T'] = ($resumo[1]['T']) ?: 0;
	$resumo[1]['R'] = ($resumo[1]['R']) ?: 0;
	$resumo[1]['N'] = ($resumo[1]['N']) ?: 0;
	$resumo[1]['total'] = ($resumo[1]['total']) ?: 0;	
	
	$resumo[2]['A'] = ($resumo[2]['A']) ?: 0;
	$resumo[2]['T'] = ($resumo[2]['T']) ?: 0;
	$resumo[2]['R'] = ($resumo[2]['R']) ?: 0;
	$resumo[2]['N'] = ($resumo[2]['N']) ?: 0;
	$resumo[2]['total'] = ($resumo[2]['total']) ?: 0;
	
	$resumo[3]['A'] = ($resumo[3]['A']) ?: 0;
	$resumo[3]['T'] = ($resumo[3]['T']) ?: 0;
	$resumo[3]['R'] = ($resumo[3]['R']) ?: 0;
	$resumo[3]['N'] = ($resumo[3]['N']) ?: 0;	
	$resumo[3]['total'] = ($resumo[3]['total']) ?: 0;
	
	$resumo[0]['total'] = $resumo[1]['total'] + $resumo[2]['total'] + $resumo[3]['total'];
	
	return $resumo;
}


function formatSolSis($resumo) {
	$ret .= "<tr>
		<td>".getOrigem(1)."</td>
		<td class='text-center'>".$resumo[1]['A']."</td>
		<td class='text-center'>".$resumo[1]['T']."</td>
		<td class='text-center'>".$resumo[1]['R']."</td>
		<td class='text-center'>".$resumo[1]['N']."</td>
		<td class='text-center'>".$resumo[1]['total']."</td>
		<td class='text-center'>".$resumo[1]['link']."</td>												
	</tr>";
	
	$ret .= "<tr>
		<td>".getOrigem(2)."</td>
		<td class='text-center'>".$resumo[2]['A']."</td>
		<td class='text-center'>".$resumo[2]['T']."</td>
		<td class='text-center'>".$resumo[2]['R']."</td>
		<td class='text-center'>".$resumo[2]['N']."</td>
		<td class='text-center'>".$resumo[2]['total']."</td>
		<td class='text-center'>".$resumo[2]['link']."</td>												
	</tr>";								
	
	$ret .= "<tr>
		<td>".getOrigem(3)."</td>
		<td class='text-center'>".$resumo[3]['A']."</td>
		<td class='text-center'>".$resumo[3]['T']."</td>
		<td class='text-center'>".$resumo[3]['R']."</td>
		<td class='text-center'>".$resumo[3]['N']."</td>
		<td class='text-center'>".$resumo[3]['total']."</td>
		<td class='text-center'>".$resumo[3]['link']."</td>												
	</tr>";	
	
	return $ret;			
}


/*
	Retorna total de demandas por Diretoria e Origem
*/
function getSolResDir() {
	$sql = "select                               
				sec.idsecretaria, 
				sec.nome as nome,
				sol.situacao as situacao,
				count(sec.nome) as total,
				sol.origem as origem

			from 
				lda_solicitacao sol
			
			left join lda_movimentacao mov on mov.idmovimentacao = (select max(m.idmovimentacao) from lda_movimentacao m where m.idsolicitacao = sol.idsolicitacao)						
			left join sis_secretaria sec on sec.idsecretaria = mov.idsecretariadestino
			
			where  
				sol.situacao not in('R','N')
		
			GROUP BY
				sec.idsecretaria,
				sec.nome, 
				sol.situacao,
				sol.origem
			ORDER BY 
				sol.origem, 
				sec.nome;
			";
						
	$rs = execQuery($sql);	
	$ret = "";
	
	while ($reg = mysql_fetch_array($rs)) {	
		
		if (empty($reg["nome"])) 
			$nome = 'Ouvidoria TCE-RN';
		else
			$nome = $reg["nome"];
		
		$situacao = getStatus($reg["situacao"]);
		$situacaoLink = "'" . $reg["situacao"] . "'";
		$total = $reg["total"];
		$origem = getOrigem($reg["origem"]);
		$link = '<a class="btn btn-info waves-effect" href="?lda_consulta&fltsecdestino='.$reg["idsecretaria"].'&fltsituacao='.$situacaoLink.'">Ver</a>';
		
		if ($total > 0) {
			$ret .= "<tr>
						<td>$nome</td>
						<td>$situacao</td>
						<td>$total</td>
						<td>$origem</td>
						<td class='text-center'>$link</td>
					</tr>";
		}				
	}
	
	return $ret;
}

function getDemandas($filtro_ = "", $limit = 0) {

	$idSicUsuario 	= getSession('idsecretaria');	//Sic logado
	$sicsUsuarios 	= getSession('sic');				//Todos os sics do usuario
	$sicCentral_ 	= $sicsUsuarios[$idSicUsuario][2];				
	
	$qryLimit	= "";
	
	//Se nao for um sic central, limita a visualizacao ao sic logado e seus vinculados
	if ($sicCentral_ == "0") {		
		
		
		$sicVinculado 	= $sicsUsuarios[$idSicUsuario][3];	//Sic(s) vinculado(s) ao sic logado
		$sicVincTmp		= "";
		
		//Considera na query todos os sics vinculados ao sic logado. 
		//Ou seja, o Sic "pai" visualiza demandas de todos os sics "filhos".
		for ($i = 0; $i < count($sicVinculado); $i++) {
			if ($i > 0) 
				$sicVincTmp .= ",";
			$sicVincTmp .= "" . $sicVinculado[$i][0] . "";
		}
		
		if (empty($sicVincTmp)) 
			$filtro_ .= " and (mov.idsecretariadestino = $idSicUsuario or mov.idsecretariaorigem = $idSicUsuario) ";
		else
			$filtro_ .= " and (mov.idsecretariadestino IN ($idSicUsuario, $sicVincTmp) or mov.idsecretariaorigem IN ($idSicUsuario, $sicVincTmp) ";
	}	
	
	if ($limit > 0)
		$qryLimit = "LIMIT $limit";
	
	
	$sql = "select sol.*, 
               pes.nome as solicitante,
               ifnull(secOrigem.sigla,'Solicitante') as secretariaorigem, 
               ifnull(secDestino.sigla,'SIC Central') as secretariadestino, 
               mov.idsecretariadestino,
               mov.datarecebimento,
               mov.idmovimentacao,
               c.*,
               DATEDIFF(sol.dataprevisaoresposta, NOW()) as prazorestante,
               tip.nome as tiposolicitacao

        from lda_solicitacao sol
        join lda_tiposolicitacao tip on tip.idtiposolicitacao = sol.idtiposolicitacao
        join lda_solicitante pes on pes.idsolicitante = sol.idsolicitante
        left join lda_movimentacao mov on mov.idmovimentacao = (select max(m.idmovimentacao) from lda_movimentacao m where m.idsolicitacao = sol.idsolicitacao)
        left join sis_secretaria secOrigem on secOrigem.idsecretaria = mov.idsecretariaorigem
        left join sis_secretaria secDestino on secDestino.idsecretaria = mov.idsecretariadestino
        join lda_configuracao c
        where  1=1
            $filtro_ 
		ORDER BY sol.idsolicitacao DESC
		$qryLimit";
	//print($sql);
	if ($limit > 0 )
		$rs__ = execQuery($sql);
	else
		$rs__ = execQueryPag($sql);
	
	return $rs__;	
}


/*
	Retorna quantidade de demandas por mes
*/
function getDemandaMes($sicCentral_) {

	$rs_ = getSolTotal("", "", 3, $sicCentral_); // total, origem, mes, situacao
	$total;

	while ($reg = mysql_fetch_array($rs_)) {
		
		$somaAberta 	= 0;
		$somaRespondido	= 0;
			
		$origem = $reg["origem"];
		$mesSol	= $reg["mesSol"];
		$mesRes	= $reg["mesRes"];
				
		if (!empty($reg["mesSol"]))
			$somaAberta = 1; 
		if (!empty($reg["mesRes"]))
			$somaRespondido = 1;
		
		$total[$origem][$mesSol]['A'] = $total[$origem][$mesSol]['A'] + $somaAberta;
		$total[$origem][$mesRes]['R'] = $total[$origem][$mesRes]['R'] + $somaRespondido;

	}
	
	return $total;
}


function getStatus($status) {
	if ($status == "A")
		return "Aberto";
	else if ($status == "T")
		return "Tramita��o";
	else if ($status == "R")
		return "Respondido";	
	else if ($status == "N")
		return "Negado";
}

function getOrigem($status) {
	if ($status == 1)
		return "E-Sic";
	else if ($status == 2)
		return "Ouvidoria";
	else if ($status == 3)
		return "Eu Inspetor";
	else
		return "N�o classificado";
}


/*
	getEnquete - retorna a pesquisa de satisfa��o do retorno da demanda
*/
function getEnquete() {
	
	$comentarios= [];
	$totais 	= [];	
	
	$sql = "SELECT 
				* 
			FROM 
				lda_enquete 
			";
	
	$rs = execQuery($sql);
	
	while ($reg = mysql_fetch_array($rs)) {
		
		if ($reg['resposta']  == 'U')
			$resposta = 'Ruim';
		else if ($reg['resposta']  == 'R')
			$resposta = 'Regular';
		else if ($reg['resposta']  == 'B')
			$resposta = 'Bom';
		else if ($reg['resposta']  == 'O')
			$resposta = '�tima';
		
		$total++;
		$totais[$reg['resposta']] = $totais[$reg['resposta']] + 1;
		
		if (!empty($reg['comentario']))
			array_push($comentarios, array($reg['dataresposta'], $reg['comentario']));
	}
	
	return [$totais, $total, $comentarios];
}


/*
	getFiltroVinculo - retorna o filtro de v�nculo do usu�rio para query 
*/
function getFiltroVinculo() {
	
	$idSicUsuario 	= getSession('idsecretaria');		//Sic logado
	$sicsUsuarios 	= getSession('sic');				//Todos os sics do usuario
	$sicCentral_ 	= $sicsUsuarios[$idSicUsuario][2];				
	$filtroVinc 	= "";
	
	//Se nao for um sic central, limita a visualizacao ao sic logado e seus vinculados
	if ($sicCentral_ == "0") {		
				
		$sicVinculado 	= $sicsUsuarios[$idSicUsuario][3];	//Sic(s) vinculado(s) ao sic logado
		$sicVincTmp		= "";
		
		//Considera na query todos os sics vinculados ao sic logado. 
		//Ou seja, o Sic "pai" visualiza demandas de todos os sics "filhos".
		for ($i = 0; $i < count($sicVinculado); $i++) {
			if ($i > 0) 
				$sicVincTmp .= ",";
			$sicVincTmp .= "" . $sicVinculado[$i][0] . "";
		}
		
		if (empty($sicVincTmp)) 
			$filtroVinc = " and (mov.idsecretariadestino = $idSicUsuario or mov.idsecretariaorigem = $idSicUsuario) ";
		else
			$filtroVinc = " and (mov.idsecretariadestino IN ($idSicUsuario, $sicVincTmp) or mov.idsecretariaorigem IN ($idSicUsuario, $sicVincTmp) ";
	}	
		
	return $filtroVinc;
}

?>
