<?php
/**********************************************************************************
 Sistema e-SIC Livre: sistema de acesso a informa��o baseado na lei de acesso.
 
 Copyright (C) 2014 Prefeitura Municipal do Natal
 
 Este programa � software livre; voc� pode redistribu�-lo e/ou
 modific�-lo sob os termos da Licen�a GPL2.
***********************************************************************************/

error_reporting(E_ERROR);

define("SISTEMA_NOME", "e-SIC Livre"); //nome do sistema para exibi��o em lugares diversos
define("SISTEMA_CODIGO", "esiclivre"); //codigo para defini��o da lista de sess�o do sistema

// Configura��es de banco de dados
define("DBHOST", "localhost");
define("DBUSER", "general");
define("DBPASS", "A3trindade");
define("DBNAME", "esic");

// Defini��es de e-mail
define("USE_PHPMAILER", false);
define("MAIL_HOST", "mail.gov.br");
define("SMTP_AUTH", false);
define("SMTP_USER", "");
define("SMTP_PWD", "");

// Endere�os do site
define("SITELNK", "https://localhost/e-sic-livre/src/");	//endere�o principal do site
define("URL_BASE_SISTEMA", "https://localhost/e-sic-livre/src/");	//endere�o principal do site

?>
