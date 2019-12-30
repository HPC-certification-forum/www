<?php

$shared_key = "MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAtMCriwva0ACN39Oju5puyH6hdu8QEaTBJ37zBCOga3HZFs/ORoOnfECAzpgtmq8KcHr5+mVUtMOcSHMWU6ZgstS22JS9wfi2FufqHT6cf30NsqRw3iRz+PQSttms1T0CJTDQuCwAhL9vrpJq05/xtXwwro6+vEp4FO6xxO5dM1yExSWyum9xmjmm9vfinr1OqAHY8+MT4fOdIoLdV+gUehoIjYGQP2PdRVmdirNX3kcRST/84breYMFvyelb5UGfpfbIPkxOX4RcP7CzAuqonXasUCOc5wxVuayqcwwzngU/7XN9/Ja1QFVRIBpCLyMM22xzPHVWbPm1Ye0tEqbb7FzzEfaFx+PYThONDNZcbyMH+FuSXrPPmJ2IySOJ87TxkSt4NCBVlzE3e/iGxbudi5Oghg4/bRUpWKTYAOQlGIeWv4XrzaE1lnOvOAHD5zmMSeWA6SgBSb5QbYf00DH/cwc6IfBxYvxwf7ktpIKKHj7QfZarwVTIXlB0t3HLrRIlQt+vXPaFDW4O8lIhm4VnKBYMBXwD30j6XdGyEWon8LCtPLscRWS3KKPp972D5JrOPvYrTcU4GqO6ahPdGWqufGt8ZR+3CCLdHu7UBVq/rO7zXlJNe5RyKQITl78MJcfkLowzcZRgdRTMmn4R5Q2x5KfW/Rva2orZQeiEIw2fj9UCAwEAAQ";
$crypt_iv = "12749z452548756a";

