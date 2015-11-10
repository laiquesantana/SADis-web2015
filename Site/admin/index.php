<head>
	<meta charset="utf-8" />
	<title>SADIS Administrador - Entrada</title>
	<link rel="stylesheet" href="../css/960_24_col.css" type='text/css'/> <!-- Grid 960 -->
	<link rel="stylesheet" href="../css/jquery.dataTables.css" type='text/css'/> <!-- Grid 960 -->
	<link rel="stylesheet" href="../css/style.css" type='text/css' /> 
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'><!-- GoogleFonts -->
</head>
<?php 
	session_start();
	if (!isset($_SESSION["acesso"])){
		header("location: login.php");
		
	}
	else{
		include ("db.php");
		$usuario = $_SESSION["usuario"];
		$sql = "SELECT CdIdeUsu FROM usuarios where NmIdeUsu = '$usuario' ";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){
			$CdIdeUsu = $row['CdIdeUsu'];
		}
?>
<body>
	<div class="background">
		<div class="container_24">
			<div class="grid_4 suffix_13">
				<div class="logo">
					<a href="../index.html"><img src="../logo_SADis_menor.png"></a>
				</div>
			</div>
			<div class="grid_7">
				<div class="id_usuario">
					<a style="margin-left:15px;" href="login.php" class="right"title="Sair"><input name="exit1" type="button" class="exit1"></a>
					
				</div>
			</div>
			<div class="grid_24">
				<div class="background-transparente">    		
					<div class="clearfix"></div> 
					
								<div class="grid_11 prefix_13"><h3 >Painel de Controle</h3></div>	
								<div class="grid_22 prefix_6">
								<div class="grid_4"><td><a href="cursos/index.php"> <br><br><button class="newbtn">Cursos </button></a></td></div>
								<div class="grid_4"><td><a href="disciplinas/index.php"> <br><br><button class="newbtn">Disciplinas </button></a></td></div>
								<div class="grid_4"><td><a href="usuarios/index.php"> <br><br><button class="newbtn">Usuários </button></a></td></div>
								<div class="grid_4"><td><a href="solicitacoes/index.php"> <br><br><button class="newbtn">Solicitações </button></a></td></div>
								<div class="grid_4"><td><a href="mudarSenha.php?CdIdeUsu=<?php echo $CdIdeUsu;?>"> <br><br><button class="newbtn">Mudar Senha </button></a></td></div>
								</div>	
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>  
	</div> 
</body>	
<?php } ?>
