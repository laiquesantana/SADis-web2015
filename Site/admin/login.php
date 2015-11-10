<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>SADis - Login</title>
		<link rel="stylesheet" href="../css/960_24_col.css" type='text/css'/> <!-- Grid 960 -->
		<link rel="stylesheet" href="../css/style.css" type='text/css' /> 
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'><!-- GoogleFonts -->
	</head>
<?php
	error_reporting(0);
	session_start();
	session_destroy();
	
	$msg="";
	$usuario="";
	$senha ="";
	if (isset($_POST["login"]) && isset($_POST["senha"])){
	    session_start();  
	    require "db.php";
	   
	    $usuario = $_POST["login"];
	    $senha = $_POST["senha"];
		$usuario = mysql_real_escape_string($usuario,$con);	
		$senha = mysql_real_escape_string($senha,$con);	

		$sql = "SELECT * FROM usuarios WHERE NmIdeUsu='$usuario' and SenhaIdeUsu='$senha'"; 			   
		$result = mysql_query($sql);   
		while($row = mysql_fetch_array($result)){ 
			if ( ($row["NmIdeUsu"]==$usuario) && ($row["SenhaIdeUsu"]==$senha) ){
				$_SESSION["acesso"]='OK';
				$_SESSION["usuario"]=$usuario;
				$_SESSION["nivel"]=$row["Niveis_CdIdeNivel"];
				header("location: index.php");
			}       
		}   
		$msg="Acesso não concedido! Tente novamente";  	
		
	} 
?>	
	
	<body>

		<div class="background">
			<div class="container_24">
				<div class="grid_8 prefix_7 suffix_6"><br>
					<div class="logo">
						<img src="../img/logo_SADis_menor.png"><br><br>
					</div>
				</div>
				<div class="grid_10 prefix_7 suffix_7">
					<div class="background_trasnparente">
						<form method="POST" action="">

							<input value="Usuário" onFocus="javascript:this.value=''" type="text" name="login" class="input input_login"></input>
							<input type="password" name="senha" class="input input_login"></input>
							
							
							<input style="width:370px;" class="btn " type="submit" value="Entrar"  />
						</form>	
							
					</div>
				</div>
			</div>  
		</div> 

		

	 
	</body>


<?php 
if($msg!=""){?> 
	<script language="javascript">
	   alert("<?php echo $msg;?>"); 
	</script>
<?php 
}?>  
</html>