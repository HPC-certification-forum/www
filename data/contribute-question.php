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
      $out[strtolower($first)] = $txt;
    }
  }
  if(! array_key_exists("outcomes", $out)){
    print("<h1>Error loading the skill</h1><p>I contacted the admin to notify about the error</p>");
    exit(0);
  }
  return $out;
}

function clean_str($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function validate_question_submission($id){
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>Submission Form for Exam Questions</title
      <link rel="stylesheet" href="/css/question-submission.css">
    </head>
    <body id="inner">
    <?php
      $file = $id . ".txt";
      if(! file_exists($file)){
        print("<h1>Error loading the skill</h1><p>I contacted the admin to notify about the error</p>");
        exit(0);
      }
      $name = clean_str($_POST["los"]);
      if($name == "unselected"){
        print("<h1>You must select a valid learning objective</h1>");
        exit(0);
      }
      $path = "/home/www/hpccertification/skills/" . $id;
      mkdir($path, 0755, true);

      $data = "";
      if($_POST["contact"] != ""){
        $data = "#contributor: " . $_POST["contact"] . "\n";
      }
      $data = $data . $_POST["question"] . "\n\n#select multiple\n\n";
      for($i=0; $i < 15; $i++){
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

      $ret = file_put_contents($path . "/" . $name, $data, LOCK_EX|FILE_APPEND);
      if($ret){
        echo("<h1>Thank you for submitting your question</h1>");
      }else{
        echo("<h1 style='color:red'>Error submitting the question, please contact HPCCF!</h1>");
      }
    ?>
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
  	<title>Submission Form for Exam Questions</title
    <link rel="stylesheet" href="/css/question-submission.css">
  </head>
  <body id="inner">
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
    By submitting a question <b>you explicitly agree</b> <a href="http://hpc-certification.org/contribute-license/">to the terms for contributors</a>.</p>

    <h3> Contact (Optional) </h3>

    <p>
    <em>We give credit to all contributors of whom we select questions. If you provide your details here, we will add you to the contributor list. Leave it blank to submit a question anonymously.</em>

    <div>
    <label for="contact">Your name &lt;Email&gt;</label>
    <input type="text" id="contact" name="contact" pattern=".* <.*@.*>" size="50">
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
      foreach($data["outcomes"] as $outcome){
        if(trim($outcome) == "") continue;
        echo('<option value="' . $outcome . '">' . $outcome . '</option>');
      }
      ?>
    </select>
    </p>

    <h3><label for="question">Question</label></h3>
    <p>
    <textarea id="question" name="question" rows="4" cols="100"></textarea>
    </p>

    <h3>Answers</h3>

    <p>
      <em>Provide short answers; an answer could use multiple lines. Please try to balance the number of correct and wrong answers. Tick the box if an answer is correct.</em>
    </p>

    <p>
    <?php
    for($i=0; $i < 15; $i++){
      echo('<div>Correct</label><input type="checkbox" name="o' . $i . '" value="o' . $i . '"><label for="a' . $i . '">Answer</label><textarea type="text" name="a' . $i . '" value=""  rows="1" cols="100"></textarea><label for="o' . $i . '"></div>');
    }
    ?>

    </p>

    <input type="submit" value="Submit">
  </form>

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
if(array_key_exists("contact", $_POST)){
  validate_question_submission($id);
  exit(0);
}

if(array_key_exists("id", $_GET)){
  view_question_submit($id);
  exit(0);
}

?>
