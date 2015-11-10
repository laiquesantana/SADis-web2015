<?php
header('Content-Type: text/html; charset=utf-8');
	
	$correcao = 'CiÃªncia da ComputaÃ§Ã£o';
	
	$correcao = str_replace("Ãº", "ú", $correcao);
	$correcao = str_replace("Ãº", "ú", $correcao);
	$correcao = str_replace("Ãº", "ú", $correcao);
	$correcao = str_replace("Ã§Ã£", "çã", $correcao);
	$correcao = str_replace("Ã§Ã£", "çã", $correcao);
	$correcao = str_replace("Ã§Ã£", "çã", $correcao);
	$correcao = str_replace("Ã£", "ã", $correcao);
	$correcao = str_replace("Ã£", "ã", $correcao);
	$correcao = str_replace("Ã£", "ã", $correcao);
	$correcao = str_replace( "Ã¡", "á", $correcao);
	$correcao = str_replace( "Ã¡", "á", $correcao);
	$correcao = str_replace( "Ã¡", "á", $correcao);
	$correcao = str_replace( "Ãª", "ê", $correcao);
	$correcao = str_replace( "Ãª", "ê", $correcao);
	$correcao = str_replace( "Ãª", "ê", $correcao);
	$correcao = str_replace( "Ã©", "é", $correcao);
	$correcao = str_replace( "Ã©", "é", $correcao);
	$correcao = str_replace( "Ã©", "é", $correcao);
	$correcao = str_replace( "Ã§", "ç", $correcao);
	$correcao = str_replace( "Ã§", "ç", $correcao);
	$correcao = str_replace( "Ã§", "ç", $correcao);
	$correcao = str_replace( "Ã³", "ó", $correcao);
	$correcao = str_replace( "Ã³", "ó", $correcao);
	$correcao = str_replace( "Ã³", "ó", $correcao);
	$correcao = str_replace( "Ã", "í", $correcao);
	$correcao = str_replace( "Ã", "í", $correcao);
	$correcao = str_replace( "Ã", "í", $correcao);

	echo $correcao;
	
?>