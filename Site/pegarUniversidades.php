<?php
  header("Content-Type: application/json; charset=UTF-8");

  if (isset($_GET["location"])){
    $location = stripslashes(strtolower($_GET["location"]));

    // Check for hacks
    if (ctype_alpha($location)){
      if ($location == "us") getUSAUniversities();
      else{
        $url = "http://ariw.org/lib/w_".$location.".amf.xml";

        $universities = getUniversities($url);
        if ($universities) echo json_encode($universities);
        else echo json_encode("");
      }
    } else echo json_encode("");
  }

  function getUniversities($url){
    // If no error ocurred, get universities
    if ($xml = file_get_contents($url, false)){
      $universities = array();

      $xml = simplexml_load_string($xml);
      foreach ($xml->organization as $uni) {
        $id = str_replace("info:lib/we:", "", (string)$uni->attributes());

        $name = (string)$uni->name;

        $universities[] = array('id' => $id, 'name' => $name);
      }

      return $universities;
    }

    return null;
  }

  function getUSAUniversities(){
    if ($files = file_get_contents("http://ariw.org/lib/", false)){
      preg_match_All("|href=[\"'](.*?)[\"']|", $files, $hrefs);

      $universities = array();
      $error = true;
      foreach($hrefs[0] as $href){
        preg_match("|u_[a-z][a-z]\.amf\.xml|", $href, $url);
        if ($url){
          $uni = getUniversities("http://ariw.org/lib/".$url[0]);
          if ($uni) {
            $error = false;
            $universities[] = $uni;
          }
        }
      }

      if (!$error) echo json_encode($universities);
      else echo json_encode("");
    }
  }
?> 
