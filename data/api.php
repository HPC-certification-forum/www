<?php
  /*
   * RESTful API to request skills
   */
  # Root to skills
  $skill_root = "/home/www/hpccertification/skill-tree-wiki/skill-tree/";

  $debug = array_key_exists("debug", $_GET);
  $skill = $_GET["request"];
  $fields = array_key_exists("fields", $_GET) ? $_GET["fields"] : "all";
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

  function endsWith($haystack, $needle){
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
  }

  function generate_list($dir){
    $it = new RecursiveDirectoryIterator($dir);
    $data = array();
    $length = strlen($dir);
    foreach(new RecursiveIteratorIterator($it) as $file){
        //if (in_array(strtolower(array_pop(explode('.', $file))), $display)){
        //}
        $name = $file->getFilename();
        $nameLen = strlen($name);
        if($nameLen < 3 || $name[0] == "." || ! endsWith($name, ".txt")) continue;

        $file = substr($file->getPathname(), $length);
        $file = substr($file, 0, strlen($file) - 4);
        array_push($data, $file);
    }
    header('Content-type: application/json');
    print(json_encode($data));
    exit(0);
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
        $line = trim($line, "* \t\n\r\0\x0B");
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

    return $out;
  }

  function load_extra($id){
    $file = $skill_root . $id . ".txt";

    if(! file_exists($file)){
      return array();
    }
    $data = file_get_contents($file);
    $out = array("title" => "");
    foreach(preg_split("/# */", $data) as $section){
      $txt = explode("\n", $section);
      $items = array();
      foreach($txt as $line){
        $line = trim($line, "* \t\n\r\0\x0B");
        if($line == "") continue;
        array_push($items, $line);
      }
      $out[strtolower($first)] = $items;
    }
    return $out;
  }

  if(array_key_exists("list", $_GET)){
    generate_list($skill_root);
  }

  $data = load_skill($skill_root . $skill);
  if( ! array_key_exists("all", $q_fields) ){
    $res = array();
    foreach($q_fields as $field => $val){
      if(array_key_exists($field, $data)){
        $res[$field] = $data[$field];
      }
    }
    $data = $res;
  }

  header('Content-type: application/json');
  print(json_encode($data));
?>
