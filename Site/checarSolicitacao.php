<?php 

  header('Content-Type: aplication/json; charset=utf-8');
  
  require "db.php";

  if (isset($_GET['CodSolic'])){
    $codigo = mysql_real_escape_string($_GET['CodSolic']);

    $result = mysql_query("SELECT StatusSolic, Abertura, Mensagem FROM solicitacoes WHERE CodSolic =".$codigo.";");
    if ($result){
      $jsonArray = array();
      while ($row = mysql_fetch_array($result)){
        $jsonArray['data'][] = array(
          "date"    => utf8_encode($row["Abertura"]),
          "status"  => utf8_encode($row["StatusSolic"]),
          "message" => utf8_encode($row["Mensagem"])
        );
      }
    }
  }  
    
  if (!isset($codigo) || !isset($jsonArray) || sizeof($jsonArray) == 0){
    $jsonArray['data'][] = array(
      "date"    => "",
      "status"  => utf8_encode("Solicitação não encontrada!"),
      "message" => ""
    );
  }
  
  echo json_encode($jsonArray);
?>
