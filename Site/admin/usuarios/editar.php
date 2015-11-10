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
		$rotina = "AcessarUsuarios->Editar->".$_GET['CdIdeUsu'];
		require "../pegarIp.php";
		$query = "INSERT into auditoria ( Niveis_CdIdeNivel, Usuarios_CdIdeUsu, DsIdeAudit , IpAudit ) 
					VALUES ( '$nivel' , '$idUsuario', '$rotina' , '$ip' ) ";					
		$rs = mysql_query($query);
	//auditoria ends
	
	if(isset($_POST['NmIdeUsu'])){
		$id = $_GET['CdIdeUsu'];
		$id = strip_tags(mysql_real_escape_string($id,$con));
		
		$nivel = utf8_decode($_POST['Niveis_CdIdeNivel']);
		$nome = utf8_decode($_POST['NmIdeUsu']);
		$senha = utf8_decode($_POST['SenhaIdeUsu']);
		$email = utf8_decode($_POST['EmailIdeUsu']);

		$nivel = strip_tags(mysql_real_escape_string($nivel,$con));	
		$nome = strip_tags(mysql_real_escape_string($nome,$con));	
		$senha = strip_tags(mysql_real_escape_string($senha,$con));	
		$email = strip_tags(mysql_real_escape_string($email,$con));	
		
		// O FORMULARIO FOI POSTADO
		$sql = "UPDATE usuarios SET 
						Niveis_CdIdeNivel='" . $nivel. "'  ,
						NmIdeUsu='" . $nome. "'  ,
						SenhaIdeUsu='" . $senha. "'  ,
						EmailIdeUsu='" . $email. "' WHERE CdIdeUsu=" . $id;					
		$rs = mysql_query($sql);
		
		header("Location: index.php");

	}else{
		// primeira vez na pagina
			$id = $_GET['CdIdeUsu'];
			$id = strip_tags(mysql_real_escape_string($id,$con));
			$sql = "SELECT * FROM usuarios WHERE CdIdeUsu = " . $id;
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
					<a style="margin-left:15px;" href="login.php" class="right"><input name="exit1" type="button" class="exit1"></a>
				</div>
			</div>
			<div class="grid_24">
				<div class="background_transparente">    
					<div class="id_aba_ativa">
						Editar Usuário
					</div>
					<div class="id_aba">
						<a href="index.php">Usuários</a>
					</div>
					<div class="id_aba">
						<a href="cadastro.php">Cadastrar novo Usuário</a>
					</div>
			
					<div class="clearfix"></div> 
					<div class="background_conteudo">

						<form method="POST" action="">
							<?php $nivel = mysql_result($rs,0,"Niveis_CdIdeNivel");?>
							<h2>Nível: </h2>
							<select name="Niveis_CdIdeNivel">
								<?php 
								  $result = mysql_query("SELECT CdIdeNivel  , DsIdeNivel from niveis WHERE CdIdeNivel = '$nivel' ");
									while($row = mysql_fetch_array($result))
									{ ?><option value="<?php echo utf8_encode($row['CdIdeNivel']);?>" > <?php echo utf8_encode($row['DsIdeNivel']);?> </option>             
								<?php
									}
								?>    
								
								<?php 
								  $result = mysql_query("SELECT CdIdeNivel  , DsIdeNivel from DsIdeNivel WHERE CdIdeNivel <> '$nivel' ");
									while($row = mysql_fetch_array($result))
									{ ?><option value="<?php echo utf8_encode($row['CdIdeNivel']);?>" > <?php echo utf8_encode($row['DsIdeNivel']);?> </option>             
								<?php
									}
								?>                                                          
							</select></br>
									
							
							<h2>Nome: </h2><input type="text" name="NmIdeUsu" id="NmIdeUsu" value="<?php echo utf8_encode(mysql_result($rs,0,"NmIdeUsu")); ?>" />
							<br />
							<h2>Senha: </h2><input type="text" name="SenhaIdeUsu" id="SenhaIdeUsu" value="<?php echo utf8_encode(mysql_result($rs,0,"SenhaIdeUsu")); ?>" />
							<br />	
							<h2>Email: </h2><input type="text" name="EmailIdeUsu" id="EmailIdeUsu" value="<?php echo utf8_encode(mysql_result($rs,0,"EmailIdeUsu")); ?>" />
							<br />								
							<br />
							<br />
							<br />
							
							
							<button class="but but-rc but-shadow but-primary" type="submit"  onClick="if (!validacao()) return false;">enviar</button>
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
