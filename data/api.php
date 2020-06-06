<?php
  /*
   * RESTful API to request skills
   */
  # Root to skills
  $skill_root = "/home/www/hpccertification/skill-tree-wiki/skill-tree/";
  $training_root = "/home/www/hpccertification/skill-data-training/";

  $debug = array_key_exists("debug", $_GET);
  $skill = $_GET["request"];
  $fields = array_key_exists("fields", $_GET) ? $_GET["fields"] : "all";
  $version = array_key_exists("version", $_GET) ? $_GET["version"] : "latest";
  $q_fields = array();
  foreach(explode(",", $fields) as $field){
    $q_fields[$field] = true;
  }

  if (! array_key_exists("request", $_GET)){
    http_response_code(404);
    exit(1);
  }
  $skill = str_replace ( "." , "", $skill);
  if($debug){
    print("Requested: " . $skill . " " . $fields);
    var_dump($_GET);
  }

  header('Content-type: application/json');
  header("Access-Control-Allow-Origin: *");

  function endsWith($haystack, $needle){
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
  }

  function generate_list($dir, $id){
    $it = new RecursiveDirectoryIterator($dir . $id);
    $data = array();
    $length = strlen($dir);
    foreach(new RecursiveIteratorIterator($it) as $file){
        $name = $file->getFilename();
        $nameLen = strlen($name);
        if($nameLen < 3 || $name[0] == "." || ! endsWith($name, ".txt")) continue;

        $file = substr($file->getPathname(), $length);
        $file = substr($file, 0, strlen($file) - 4);
        array_push($data, $file);
    }
    print(json_encode($data));
    exit(0);
  }

  function retrieve_subtree_skills($dir, $id){
      $it = new RecursiveDirectoryIterator($dir . $id);
      $data = array();
      $length = strlen($dir);
      foreach(new RecursiveIteratorIterator($it) as $file){
          $name = $file->getFilename();
          $nameLen = strlen($name);
          if($nameLen < 3 || $name[0] == "." || ! endsWith($name, ".txt")) continue;

          $file = substr($file->getPathname(), $length);
          $file = substr($file, 0, strlen($file) - 4);
          $data[$file] = load_skill($dir . $file);
      }
      return $data;
  }

  function generate_subtree_skills($dir, $id){
    $data = retrieve_subtree_skills($dir, $id);
    print(json_encode($data));
    exit(0);
  }

  function filter($data){
    global $q_fields;
    if( array_key_exists("all", $q_fields) ){
      return $data;
    }
    $res = array();
    foreach($q_fields as $field => $val){
      if(array_key_exists($field, $data)){
        $res[$field] = $data[$field];
      }
    }
    return $res;
  }

  function load_skill($id){
    $file = $id . ".txt";

    if(! file_exists($file)){
      print('{ "error" : "skill doesn\'t exist"}');
      exit(0);
    }
    $data = file_get_contents($file);
    $out = array("title" => "");
    foreach(preg_split("/# */", $data) as $section){
      $txt = explode("\n", $section);
      $first = trim(array_shift($txt));
      if($first == ""){
        continue;
      }
      if($out["title"] == ""){
        $out["title"] = $first;
        continue;
      }
      $items = array();
      foreach($txt as $line){
        $line = trim($line);
        if($line == "") continue;
        array_push($items, $line);
      }
      $out[strtolower($first)] = $items;
    }
    if(! array_key_exists("outcomes", $out)){
      print('{"error":"skill doesn\'t exist"}');
      exit(0);
    }
    // append additional information

    return filter($out);
  }

  function load_teaching($id){
    $file = $id . ".json";

    if(! file_exists($file)){
      return null;
    }
    $data = file_get_contents($file);
    return json_decode($data);
  }

  if(array_key_exists("list", $_GET)){
    generate_list($skill_root, $skill);
  }

  if(endsWith($skill, "/")){
    generate_subtree_skills($skill_root, $skill);
  }

  $data = load_skill($skill_root . $skill);
  if(array_key_exists("all", $q_fields) || array_key_exists("training", $q_fields)){
    $train = load_teaching($training_root . $skill);
    if($train){
      $data["training"] = $train;
    }
  }

  print(json_encode($data));
?>
