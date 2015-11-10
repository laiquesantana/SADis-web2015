<head>
<meta charset="utf-8" />
<title></title>
<link rel="stylesheet" href="../css/960_24_col.css" type='text/css'/> <!-- Grid 960 -->
<link rel="stylesheet" href="../css/jquery.dataTables.css" type='text/css'/> <!-- Grid 960 -->
<link rel="stylesheet" href="../css/style.css" type='text/css' /> 
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'><!-- GoogleFonts -->
<script src="../../js/jquery-1.10.2.min.js"></script>
<script src="../../js/jquery.dataTables.js"></script>
</head>
<?php
	// Nome: mudarSenha.php
	// Muda a senha do usuário

?>
<body>
	<div class="background">
		<div class="container_24">
			<div class="grid_4 suffix_13">
				<div class="logo">
					<a href="index.php"><img src="../img/logo_SADis_menor.png"></a>
				</div>
			</div>
			<div class="grid_7">
				<div class="id_usuario">
					<a style="margin-left:15px;" href="login.php" class="right"title="Sair"><input name="exit1" type="button" class="exit1"></a>
					
				</div>
			</div>
			<div class="grid_24">
				<div class="background_transparente">    
					<div class="id_aba_ativa">
						Mudar a senha
					</div>
					<div class="clearfix"></div> 
					<div class="background_conteudo">
						
					<form method="POST" action="">
							
						<h2>Senha Atual: </h2><input type="text" name="senhaAtual" id="NOME" /><br>
						<h2>Nova Senha: </h2><input type="text" name="novaSenha" id="NOME" /><br>
						
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


<script type="text/javascript">
	function validar(formulario) {

		if ((formulario.senhaAtual.value.length == 0 ) || (formulario.novaSenha.value.length == 0 ) )   {
			alert("Por favor preencher os campos.");
			return false;
		}
		

		return true;
	}
</script>
  