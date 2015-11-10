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
		$rotina = "AcessarDisciplinas->Cadastro";
		require "../pegarIp.php";
		$query = "INSERT into auditoria ( Niveis_CdIdeNivel, Usuarios_CdIdeUsu, DsIdeAudit , IpAudit ) 
					VALUES ( '$nivel' , '$idUsuario', '$rotina' , '$ip' ) ";					
		$rs = mysql_query($query);
	//auditoria ends
	
	if(isset($_POST['NmIdeDis'])){

		require "../db.php";
		
		$cod = utf8_decode($_POST['CodIdeDis']);
		$nome = utf8_decode($_POST['NmIdeDis']);

		$cod = strip_tags(mysql_real_escape_string($cod,$con));	
		$nome = strip_tags(mysql_real_escape_string($nome,$con));	
		
		
		
		$sql = "INSERT INTO  disciplinas 
											(
											CodIdeDis,
											NmIdeDis											
											)
											VALUES
											( 
											'" . $cod . "' ,
											'" . $nome . "'
											)";
											
		
		
		$rs = mysql_query($sql);	
		$indDisciplina = mysql_insert_id();
		
		$aCursos = $_POST['cursos'];
		if(empty($aCursos)) 
		{
			$semMaterias = true;
		} 
		else
		{
			$N = count($aCursos);
			for($i=0; $i < $N; $i++)
				{
								
					$indCurso = $aCursos[$i];
					
					$rs = mysql_query("INSERT INTO r_disciplinas_cursos (DISCIPLINAS_CdIdeDis, CURSOS_CdIdeCur ) 
																	VALUES ( '" . $indDisciplina . "' , '" . $indCurso . "') ");
				 
				}
		}
		
		
		
		header("Location: index.php");

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
					<a style="margin-left:15px;" href="login.php" class="right"><input name="exit1" type="button" class="exit1"></a>
					
				</div>
			</div>
			<div class="grid_24">
					<div class="background-transparente">    
						<div class="id_aba_ativa">
							Cadastro de Disciplinas
						</div>
					<div class="id_aba">
						<a href="index.php">Disciplinas</a>
					</div>
			
					<div class="clearfix"></div> 
					<div class="background_conteudo">
						<form onsubmit="return validar(this);" method="POST" action="">


							<h2>Código: </h2><input type="text" name="CodIdeDis" id="NOME" />
							<h2>Nome: </h2><input type="text" name="NmIdeDis" id="NOME" />


							
							<h2>Cursos Relacionados </h2>
							<?php 
								$result = mysql_query("SELECT CdIdeCur, NmIdeCur from cursos ");
								while($row = mysql_fetch_array($result))
								{  ?>
								
								<input type="checkbox" name="cursos[]" value="<?php echo utf8_encode($row['CdIdeCur']);?>"> <?php echo utf8_encode($row['NmIdeCur']);?><br>
							<?php
								}
							?>    
							
							<br />		
							<br />		
							<br />		
							<a href="../index.php">&nbsp;<button class="but but-rc but-primary but-shadow">voltar</button></a>
							<button style="margin-left:15px;"type="submit" class="but but-rc but-primary but-shadow" onClick="if (!validacao()) return false;">enviar</button>

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
<script type="text/javascript">
	function validar(formulario) {

		if (formulario.NmIdeCur.value.length == 0 )   {
			alert("Por favor preencher os campos.");
			return false;
		}
		

		return true;
	}
</script>