<?php 


require("funcoes.php");

$sic = getSession('sic');
$sicCentral = $sic[getSession('idsecretaria')][2];

//echo getSolTotal("'A'","",1,$sicCentral);

$solAbertas = getSolTotal("'A'","",1,$sicCentral)['total'];
$solTramita = getSolTotal("'T'","",1,$sicCentral)['total'];
$solResp	= getSolTotal("'R'","",1,$sicCentral)['total'];
$solNegado	= getSolTotal("'N'","",1,$sicCentral)['total'];

$rs_ = getSolTotal("", "", 2, $sicCentral);
$resumoSis = getSolResSis($rs_);

?>
<div class="mesa-eletronica">
	<div class="container-fluid">
	    <header class="header-title">
	        <h1>Painel Gerencial</h1>
	        <ol class="breadcrumb">
	            <li class="active">In�cio</li>            
	        </ol>
	    </header>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="card icon energized" onclick="notify()">
					<a href="?lda_consulta&fltsituacao='A','T'" class="waves-effect">
						<div class="media">
							<div class="media-left"><i class="material-icons">feedback</i></div>
							<div class="media-body">
								Solicita��es n�o respondidas
								<div class="number" title="Solicita��es abertas e em tramita��o"><span class="count"><?= $solAbertas; ?></span></div>					
							</div>				
						</div>	
					</a>		
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="card icon positive">
					<a href="?lda_consulta&fltsituacao='T'" class="waves-effect">
						<div class="media">
							<div class="media-left"><i class="material-icons">compare_arrows</i></div>
							<div class="media-body">
								Em tramita��o
								<div class="number" title="Solicita��es em tramita��o"><span class="count"><?= $solTramita; ?></span></div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="card icon balanced">
					<a href="?lda_consulta&fltsituacao='R'" class="waves-effect">
						<div class="media">
							<div class="media-left"><i class="material-icons">check_circle</i></div>
							<div class="media-body">
								Solicita��es respondidas
								<div class="number" title="Solicita��es respondidas"><span class="count"><?= $solResp; ?></span></div>
							</div>
						</div>
					</a>
				</div>
			</div>			
			<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				<div class="card icon assertive">
					<a href="?lda_consulta&fltsituacao='N'" class="waves-effect">
						<div class="media">
							<div class="media-left"><i class="material-icons">block</i></div>
							<div class="media-body">
								Negado
								<div class="number" title="Solicita��es negadas"><span class="count"><?= $solNegado;  ?></span></div>
							</div>
						</div>
					</a>
				</div>
			</div>	
		</div>
		<!--INICIO Demanda por sistema, TALVES REMOVER -->
		<!--div class="row m-b">  
			<div class="col-md-4 col-xs-12">
				<div class="box">
					<header>Demanda por sistema</header>
					 <?php 
						$esicPerc 	= round(($resumoSis[1]['total']*100)/$resumoSis[0]['total']); 
						$ouviPerc 	= round(($resumoSis[2]['total']*100)/$resumoSis[0]['total']);
						$euinPerc 	= round(($resumoSis[3]['total']*100)/$resumoSis[0]['total']);
					 ?>
					<div class="caption">
						<div class="block">
							<div class="title">E-sic</div>
							<div class="progress progress-animated">
							  <div class="progress-bar" role="progressbar" aria-valuenow="<?= $esicPerc; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $esicPerc; ?>%;">
							    <?= $esicPerc; ?>%
							  </div>
							</div>
						</div>
						<div class="block">
							<div class="title">Ouvidoria</div>
							<div class="progress progress-animated">
							  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $ouviPerc; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $ouviPerc; ?>%;">
							    <?= $ouviPerc; ?>%
							  </div>
							</div>
						</div>
						<div class="block">
							<div class="title">Eu Inspetor</div>
							<div class="progress progress-animated">
							  <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $euinPerc; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $euinPerc; ?>%;">
							    <?= $euinPerc; ?>%
							  </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-8 col-xs-12">
				<div class="boll-table">
					<table class="table">
						<thead>
							<tr>
								<th>Sistema</th>
								<th class="text-center">
									<i class="material-icons energized">feedback</i>
								</th>
								<th class="text-center">
									<i class="material-icons positive">compare_arrows</i>
								<th class="text-center">
									<i class="material-icons balanced">check_circle</i>
								<th class="text-center">
									<i class="material-icons assertive">block</i>
								</th>
								<th class="text-center">
									Total
								</th>								
								<th></th>
							</tr>
						</thead>
							<?php 
								echo formatSolSis($resumoSis);
							?>			
					</table>
				</div>
			</div>
		</div--> <!--FIM FUNCAO Demanda por sistema-->
		<?php 
			if (getSession("sic")[getSession("idsecretaria")][2] > 0) { 
				$enquete 	= getEnquete();
				$totais		= $enquete[0];
				$total		= $enquete[1];
				$comments	= $enquete[2];
		?>
		<div class="row m-b">
			<div class="col-md-12">
				<h4 class="subtitle">�ndice de satisfa��o</h4>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="boll-table">
					<table class="table">
						 <thead>
							<tr>
								<th>Satisfa��o</th>
								<th>%</th>
							</tr>
						</thead>
						<tr>
							<td>Ruim</td>
							<td><?= (($totais['U']*100)/$total)?:0;?></td>
						</tr>
						<tr>
							<td>Regular</td>
							<td><?= (($totais['R']*100)/$total)?:0;?></td>
						</tr>
						<tr>
							<td>Bom</td>
							<td><?= (($totais['B']*100)/$total)?:0;?></td>
						</tr>
						<tr>
							<td>�timo</td>
							<td><?= (($totais['O']*100)/$total)?:0;?></td>
						</tr>
					</table>
				</div>
				<br>Total de apura��es: <?= $total; ?>
			</div>
			<div class="col-md-8 col-xs-12">
				<div class="boll-table">
					<table class="table">
						<thead>
							<tr>
								<th>Data</th>
								<th>Coment�rios</th>
							</tr>
						</thead>
						<?php
							$count = count($comments);
							for ($i = 0; $i < $count && $i < 5; $i++) {
						?>
							<tr>
								<td><?= bdToDate($comments[$i][0]); ?></td>
								<td><?= $comments[$i][1]; ?></td>
							</tr>
							<?php }?>
					</table>
				</div>
			</div>
		</div>			
		<?php } ?>		
		<?php if (getSession("sic")[getSession("idsecretaria")][2] > 0) { ?>
		<div class="row m-b">
			<div class="col-md-12">
				<h4 class="subtitle">Demandas por diretorias</h4>
				<div class="boll-table">
					<table class="table">
						 <thead>
							<tr>
								<th>Diretoria</th>
								<th>Situa��o</th>		
								<th>Total</th>				
								<th>Origem</th>			
								<th></th>							
							</tr>
						</thead>
					
						<?php echo getSolResDir(); ?>
					
					</table>
				</div>
			</div>
		</div>			
		<?php } ?>
		<!--DEMANDA POR MES TALVEZ REMOVER-->
		<!--div class="row m-b">
			<div class="col-md-12">
				<h4 class="subtitle">Demandas por m�s</h4>
				<div class="boll-table">
					<table class="table">
						<thead>
							<tr>
								<th rowspan="2">Sistema</th>							
							<?php 
							for ($i = 0; $i < 6 ; $i++) {
							?>								
								<th colspan="2"><center><?= date('M', strtotime('-'.$i.' month')); ?></center></th>
							<?php } ?>
							</tr>
							<tr>
								<th style="font-size: 8pt; text-align: center;">Aberto</th>
								<th style="font-size: 8pt; text-align: center;">Encerrado</th>
								<th style="font-size: 8pt; text-align: center;">Aberto</th>
								<th style="font-size: 8pt; text-align: center;">Encerrado</th>
								<th style="font-size: 8pt; text-align: center;">Aberto</th>
								<th style="font-size: 8pt; text-align: center;">Encerrado</th>
								<th style="font-size: 8pt; text-align: center;">Aberto</th>
								<th style="font-size: 8pt; text-align: center;">Encerrado</th>
								<th style="font-size: 8pt; text-align: center;">Aberto</th>
								<th style="font-size: 8pt; text-align: center;">Encerrado</th>
								<th style="font-size: 8pt; text-align: center;">Aberto</th>
								<th style="font-size: 8pt; text-align: center;">Encerrado</th>							
							</tr>
							</span>
						</thead>
						<?php
						//Demadnas por m�s
						$demandasMes = getDemandaMes($sicCentral);
						for ($iSis = 1; $iSis < 4; $iSis++) {							
						?> 
							<tr style="cursor:pointer;">
								<td onClick="<?php echo $clickMovimento; ?>"><?= getOrigem($iSis); ?></td>
							<?php
							$month = date('n');
							for ($i = 1; $i <= 6; $i++) {

								$clickMovimentoA = "?lda_consulta&fltAbeRes=A&fltorigem=".$iSis."&fltmonth=".$month."";
								$clickMovimentoR = "?lda_consulta&fltAbeRes=R&fltorigem=".$iSis."&fltmonth=".$month."";
								
							?>
							
								<td style="text-align: center;"><a href="<?php echo $clickMovimentoA; ?>"><?= ($demandasMes[$iSis][$month]['A'])?:0; ?></a></td>
								<td style="text-align: center;"><a href="<?php echo $clickMovimentoR; ?>"><?= ($demandasMes[$iSis][$month]['R'])?:0; ?></a></td>
							
						<?php $month = date('n', strtotime('-'.$i.' month')); }	?>
							</tr>
							
						<?php }?>					
					</table>
				</div>
			</div>
		</div--> <!--FIM FUN��O DEMANDA POR MES, TALVEZ REMOVER -->
		<div class="row m-b">
			<div class="col-md-12">
				<h4 class="subtitle">�ltimas demandas cadastradas</h4>
				<div class="boll-table">
					<table class="table">
						<thead>
							<tr>
								<th class="none-print"></th>
								<th>Protocolo</th>
								<th>Tipo</th>
								<th>Data</th>
								<th width="150">Solicitante</th>
								<th>Data Envio</th>
								<th class="text-center">Origem</th>
								<th class="text-center">Destino</th>
								<th class="text-center">Prazo Restante</th>
								<th>Previs�o Resposta</th>
								<th width="100">Prorrogado</th>
								<th>Situa��o</th>
							</tr>
						</thead>
						<?php
						$rs_ = getDemandas("",6);
						while ($registro = mysql_fetch_array($rs_)) {
							$corLinha = "#fff";
								
							//se foi respondida marca verde
							if( (!empty($registro['dataresposta'])) ){
								$corLinha = "#00FF00";	
								$registro['prazorestante'] =0;
							}
							//se tiver passado do prazo de resposta	sem ter sido respondida				
							elseif ($registro['prazorestante'] < 0 and (empty($registro['dataresposta']))) {
								$corLinha = "#ef4e3a"; //vermelho - Urgente! Passou do prazo de resolu��o
							}
							//se faltar entre 1 e 5 dias para expirar o prazo de resposta
							elseif ($registro['prazorestante'] >= 0 and $registro['prazorestante'] <= 5) {
								$corLinha = "#f0b840"; //amarelo - Alerta! Est� perto de expirar
							}
							
							//$clickMovimento = $confirmacao . "editar('" . $registro["idsolicitacao"] . "&$parametrosIndex','../lda_solicitacao/visualizar');";
							$clickMovimento = "javascript:document.location='?lda_solicitacao&p=visualizar&codigo=".$registro['idsolicitacao']."';";
							?>
							<tr style="cursor:pointer;" onMouseOver="this.style.backgroundColor = getCorSelecao(true);" onMouseOut="this.style.backgroundColor = getCorSelecao(false);" >
								<td class="prazo"><span style="background-color: <?=$corLinha?>;"></span></td>
								<td onClick="<?php echo $clickMovimento; ?>"><?php echo $registro["numprotocolo"] . "/" . $registro["anoprotocolo"]; ?></td>
								<td onClick="<?php echo $clickMovimento; ?>"><?php echo $registro["tiposolicitacao"]; ?></td>
								<td onClick="<?php echo $clickMovimento; ?>"><?php echo bdToDate($registro["datasolicitacao"]); ?></td>
								<td onClick="<?php echo $clickMovimento; ?>"><?php echo $registro["solicitante"]; ?></td>
								<td onClick="<?php echo $clickMovimento; ?>"><?php echo bdToDate(!empty($registro["dataenvio"]) ? $registro["dataenvio"] : $registro["datasolicitacao"]); ?></td>                
								<td class="text-center" onClick="<?php echo $clickMovimento; ?>"><?php echo strtoupper($registro["secretariaorigem"]); ?></td>
								<td class="text-center" onClick="<?php echo $clickMovimento; ?>"><?php echo strtoupper($registro["secretariadestino"]); ?></td>
								<td class="text-center" onClick="<?php echo $clickMovimento; ?>"><?php echo $registro["prazorestante"]; ?></td>
								<td onClick="<?php echo $clickMovimento; ?>"><?php echo bdToDate($registro["dataprevisaoresposta"]); ?></td>
								<td onClick="<?php echo $clickMovimento; ?>"><?php echo (!empty($registro["dataprorrogacao"])) ? "Sim" : "N�o"; ?></td>
								<td onClick="<?php echo $clickMovimento; ?>"><?php echo getStatus($registro["situacao"]); ?></td>
							</tr>
						<?php }	?>					
					</table>
				</div>
			</div>
		</div>		
	</div>
</div>
<script>
	
    // function notify() {
    //     Notification.requestPermission(function() {
    //         var notification = new Notification("T�tulo", {
    //             icon: 'http://i.stack.imgur.com/dmHl0.png',
    //             body: "Texto da notifica��o"
    //         });
    //         notification.onclick = function() {
    //             window.open("http://stackoverflow.com");
    //         }
    //     });
    // }  
</script>
