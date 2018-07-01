<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informação baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa é software livre; você pode redistribuí-lo e/ou
 modificá-lo sob os termos da Licença GPL2.
***********************************************************************************/

require_once("config.php");

function db_open() {

    $conn = mysql_connect(DBHOST, DBUSER, DBPASS) or die('nao pode conectar ao banco');
    mysql_select_db(DBNAME) or die("nao pode selecionar o banco");

    return $conn;
}

function db_close($conn) {
    mysql_close($conn) or die ("nao pode fechar a conexao");
}

//retorna objeto de conexao com o banco para transações
function db_open_trans()
{
	//conecta ao mysqli
	$mysqli = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
	
	/* check connection */
	if (mysqli_connect_errno()) {
		die("Falha na conexao: ". mysqli_connect_error());
	}		

	$mysqli->autocommit(false);
	
	return $mysqli;

}



function execQuery($query) {
    $conn = db_open();

    $rs = mysql_query($query, $conn); // or die (mysql_error());

    db_close($conn);
    return $rs;
}

function rs_to_array($result, $numass=MYSQL_BOTH) {
    $got = array();

    if(mysql_num_rows($result) == 0)
    return $got;

    mysql_data_seek($result, 0);

    while ($row = mysql_fetch_array($result, $numass)) {
        array_push($got, $row);
    }

    return $got;
}


?>
