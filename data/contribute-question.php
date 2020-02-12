<?php

function get_actual_link(){
  return 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function load_markdown($exam){
  $file = "./exam/" . $exam . ".md";
  if($exam == NULL || ! preg_match('/^[A-Za-z0-9_]*$/', $exam) || ! file_exists($file)){
    print("<h1>HPC Certification examination page!</h1><p>Use it with proper tools</p>");
    exit(0);
  }

  $file = "./exam/" . $exam . ".json";
  if(! file_exists($file)){
    print("Error in the exam description! This should not happen...");
    exit(0);
  }
  return json_decode(file_get_contents($file), true);
}

function view_question_submit($id){
  ?>
  <!DOCTYPE html>
  <html>
  <head>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title>Submission Form for Exam Questions</title
  </head>
  <body id="inner">
  <?php print($data["name"]); ?>"

  </html>
  <?php
}

# Controller for the multiple phases
chdir("/home/www/hpccertification/");

// Generate the HTML for the user-specific examination OR grade it
if(array_key_exists("id", $_REQUEST)){
  view_question_submit($_REQUEST["id"]);
  exit(0);
}
?>
<h1>Invalid request...</h1>
