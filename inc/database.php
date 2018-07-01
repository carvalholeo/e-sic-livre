<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

require_once("config.php");

function db_open() {

    $mysqli =  new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
    if($mysqli->connect_error) {
        die('Erro de conex�o (' . $mysqli->connect_errno . ')'
                . $mysqli->connect_error);
    }
    

    return $mysqli;
}

function db_close($mysqli) {
    $mysqli->close();
}

//retorna objeto de conexao com o banco para transa��es
function db_open_trans()
{
	//conecta ao mysqli
	$mysqli = db_open();	

	$mysqli->autocommit(false);
	
	return $mysqli;

}



function execQuery($query) {
    $mysqli = db_open();

    $result = $mysqli->query($query); // or die (mysql_error());

    db_close($mysqli);
    return $result;
}

function result_to_array($result, $numass=MYSQL_BOTH) {
    $got = array();

    if(mysqli_num_rows($result) == 0){
        return $got;
    } else {
        mysqli_data_seek($result, 0);

        while ($row = mysqli_fetch_array($result, $numass)) {
            array_push($got, $row);
        }

        return $got;
    }
}


?>