function get_actual_link(){
  return 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function loadExam($exam){
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

function view_page_exam_check($db, $seed, $name, $data){
  $data = file_get_contents('php://input');
  if($data == NULL){
    print("<h1>Error</h1>");
    exit(0);
  }
  #header("Access-Control-Allow-Origin: *");
  header('Content-type:application/json;charset=utf-8');

  $descriptorspec = array(
     0 => array("pipe", "r"),
     1 => array("pipe", "w")
  );

  $proc = proc_open("./examiner -r " . $seed . " check", $descriptorspec, $pipes);
  if(! is_resource($proc)) {
      print("<h1>Error executing examination</h1>");
      exit(0);
  }

  fwrite($pipes[0], $data);
  fclose($pipes[0]);
  $score = trim(stream_get_contents($pipes[1]));
  fclose($pipes[1]);
  $return = proc_close($proc);
  if($return != 0 || $score == NULL){
    print('{ "return" : "1" }');
    exit(0);
  }

  $res = $db->query("UPDATE t SET score = ". $score . " where name == \"" . $db->escapeString($name) . "\"");
  if(! $res){
    print('{ "return" : "2" }');
  }else{
    print('{ "return" : "0" }');
  }

  $file = $name . "-" . $seed;
  $file = preg_replace('/[^A-Za-z0-9\-]/', '', $file);
  file_put_contents("./db/submissions/" . $file . ".md", $data);
}

function view_page_exam_generate($seed, $exam){
  # Generate examination
  header('Content-type:application/json;charset=utf-8');

  $output = NULL;
  $command = "./examiner -r " . $seed . " generate -i " . "./exam/" . $exam . ".md";
  exec($command, $output);
  print(implode("\n",$output));
}

function view_page_exam_generate_index_html($data, $name, $email, $affiliation, $crypted){
  // deliver index.html that actually starts the test by fetching the JSON
  ?>
  <!DOCTYPE html>
  <html>
  <head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title><?php print($data["name"]); ?></title>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/testGui.js"></script>
  </head>
  <body id="inner">
  <h1>Examination for "<em><?php print($data["name"]); ?>"</em></h1>
  <p>This multiple-choice test contains questions to obtain the certificate "<em><?php print($data["name"]); ?></em>" with the ID <?php print($data["id"]); ?>. Note that more than one answer may be correct for a question.</p>

	<script id="testMetadata" type="application/json">
		{
			"name": "<?php print($name); ?>",
			"email": "<?php print($email); ?>",
			"affiliation": "<?php print($affiliation); ?>"
		}
	</script>

  <div class="test" id="testContainer" data-test-url="?json&check=<?php print($crypted); ?>" data-submission-url="https://www.hpc-certification.org/examiner?check=<?php print($crypted); ?>"></div>
  </body>
  </html>
  <?php
}

function view_page_exam($shared_key, $crypt_iv){
  $crypted = $_REQUEST["check"];
  $decrypted = openssl_decrypt(strtr($crypted, '._-', '+/='), "AES256", $shared_key, 0, $crypt_iv);
  if(! $decrypted){
    print("<h1>We are sorry, the provided link is invalid</h1>");
    exit(0);
  }
  $data = json_decode($decrypted);
  if(count($data) != 5){
    print("<h1>We are sorry, the provided link is invalid</h1>");
    exit(0);
  }
  $time = $data[0];
  $exam = $data[1];
  $name = $data[2];
  $email = $data[3];
  $affiliation = $data[4];
  $data = loadExam($exam);

  // test timeout
  if( (time() - $time) > 24*3600){
    print("<h1>Timeout of the examination!</h1><p>An examination is only valid for 24 hours after the link is generated. Please restart your examination by requesting a new examination!</p>");
    exit(0);
  }

  // open the database that checks the registered submissions
  $db = new SQLite3('./db/examinations.db');
  if(! $db){
      print("<h1>Error, cannot open database!</h1>");
      exit(0);
  }
  $res = $db->query("create table if not exists t (deadline INTEGER, exam TEXT, name TEXT PRIMARY KEY, email TEXT, affiliation TEXT, seed INTEGER, score FLOAT)");

  $res = $db->query("select deadline, score, seed from t where name == \"" . $db->escapeString($name) . "\"");
  $found = $res->fetchArray();
  if($found){
    if( time() > $found["deadline"] ){
      print("<h1>Timeout of the examination!</h1><p>You have reached the deadline for submitting the examination, now you must wait until the end of the embargo period to retry this certificate!</p>");
      exit(0);
    }
    $seed = $found["seed"];
    if($found["score"] != -1){
      print("<h1>Test already submitted!</h1><p>You already submitted this test, now you must wait for the manual examination.</p>");
      exit(0);
    }
  }else{
    $seed = rand();
    // start the test now!
    $res = $db->query("insert into t VALUES (" . (time() + $data["deadline"]*60 + 5) . ",\"" . $db->escapeString($exam) . "\",\"" . $db->escapeString($name) . "\",\"" . $db->escapeString($email) . "\",\"" . $db->escapeString($affiliation) . "\"," . $seed . ", -1)");
    if(! $res){
      print("<h1>Error loading the page, please retry!</h1>");
      exit(1);
    }
  }

  // upload of the test
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    view_page_exam_check($db, $seed, $name, $data);
    exit(0);
  }

  // create the test
  if(array_key_exists("json", $_REQUEST)){
    view_page_exam_generate($seed, $exam);
    exit(0);
  }
  view_page_exam_generate_index_html($data, $name, $email, $affiliation, $crypted);
}

function view_page_show_all_examinations(){
  print("<h1>HPC Certification examination page</h1><p>Select your exam</p><ul>");

  $files = scandir("./exam/");
  foreach($files as $file) {
    if(strlen($file) < 4 || substr($file, strlen($file)-3, 3) != ".md") {
      continue;
    }
    $file = substr($file, 0, strlen($file)-3);

    print('<li><a href="' . get_actual_link() . '?exam=' . $file . '">' . $file . '</a></li>');
  }
  print("</ul>");
}

function view_page_register_submit($exam, $data, $shared_key, $crypt_iv){
  ?>
  <!DOCTYPE html>
  <html>
  <head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title><?php print($data["name"]); ?></title>
  </head>
  <body>
<?php
  if(array_key_exists("email", $_POST) && array_key_exists("affiliation", $_POST) && array_key_exists("name", $_POST)){
    if(strlen($_POST["email"]) < 6 || strlen($_POST["name"]) < 6 ){
      print("<h1>Invalid name or email</h1><h2>Please go back with your browser and ensure your email and name is correct.</h2>");
    }else if(! array_key_exists("privacy", $_POST) || $_POST["privacy"] != "on"){
      print("<h1>Check the privacy policy</h1><h2>Please go back with your browser, check the privacy statement and terms of the service, then mark the checkbox to proceed.</h2>");
    }else{
      $crypted = openssl_encrypt(json_encode([time(), $exam, $_POST["name"], $_POST["email"], $_POST["affiliation"]]), "AES256", $shared_key, 0, $crypt_iv);
      if($crypted){
        $crypted = strtr($crypted, '+/=', '._-');
        print("<h1>A confirmation link for your unique examination was sent to ". $_POST["email"] . "</h1><p>Please check your spam email. You can close this window now.</p>");
        $link = get_actual_link() . "&check=" . $crypted;

        $headers = "From: examination-chair@hpc-certification.org\r\n";
        $headers .= "Reply-To: examination-chair@hpc-certification.org\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $msg = '<p>This email contains the information how to conduct the examination for the certificate <em>"' . $data["name"]. '"</em> from the HPC Certification Forum.</p>
        <p>Make sure you are ready for the examination, because once the test is started you have a time limit to complete it.
        If you fail the test, you must wait for an embargo period to restart the examination.</p>
        <p> Click on the link to <a href="' . $link . '">Start the examination now</a>.</p>';
        mail($_POST["email"], "Start the examination for \"". $data["name"] . "\" now", $msg, $headers);
      }else{
        print("<h1>Unexpected error trying to notify you!</h1><h2>This should not happen! Please inform us about the problem.</h2>");
      }
    }
  }else{
    print("<h1>Error invalid form submitted</h1><h2>This should not happen! Please inform us about the problem.</h2>");
  }
?>
  </body>
  </html>
<?php
}

function view_page_register($data){
  ?>
  <!DOCTYPE html>
  <html>
  <head>
  	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<title><?php print($data["name"]); ?></title>
  </head>
  <body>
  <h1>Disclaimer</h1>
  <h2>Privacy statement</h2>
  <h3>The certificate</h3>
  <p>
    The certificate will show your name and affiliation, the month the exam was conducted and a description of the particular certificate.
    It will also include a unique URL that can be given to a third-party allowing it to verify that you have obtained the certificate.
    It will be produced as a PDF and transferred via email to you.
  </p>
  <h3>Data recorded</h3>
  <p>
    To generate a meaningful certificate, we need the following personal information: <b>name</b>, <b>affiliation</b> and <b>email address</b>.
    On the server side, we will also attach the <b>date</b> the test was submitted to this metadata.

    When you interact with the webserver, the typical server logs will be recorded as stated in <a href="https://www.hpc-certification.org/privacy/">the privacy statement</a>. These data will not be correlated to your personal information or the tests submitted here.
  </p>
  <h3>Data processing</h3>
  <p>
    We will use your personal data and the score to generate a certificate. This process may involve manual marking and approval of a staff member and the automatic generation of the certificate.
  </p>
  <p>
    Your name and email is used solely to contact you and send you the test results and certificate (in case of your failure, we will send you some information that allows the discussion of your test results).
    We will not disclose your personal information, the results, or the generated certificate with any third party.
    In case of a successful examination, this information will be deleted from the server immediately after the results are  processed and the  email has been sent to you.
    In case of an unsuccessful examination, to prevent re-examination within a cooldown period, we will store a hash of your name together with the date of the examination for a duration that is the resit period of the individual certificate.
    After that duration, we will delete the information such that you can be re-examined.
  </p>
  <p>
    We will record the information of (date, affiliation), i.e.,
    how many users with a certain affiliation have obtained a certain certificate.
    We will use this database of affiliations and date to promote the certification program (e.g., by mentioning that someone from an affiliation have obtained a certain certificate).
    This data will <b>not</b> contain your name, email address, or the score.
  </p>
  <p>
    We will record the achieved score separately from any personal information with the answered questions for the purpose to optimize the examination.
  </p>

  <h2>Terms of this service</h2>
  <p>We will take industry-typical precautions to prevent any cybercrime including theft of your personal data while your data is stored on an IT system involved in the data processing. However, in case of any data loss, theft of the data provided, we do not take liability for data loss or thefts caused by cybercrime.
  Note that includes cases in which minor careless actions of staff enabled third-parties to steal or compromise the information.</p>

  <h2>Process</h2>
  <ol>
  <li>Once you are ready, you register for conducting the examination; therefore, enter your personal information into this form and submit it.</li>
  <li>We will immediately send you an email with a link to your personal examination. This link is valid for 24 hours. <em>Check your spam folder if it does not arrive!</em></li>
  <li>The link will lead to a webpage with your examination questions, you have a <b>time limit for this particular examination of <?php print($data["deadline"]);?> minutes</b>.</li>
  <li>We will mark your exam and send your results or your certificate (typically, this takes a week).</li>
  <li>The certificate will include a unique URL allowing anyone to verify that you obtained the certificate and the date.</li>
  </ol>

  <h1>Examination for "<em><?php print($data["name"]); ?>"</em></h1>
  <p>This multiple-choice test contains questions to obtain the certificate "<em><?php print($data["name"]); ?></em>" with the ID <?php print($data["id"]); ?>.</p>

  <p><?php print($data["learning aim"]); ?></p>

  <p><b>Time limit</b>: <?php print($data["deadline"]);?> minutes</p>
  <p><b>Score to pass</b>: <?php print($data["minimum percentage"]);?>%</p>


  <form action="<?php print(get_actual_link() . "&state=submit"); ?>" method="post">
  <table>
  <tr><td>Name</td><td><input type="text" name="name"> (will be on the certificate)</td></tr>
  <tr><td>E-mail</td><td><input type="email" name="email"> (will be used to send you the certificate / the results)</td></tr>
  <tr><td>Affiliation</td><td><input type="text" name="affiliation"> (will be used for statistical purposes)</td></tr>
  </table>
  <p>
  I agree to the privacy policy and terms of this service
  <input type="checkbox" name="privacy">
  </p>

  <p>Press <b>Register for this examination</b> to transfer your request for examination together with the provided <b>personal information</b> to the server.</p>

  <input style="margin:20px" type="submit" value="Register for this examination!">
  </form>
</body>
</html>
<?php
}

# Controler of the multiple phases
chdir("/home/www/hpccertification/scripts");

// Generate the HTML for the user-specific examination OR grade it
if(array_key_exists("check", $_REQUEST)){
  view_page_exam($shared_key, $crypt_iv);
  exit(0);
}

if(! array_key_exists("exam", $_REQUEST)){
  view_page_show_all_examinations();
  exit(0);
}

$exam = $_REQUEST["exam"];
$data = loadExam($exam);
if(array_key_exists("state", $_REQUEST) && $_REQUEST["state"] == "submit"){
  view_page_register_submit($exam, $data, $shared_key, $crypt_iv);
  exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  view_page_register($data);
  exit(0);
}
?>
