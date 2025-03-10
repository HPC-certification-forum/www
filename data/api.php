<?php
  /*
   * RESTful API to request skills
   */
  # Root to skills
  $root = "/home/www-hpccf/git";
  $skill_root = $root ."/skill-tree-wiki/skill-tree/";
  $material_root = $root ."/skill-data-material/";
  $event_root = $root ."/skill-data-events/";
  $exam_root = $root . "/examination-questions-staging/";

  $debug = array_key_exists("debug", $_GET);
  $skill = $_GET["request"];
  $fields = array_key_exists("fields", $_GET) ? $_GET["fields"] : "all";
  $version = array_key_exists("version", $_GET) ? $_GET["version"] : "latest";
  $format = array_key_exists("format", $_GET) ? $_GET["format"] : "json";
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

  header('Content-type: application/json; charset=utf-8');
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

  function retrieve_subtree_skills($dir, $id, $format){
      $it = new RecursiveDirectoryIterator($dir . $id);
      $data = array();
      $length = strlen($dir);
      foreach(new RecursiveIteratorIterator($it) as $file){
          $name = $file->getFilename();
          $nameLen = strlen($name);
          if($nameLen < 3 || $name[0] == "." || ! endsWith($name, ".txt")) continue;

          $file = substr($file->getPathname(), $length);
          $file = substr($file, 0, strlen($file) - 4);

          if($file == "b" || $file == "leftover") continue; // skip root

          $skill = load_skill($dir, $file);
          if($format == "js-structure"){
            // find the skill in the hierarchy
            $target = explode("/", $file);
            $cur = & $data;
            $parent = "";
            foreach($target as $i){
              $full = $parent . $i;
              if(! array_key_exists($full, $cur)){
                $cur[$full] = array();
              }
              $cur = & $cur[$full];
              $parent = $parent . $i . "/";
            }
            if(array_key_exists("subskills", $skill)){
              foreach($skill["subskills"] as $i){
                $cur[$i] = array();
              }
            }
          }else if($format == "json"){
            //array_push($data, $skill);
            $data[$file] = $skill;
          }else if($format == "js-skills"){
            if(array_key_exists("title", $skill)){
              $elem = array("id" => $file, // str_replace("/", ".",
                "define" => "core data",
                "level" => "Merged",
                "name"  => $skill["title"]);
              array_push($data, $elem);
            }
          }
      }
      if($format == "js-structure"){
        return  array(array("tree" => array("ST" => $data), "define" => "tree"));
      }else if($format == "js-skills"){
        array_push($data, array("id" => "ST",
                    "define"=> "core data",
                    "category"=> "All",
                    "name"=> "Skill Tree",
                    "level"=> "Merged"));

      }
      return $data;
  }

  function generate_subtree_skills($dir, $id, $format){
    $data = retrieve_subtree_skills($dir, $id, $format);
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

  function load_teaching($id){
    $file = $id . ".json";

    if(! file_exists($file)){
      return null;
    }
    $data = file_get_contents($file);
    return json_decode($data);
  }

  function getExamQuestionCount($path) {
    $size = 0;
    $ignore = array('.', '..');
    $files = scandir($path);
    foreach($files as $t) {
        if(in_array($t, $ignore)) continue;
        $file = rtrim($path, '/') . '/' . $t;
        if (is_dir($file)) {
            $size += getExamQuestionCount(rtrim($path, '/') . '/' . $t);
        } else {
            $data = file_get_contents($file);
            $size += substr_count($data, "#select");
        }
    }
    return $size;
  }

  function load_exam($dir, $id){
    return array( "questions" => getExamQuestionCount($dir . $id));
  }

  function load_skill($dir, $id){
    $file = $dir . $id . ".txt";

    if(! file_exists($file)){
      return array("error" => "skill doesn't exist", "id" => $id);
    }
    $data = file_get_contents($file);
    $out = array("title" => "", "id" => $id);
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
        if(preg_match("/  ( )*[*](.*)/", $line, $match)){
          if($match[1] == ""){
            $line = trim($match[2]);
          }else{
            $line = "* " . trim($match[2]);
          }
        }else{
          $line = trim($line);
        }
        if($line == "") continue;
        array_push($items, $line);
      }
      $first = strtolower($first);
      if($first == "subskills"){
        // formatted as: * [[skill-tree:bda:1:b]]
        $res = array();
        foreach($items as $i){
          if(preg_match("/.*skill-tree:(.*)\]\].*/i", $i, $match)){
            array_push($res, str_replace(":", "/",$match[1]));
          }
        }
        $items = $res;
      }
      $out[$first] = $items;
    }
    if(! array_key_exists("learning outcomes", $out)){
      return array("error" => "skill is incomplete", "id" => $id);
    }
    // append additional information

    $data = filter($out);
    global $q_fields, $material_root, $event_root, $exam_root;
    if(array_key_exists("all", $q_fields) || array_key_exists("material", $q_fields)){
      $train = load_teaching($material_root . $id);
      if($train){
        $data["material"] = $train;
      }
    }
    if(array_key_exists("all", $q_fields) || array_key_exists("events", $q_fields)){
      $train = load_teaching($event_root . $id);
      if($train){
        $data["events"] = $train;
      }
    }
    if(array_key_exists("all", $q_fields) || array_key_exists("exam", $q_fields)){
      $train = load_exam($exam_root, $id);
      if($train){
        $data["exams"] = $train;
      }
    }

    return $data;
  }

  if(array_key_exists("list", $_GET)){
    generate_list($skill_root, $skill);
  }

  if(endsWith($skill, "/") || $skill == ""){
    generate_subtree_skills($skill_root, $skill, $format);
  }

  $data = load_skill($skill_root, $skill);
  print(json_encode($data));
?>
