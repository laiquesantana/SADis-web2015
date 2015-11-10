<?php
	// Nome: pegarIp.php
	// pega o IP para a auditoria

	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	  $ip=$_SERVER['HTTP_CLIENT_IP'];
	//Testa "Proxy"
	}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
	  $ip=$_SERVER['REMOTE_ADDR'];
	}
	//Conversão do IP para gravar no BD
	$ip = ip2long($ip);

?>