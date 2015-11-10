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
		$rotina = "AcessarDisciplinas->Editar->".$_GET['CdIdeDis'];
		require "../pegarIp.php";
		$query = "INSERT into auditoria ( Niveis_CdIdeNivel, Usuarios_CdIdeUsu, DsIdeAudit , IpAudit ) 
					VALUES ( '$nivel' , '$idUsuario', '$rotina' , '$ip' ) ";					
		$rs = mysql_query($query);
	//auditoria ends
	
	if(isset($_POST['NmIdeDis'])){
		$id = $_GET['CdIdeDis'];
		$id = strip_tags(mysql_real_escape_string($id,$con));
		
		$nome = utf8_decode($_POST['NmIdeDis']);
		$cod = utf8_decode($_POST['CodIdeDis']);

		$nome = strip_tags(mysql_real_escape_string($nome,$con));	
		$cod = strip_tags(mysql_real_escape_string($cod,$con));	
		
		// O FORMULARIO FOI POSTADO
		$sql = "UPDATE disciplinas SET CodIdeDis='" . $cod. "' , NmIdeDis='" . $nome. "'  WHERE CdIdeDis=" . $id;					
		$rs = mysql_query($sql);
		
		header("Location: index.php");

	}else{
		// primeira vez na pagina
			$id = $_GET['CdIdeDis'];
			$id = strip_tags(mysql_real_escape_string($id,$con));
			$sql = "SELECT * FROM disciplinas WHERE CdIdeDis = " . $id;
			$rs   = mysql_query($sql); 
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
						Editar Disciplina
					</div>
					<div class="id_aba">
						<a href="index.php">Disciplinas</a>
					</div>
					<div class="id_aba">
						<a href="cadastro.php">Cadastrar nova Disciplina</a>
					</div>
			
					<div class="clearfix"></div> 
					<div class="background_conteudo">

						<form method="POST" action="">
							<h2>Código: </h2><input type="text" name="CodIdeDis" id="CodIdeDis" value="<?php echo utf8_encode(mysql_result($rs,0,"CodIdeDis")); ?>" />
							<br />
							
							<h2>Nome: </h2><input type="text" name="NmIdeDis" id="NmIdeDis" value="<?php echo utf8_encode(mysql_result($rs,0,"NmIdeDis")); ?>" />	
							<br />
							
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