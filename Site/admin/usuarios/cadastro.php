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
	$rotina = "AcessarUsuarios";
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
		$rotina = "AcessarUsuarios->Cadastro";
		require "../pegarIp.php";
		$query = "INSERT into auditoria ( Niveis_CdIdeNivel, Usuarios_CdIdeUsu, DsIdeAudit , IpAudit ) 
					VALUES ( '$nivel' , '$idUsuario', '$rotina' , '$ip' ) ";					
		$rs = mysql_query($query);
	//auditoria ends
	
	if(isset($_POST['NmIdeUsu'])){

		require "../db.php";
		
		$nivel = utf8_decode($_POST['Niveis_CdIdeNivel']);
		$nome = utf8_decode($_POST['NmIdeUsu']);
		$senha = utf8_decode($_POST['SenhaIdeUsu']);
		$email = utf8_decode($_POST['EmailIdeUsu']);
		$departamento = utf8_decode($_POST['Departamento_CdIdeDepartamento']);

		$nivel = strip_tags(mysql_real_escape_string($nivel,$con));	
		$nome = strip_tags(mysql_real_escape_string($nome,$con));	
		$senha = strip_tags(mysql_real_escape_string($senha,$con));	
		$email = strip_tags(mysql_real_escape_string($email,$con));	
		$departamento = strip_tags(mysql_real_escape_string($departamento,$con));	
		
		
		$sql = "INSERT INTO  usuarios 
											(
											Niveis_CdIdeNivel,											
											NmIdeUsu,											
											SenhaIdeUsu,											
											EmailIdeUsu,
											Departamento_CdIdeDepartamento										
											)
											VALUES
											( 
											'" . $nivel . "' ,											
											'" . $nome . "' ,											
											'" . $senha . "' ,											
											'" . $email . "' ,										
											'" . $departamento . "' 										
											)";
											
		$rs = mysql_query($sql);	
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
					<div class="background_transparente">    
						<div class="id_aba_ativa">
							Cadastro de Usuários
						</div>
					<div class="id_aba">
						<a href="index.php">Usuários</a>
					</div>
			
					<div class="clearfix"></div> 
					<div class="background_conteudo">
						<form onsubmit="return validar(this);" method="POST" action="">


							<h2>Nível: </h2> 
							<select name="Niveis_CdIdeNivel">
								<?php 
									  $result = mysql_query("SELECT CdIdeNivel  , DsIdeNivel from niveis  ");
										while($row = mysql_fetch_array($result))
										{ ?><option value="<?php echo utf8_encode($row['CdIdeNivel']);?>" > <?php echo utf8_encode($row['DsIdeNivel']);?> </option>             
									<?php
										}
								?>    
																						
							</select>
							<h2>Nome: </h2><input type="text" name="NmIdeUsu" id="NOME" />
							<h2>Senha: </h2><input type="text" name="SenhaIdeUsu" id="NOME" />
							<h2>Email: </h2><input type="text" name="EmailIdeUsu" id="NOME" />
							<h2>Departamento: </h2> 
							<select name="Departamento_CdIdeDepartamento">
								<?php 
									  $result = mysql_query("SELECT CdIdeDepartamento  , NmIdeDepartamento from departamentos  ");
										while($row = mysql_fetch_array($result))
										{ ?><option value="<?php echo utf8_encode($row['CdIdeDepartamento']);?>" > <?php echo utf8_encode($row['NmIdeDepartamento']);?> </option>             
									<?php
										}
								?>    
																						
							</select>
							<br />		
							<br />		
							<br />		
							<a href="../index.php">&nbsp;<button class="but but-rc but-primary but-shadow">voltar</button></a>
							<button style="margin-left:20px;"type="submit" class="but but-rc but-primary but-shadow" onClick="if (!validacao()) return false;">enviar</button>

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

		if (
				(formulario.NmIdeCur.value.length == 0 )   ||
				(formulario.SenhaIdeUsu.value.length == 0 )   ||
				(formulario.EmailIdeUsu.value.length == 0 )   
			){
			alert("Por favor preencher os campos.");
			return false;
		}
		

		return true;
	}
</script>