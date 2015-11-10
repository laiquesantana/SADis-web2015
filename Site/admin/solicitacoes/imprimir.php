<?php
	session_start();
	if (!isset($_SESSION["acesso"])){
		header("location: ..\login.php");
	
	}	
	
	//auditoria starts
	require "../db.php";
	$rotina = "AcessarSolicitacoes";
	$nivel = $_SESSION["nivel"];
	$usuario = $_SESSION["usuario"];
	$query = "SELECT count(*) FROM direitos WHERE DsIdeRotina = '$rotina' AND Niveis_CdIdeNivel = '$nivel' AND ConsIdeRot = 1";
	$rs = mysql_query($query);
	$row = mysql_fetch_row($rs);
	
	if ( $row[0] > 0 ) { // nivel pode ver a pagina
		$query = "SELECT CdIdeUsu from usuarios where NmIdeUsu = '$usuario'";
		$rs = mysql_query($query);
		$row = mysql_fetch_row($rs);
		$idUsuario = $row[0];
		require "../pegarIp.php";
		$query = "INSERT into auditoria ( Niveis_CdIdeNivel, Usuarios_CdIdeUsu, DsIdeAudit , IpAudit ) 
					VALUES ( '$nivel' , '$idUsuario', '$rotina' , '$ip' ) ";					
		$rs = mysql_query($query);
	//auditoria ends
	
		$disc2 = "";
		$idSolic =  $_GET['CdIdeAluno'];
		$idSolic = mysql_real_escape_string($idSolic,$con);
		$sql = "select * FROM solicitacoes WHERE CdIdeAluno = '$idSolic' "; 
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){
			$nome = $row['NmIdeAluno'];
			
			$aluno = utf8_encode($row["CdIdeAluno"]);
			$CodSolic = utf8_encode($row["CodSolic"]);
			$sql1 = "SELECT DISCIPLINAS_CdIdeDis FROM r_alunos_disciplinas WHERE ALUNOS_CdIdeAlu = ".$aluno;
			$result3 = mysql_query($sql1);
			while($row3 = mysql_fetch_array($result3))
			{ 
				$disc= $row3['DISCIPLINAS_CdIdeDis'];
				$result4 = mysql_query("SELECT NmIdeDis FROM disciplinas WHERE CdIdeDis = '$disc'");
				while($row4 = mysql_fetch_array($result4))
				{ 
					$disc2 .= utf8_encode($row4['NmIdeDis'])." , ";
				}
			}
			
		}
		
		$html = "<b><div style='margin-left:27%; margin-top:10%;'><img src='../../img/logo_SADis_menor.png'>  SADis – Sistema de Aproveitamento de Disciplinas</b><br></div>
				<b><div style='margin-left:39%';>UFBA </b>- Universidade Federal da Bahia<br><br></div>
				<div style='margin-left:27%; margin-top:2%;'>
				<div style='margin-left:13%';><b>Comprovante de aproveitamento de disciplinas - CÓDIGO: ".$CodSolic."</b><br></div>
				Este comprovante tem validade legal e comprova que a solicitação do aluno ".$nome." para aproveitamento das disciplinas: <br>
				".$disc2."
				foi avaliada e aprovada pelo órgão responsável.<br><br><br><br>

				<div style='margin-left:18%';>
				_____________________________________<br>
				<div style='margin-left:6%';> Assinatura do Coordenador</div>";
				
		
		echo $html;

	}
	else {
	header("location: ../acessonegado.php");
	}

	
	

?>

<script>
window.print();
</script>

