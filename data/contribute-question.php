<?php

function get_actual_link(){
  return 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function load_skill($id){
  $file = $id . ".txt";
  if(! file_exists($file)){
    print("<h1>Error loading the skill</h1><p>I contacted the admin to notify about the error</p>");
    exit(0);
  }
  $data = file_get_contents($file);
  $out = array();
  foreach(preg_split("/# */", $data) as $section){
    $txt = explode("\n", $section);
    $first = array_shift($txt);
    if(trim($first) == ""){
      continue;
    }
    if(count($out) == 0){
      $out["title"] = $first;
    }else{
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
      $out[strtolower($first)] = $items;
    }
  }
  if(! array_key_exists("outcomes", $out)){
    print("<h1>Error loading the skill</h1><p>I contacted the admin to notify about the error</p>");
    exit(0);
  }
  return $out;
}

function clean_str($string) {
   $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/\*/', 'star', $string);
   $result = trim(preg_replace('/[^A-Za-z0-9]/', '_', $string), "_"); // Replace special chars.
   if($result == ""){
     $result = trim(preg_replace('/[^A-Za-z0-9,_-]/', '_', $result), "_");
   }
   return strtolower($result);
}

function create_file_name($id, $name){
  $path = "/home/www/hpccertification/examination-questions-staging/" . $id;
  mkdir($path, 0755, true);
  return $path . "/" . clean_str($name);
}

function validate_question_submission($id){
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>Submission Form for Exam Questions</title>
      <link rel="stylesheet" href="/css/bootstrap.min.css">
      <link rel="icon" href="/assets/favicon.ico">
    </head>
    <body id="inner">
    <main role="main" class="container">
    <div class ="row justify-content-md-center">
    <?php
      $file = $id . ".txt";
      if(! file_exists($file)){
        print("<h1>Error loading the skill</h1><p>I contacted the admin to notify about the error</p>");
        exit(0);
      }
      $name = clean_str($_POST["los"]);
      if($name == "NA"){
        print("<h1 style='color:red'>You must select a valid learning objective</h1><p>Please return with your browser to the previous page and fix the error.</p>");
        exit(0);
      }
      $path = create_file_name($id, $name);

      $data = "";
      if($_POST["name"] != ""){
        $email = "";
        if($_POST["email"] != ""){
          $email = " <" . $_POST["email"] . ">";
        }
        $data = "#contributor: " . $_POST["name"] . $email . "\n";
      }
      $data = $data . $_POST["question"] . "\n\n#select multiple\n\n";
      for($i=0; $i < 10; $i++){
        $answer = $_POST["a".$i];
        if($answer != ""){
          if(array_key_exists("o" . $i, $_POST)){
            $correct = "_right_";
          }else{
            $correct = "_wrong_";
          }
          $data = $data . " * " . $correct . " " . $answer . "\n";
        }
      }
      $data = $data . "\n\n";

      $ret = file_put_contents($path, $data, LOCK_EX|FILE_APPEND);
      if($ret){
        echo("<h1>Thank you for submitting your question</h1>");
      }else{
        echo("<h1 style='color:red'>Error submitting the question, please contact HPCCF!</h1>");
      }
    ?>
    </div>
    </main>
    </html>
    <?php
}

function view_question_submit($id){
  ?>
  <!DOCTYPE html>
  <html>
  <head>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title>Submission Form for Exam Questions</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="icon" href="/assets/favicon.ico">
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/testGui.js"></script>
    <link rel="stylesheet" href="/css/exam.css">
  </head>
  <body id="inner">
  <main role="main" class="container">
  <div class ="row justify-content-md-center">
  <?php
  $data = load_skill($id);
  ?>
  <h1>Contributing of a question</h1>
  <p><em>The process of contributing a question is described in our article <a href="TODO">here</a>.</em>
  At the moment, the tool chain implements multiple-choice questions from our wishlist which is why we prefer them.
  </p>

  <form action="<?php echo(get_actual_link()); ?>" method="POST">
    <h2>License terms</h2>
    <p>In order to accept your contribution(s), we must ask your consent to our license/contribution terms.
    <br/>
    By submitting a question <b>you explicitly agree</b> <a href="http://hpc-certification.org/contribute-license/">to the terms for contributors</a>.</p>

    <h3> Contact (Optional) </h3>

    <p>
    <em>We give credit to all contributors of whom we select questions. If you provide your details here, we will add you to the contributor list. <br/>Leave it blank to submit a question anonymously.</em>

    <div>
    <label for="name">Your name</label></div>
    <div>
    <input type="text" id="name" name="name" size="50">
    </div>

    <div>
    <label for="email">Email</label>
    </div><div>
    <input type="text" id="email" name="email" pattern=".*@.*" size="50">
    </div>
    </p>

    <h2>Skill</h2>
    <p><?php echo($data["title"]); ?></p>

    <h3>Aim</h3>
    <p>
    <?php echo(implode("<br>", $data["aim"])); ?>
    </p>

    <h3><label for="los">Assessed learning objective</label></h3>
    <p>
    <em>Please select which learning objective is primarily assessed by this question. A question should only assess one learning objective.</em>
    </p>
    <p>
    <select id="los" name="los">
      <option value="NA">unselected</option>
      <?php
      $lastParent = "";
      foreach($data["outcomes"] as $outcome){
        $outcome = trim($outcome);
        if($outcome == "") continue;
        if($outcome[0] == '*'){
          $val = clean_str($lastParent) . "_" . clean_str(trim(substr($outcome, 2)));
        }else{
          $val = clean_str($outcome);
          $lastParent = $outcome;
        }
        $path = create_file_name($id, $val);
        if(! file_exists($path)){
          file_put_contents($path, "", LOCK_EX|FILE_APPEND);
        }
        echo('<option value="' . $val . '">' . $outcome . '</option>');
      }
      ?>
    </select>
    </p>

    <h3><label for="question">Question</label></h3>
    <p><em>You can paste a question and all answers into this box, if you start the question with "^". Use then one line for the question and per answer and * at the beginning of a line to indicate a correct answer.</em><p>
    <p>
    <textarea id="question" name="question" rows="4" cols="100"></textarea>
    </p>

    <h3>Answers</h3>

    <p>
      <em>Provide short answers; an answer could use multiple lines. Please try to balance the number of correct and wrong answers. Tick the box if an answer is correct.</em>
    </p>

    <p>
    <?php
    for($i=0; $i < 10; $i++){
      echo('<div class="row"><div class="col-2">Correct </label><input type="checkbox" id="o' . $i . '" name="o' . $i . '" value="o' . $i . '"></div>
      <div class="col-m"><textarea type="text" id="a' . $i . '" name="a' . $i . '" value=""  rows="2" cols="60"></textarea><label for="o' . $i . '">
      </div></div>'); # <label for="a' . $i . '">Answer</label>
    }
    ?>

    </p>

    <input type="submit" class="btn btn-primary" value="Submit">
    <input type="button" class="btn" value="Preview" onclick='genQuestion()'>

  </form>
  </div>

  <h3 style="margin-top:2em">Preview</h3>
  <p> Shows all answers, normally only a subset is selected</p>
  <div class="test" id="testContainer"></div>

  </main>
  </html>
  <?php
}

# Controller for the multiple phases
chdir("/home/www/hpccertification/skill-tree-wiki/skill-tree/");

$id = $_GET["id"];
if($id == NULL || ! preg_match('/^[A-Za-z0-9_\/]*$/', $id)){
  print("<h1>Error loading the skill</h1><p>I contacted the admin to notify about the error</p>");
  exit(0);
}

// Generate the HTML for the user-specific examination OR grade it
if(array_key_exists("email", $_POST)){
  validate_question_submission($id);
  exit(0);
}

if(array_key_exists("id", $_GET)){
  view_question_submit($id);
  exit(0);
}

?>
