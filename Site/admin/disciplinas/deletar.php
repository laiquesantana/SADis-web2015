<?php
	session_start();
	if (!isset($_SESSION["acesso"])){
		header("location: ..\login.php");
	
	}	
	
	//auditoria starts
	require "../db.php";
	$rotina = "AcessarDisciplinas";
	$nivel = $_SESSION["nivel"];
	$usuario = $_SESSION["usuario"];
	$query = "SELECT count(*) FROM direitos WHERE DsIdeRotina = '$rotina' AND Niveis_CdIdeNivel = '$nivel' AND ExcIdeRot = 1";
	$rs = mysql_query($query);
	$row = mysql_fetch_row($rs);
	
	if ( $row[0] > 0 ) { // nivel pode ver a pagina
		$query = "SELECT CdIdeUsu from usuarios where NmIdeUsu = '$usuario'";
		$rs = mysql_query($query);
		$row = mysql_fetch_row($rs);
		$idUsuario = $row[0];
		$rotina = "AcessarDisciplinas->Deletar->".$_GET['CdIdeDis'];
		require "../pegarIp.php";
		$query = "INSERT into auditoria ( Niveis_CdIdeNivel, Usuarios_CdIdeUsu, DsIdeAudit , IpAudit ) 
					VALUES ( '$nivel' , '$idUsuario', '$rotina' , '$ip' ) ";					
		$rs = mysql_query($query);
	//auditoria ends
	
	
		$idDisc =  $_GET['CdIdeDis'];
		$idDisc = mysql_real_escape_string($idDisc,$con);
		$sql = "DELETE FROM disciplinas WHERE CdIdeDis = '$idDisc' ";
		$rs  = mysql_query($sql); 
		
		$sql = "DELETE FROM r_disciplinas_cursos WHERE DISCIPLINAS_CdIdeDis = '$idDisc' ";
		$rs  = mysql_query($sql); 
		header("Location: index.php");

	}
	else {
	header("location: ../acessonegado.php");
	}

	
	

?>