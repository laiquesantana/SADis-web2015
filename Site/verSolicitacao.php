<html>
<head>
	<meta charset="utf-8" />
	<title>SADis - Acompanhar Solicitação</title>
	<link rel="stylesheet" href="css/960_24_col.css" type='text/css'/> <!-- Grid 960 -->
	<link rel="stylesheet" href="css/jquery.dataTables.css" type='text/css'/> <!-- Grid 960 -->
	<link rel="stylesheet" href="css/style.css" type='text/css' /> 
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'><!-- GoogleFonts -->
</head>

<body>
	<div class="background">
		<div class="container_24">
			<div class="grid_4 suffix_13">
				<div class="logo">
					<a href="index.html"><img src="logo_SADis_menor.png"></a>
				</div>
			</div>

			<div class="grid_24">
				<div class="background_transparente">    
					<div class="id_aba_ativa">
						<font color="000" size="3px"style="font-weight:bold;">Acompanhar Solicitação</font>
					</div>

					<div class="clearfix"></div> 
					<div class="background_conteudo">
						<div class="background_conteudo">
							<h2> STATUS DA SOLICITAÇÃO: 
							<?php 
							  $tempData = json_decode(str_replace("'", '"', $_POST['jsonData']), true);
$data = $tempData["data"];
							  for($i = 0; $i < sizeof($data); $i++){
							    if ($data[$i]["date"] != ""){
    							  echo "</br>Status: ";
    							  echo utf8_decode($data[$i]["status"]);
							      echo "</br>Data: ";
    							  echo $data[$i]["date"];
    							  $msg = utf8_decode($data[$i]["message"]);
    							  if ($msg != "") echo "</br>Mensagem: ".$msg."</br>";
    							}
    							// If got error message
    							else{
    							  echo "</br>";
    							  echo utf8_decode($data[$i]["status"]);
    							  echo "</br>";
    							}
							  }
							  if ($_POST['jsonData'] == "{'data':[{'date':'','status':'Deferindo Presencialmente','message':''}]}"){
								echo "</br>"; 
							  	echo "Procure a secretaria do seu Departamento para reapresentação de documentos";
							  	echo "</br>"; }
							?>
							</h1></br>
							<a href="index.html"><button class="btn">Voltar</button></a>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>  
	</div>           
</body>

</html>