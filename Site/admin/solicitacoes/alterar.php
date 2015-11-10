<head>
<meta charset="utf-8" />
<title></title>
<link rel="stylesheet" href="../../css/960_24_col.css" type='text/css'/> <!-- Grid 960 -->
<link rel="stylesheet" href="../../css/jquery.dataTables.css" type='text/css'/> <!-- Grid 960 -->
<link rel="stylesheet" href="../../css/style.css" type='text/css' /> 
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'><!-- GoogleFonts -->
<script src="../../js/jquery-1.10.2.min.js"></script>
<script src="../../js/jquery.dataTables.js"></script>
<script language="javascript">
	$(document).ready(function() {
		$('#cursos').dataTable();
	} );
</script>
</head>
<?php
	// Nome: cadastro.php
	// Cadastra um novo curso
	session_start();
	if (!isset($_SESSION["acesso"])){
		header("location: ..\login.php");
	
	}		
	//auditoria starts
	require "../db.php";
	$rotina = "AcessarDisciplinas";
	$nivel = $_SESSION["nivel"];
	$usuario = $_SESSION["usuario"];
	$query = "SELECT count(*) FROM direitos WHERE DsIdeRotina = '$rotina' AND Niveis_CdIdeNivel = '$nivel' AND IncIdeRot = 1";
	$rs = mysql_query($query);
	$row = mysql_fetch_row($rs);
	
	if ( $row[0] > 0 ) { // nivel pode ver a pagina
		$query = "SELECT CdIdeUsu from usuarios where NmIdeUsu = '$usuario'";
		$rs = mysql_query($query);
		$row = mysql_fetch_row($rs);
		$idUsuario = $row[0];
		$rotina = "AcessarDisciplinas->Alterar->".$_GET['CdIdeAluno'];
		require "../pegarIp.php";
		$query = "INSERT into auditoria ( Niveis_CdIdeNivel, Usuarios_CdIdeUsu, DsIdeAudit , IpAudit ) 
					VALUES ( '$nivel' , '$idUsuario', '$rotina' , '$ip' ) ";					
		$rs = mysql_query($query);
	//auditoria ends
	
	if(isset($_POST['StatusSolic'])){
		$id = $_GET['CdIdeAluno'];
		$id = strip_tags(mysql_real_escape_string($id,$con));
		
		$status = utf8_decode($_POST['StatusSolic']);	
		$status = strip_tags(mysql_real_escape_string($status,$con));	
		
		// O FORMULARIO FOI POSTADO
		$sql = "UPDATE solicitacoes SET StatusSolic='" . $status. "'  WHERE CdIdeAluno=" . $id;					
		$rs = mysql_query($sql);

//envio de email
		$nomeQuery = mysql_query("SELECT NmIdeAluno FROM solicitacoes WHERE CdIdeAluno='".$id."';");
		$nomeRetornado = mysql_fetch_row($nomeQuery);
		$nome = $nomeRetornado[0];
		

		$codigoQuery = mysql_query("SELECT CodSolic FROM solicitacoes WHERE CdIdeAluno='".$id."';");
		$codigoRetornado = mysql_fetch_row($codigoQuery);
		$codigo = $codigoRetornado[0];
		

		$emailBuscado = mysql_query("SELECT EmailIdeAluno FROM solicitacoes WHERE CdIdeAluno='".$id."';");
		$emailRetornado = mysql_fetch_row($emailBuscado);
		$email = $emailRetornado[0];


		$mensagem = "<h3> Prezado(a) $nome, o status da sua solicitação de aprovaitamento de disciplinas de código $codigo foi alterado para $status </h3>";

		$emailenviar = $email;	
		$destino = $email;
		$assunto = "Alteração no status da solicitação de aproveitamento de disciplinas ";
		

		// É necessário indicar que o formato do e-mail é html
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Sadis<sadisweb@gmail.br>';
		

		$enviaremail = mail($destino, $assunto, $mensagem, $headers);
		if($enviaremail){
			echo "<h2>Um email foi enviado com para acompanhamento do processo.</h2>";
		} else {
			echo "<h2>Houve uma falha no envio do email de alteração</h2>";
		}


		
		header("Location: index.php");

	}else{
		
	}
?>
<body>
	<div class="background">
		<div class="container_24">
			<div class="grid_4 suffix_13">
				<div class="logo">
					<a href="../index.php"><img src="../../img/logo_SADis_menor.png"></a>
				</div>
			</div>
			<div class="grid_7">
				<div class="id_usuario">
					<a style="margin-left:15px;" href="login.php" class="right">Sair</a>
					<h1 class="right">Usuário Administrador  </h1>
				</div>
			</div>
			<div class="grid_24">
				<div class="background_transparente">    
					<div class="id_aba_ativa">
						Alterar Status
					</div>
					<div class="id_aba">
						<a href="index.php">Solicitações</a>
					</div>

			
					<div class="clearfix"></div> 
					<div class="background_conteudo">

						<form method="POST" action="">
							
							<h2>Status: </h2>
							<select name="StatusSolic">
								<option value="Para Conhecimento" > Em andamento </option>             
								<option value="Em avaliação" > Em avaliação </option>             
								<option value="Presença do(a) aluno(a) requerida" > Presença do(a) aluno(a) requerida </option>             								
								<option value="Indeferido" > Indeferido </option>             
								<option value="Deferido" > Deferido </option>                 
								<option value="Deferindo Presencialmente" > Deferindo Presencialmente </option>             								                                                     
							</select>
							
							<br />
							<br />
							<br />
							
							
							<input class="btn" type="submit" value="ENVIAR"  onClick="if (!validacao()) return false;"/>
						</form>

						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>  
</div>  
</body>

<?php }

else {
header("location: ../acessonegado.php");
}
?>