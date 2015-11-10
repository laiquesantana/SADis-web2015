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
	// Nome: index.php
	// Mostra todos os Cursos
	session_start();
	if (!isset($_SESSION["acesso"])){
		header("location: ..\login.php");
	
	}
	//auditoria starts
	require "../db.php";
	$rotina = "AcessarDisciplinas";
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
	
		

	$rs = mysql_query("select * from disciplinas");
	$linhas = mysql_num_rows($rs);


	$html = null;

	for($i=0;$i<$linhas;$i++){
		$cursos = "";
		$disc = mysql_result($rs,$i,'CdIdeDis');
		$result = mysql_query("SELECT CURSOS_CdIdeCur from r_disciplinas_cursos WHERE DISCIPLINAS_CdIdeDis = '$disc'");
		while($row = mysql_fetch_array($result))
		{ 
			//echo $row['CURSOS_CdIdeCur']."<br>";
			$curso= $row['CURSOS_CdIdeCur'];
			$result2 = mysql_query("SELECT NmIdeCur from cursos WHERE CdIdeCur = '$curso'");
			while($row2 = mysql_fetch_array($result2))
			{ 
				$cursos .=$row2['NmIdeCur']."  | ";
			}
		}
		
		
		$html .=  '<tr>
				<td>' .	utf8_encode(mysql_result($rs,$i,'CdIdeDis')) . '</td>
				<td>' . utf8_encode(mysql_result($rs,$i,'CodIdeDis')) . '</td>
				<td>' . utf8_encode(mysql_result($rs,$i,'NmIdeDis')) . '</td>
				<td>' . utf8_encode($cursos) . '</td>
				
				
				<td> <a href= "editar.php?CdIdeDis=' . mysql_result($rs,$i,'CdIdeDis') . ' 
				"title="Editar"><input name="edit" type="button" class="edit"></a> </td>
				<td><a href="deletar.php?CdIdeDis='. mysql_result($rs,$i,'CdIdeDis').'"title="Remover"><input name="remove" type="button" class="remove"></a> </td>
			</tr>';
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
					<a style="margin-left:15px;" href="login.php" class="right"title="Sair"><input name="exit1" type="button" class="exit1"></a>
					
				</div>
			</div>
			<div class="grid_24">
				<div class="background-transparente">    
					<div class="id_aba_ativa">
						Disciplinas
					</div>
					<div class="id_aba">
						<a href="cadastro.php">Cadastrar nova Disciplina</a>
					</div>
			
					<div class="clearfix"></div> 
					<div class="background_conteudo">
						<table id="cursos" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>							
								<td><h2>Id </h2></td>
								<td><h2>Código</h2></td>
								<td><h2>Nome</h2></td>
								<td><h2>Cursos Relacionados</h2></td>															
								<td><h2>  </h2></td>															
								<td><h2>  </h2></td>															
							</tr>  
						</thead>	
						<tbody>
							<?php echo $html; ?>
						</tbody>	
						
						</table><br>
<a href="../index.php">&nbsp;<button class="but but-rc but-primary but-shadow">voltar</button></a>

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