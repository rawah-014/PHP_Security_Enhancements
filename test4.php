<?php

session_start();
$db = new mysqli('localhost', 'root', '', '');

/* ---------------------------- Send http request --------------------------- */
function sendHttpRequest($url, $data = array(), $headers = array())
{
  // initiate the curl request
  $curl = curl_init();
  // set the request options
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://zainblue.com/dologin.php', // url to send the request to
    CURLOPT_RETURNTRANSFER => true, // return the response as a string
    CURLOPT_ENCODING => '', // encoding type not required it's by default empty
    CURLOPT_MAXREDIRS => 10, // max redirects to follow we set this because maybe the url has redirects and not return the response directly
    CURLOPT_TIMEOUT => 0, // timeout 0 means no timeout
    CURLOPT_FOLLOWLOCATION => true, // follow redirects if any redirect from them
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // http version not required it's by default 1.1
    CURLOPT_CUSTOMREQUEST => 'POST', // request method
    CURLOPT_POSTFIELDS => $data, // request data
    CURLOPT_HTTPHEADER => $headers, // request headers if any
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}

// Check if user has attempted to login three times in the last 3 minutes
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3 && time() - $_SESSION['last_attempt_time'] <= 600) {
  // Block user's IP address for 10 minutes
  header('HTTP/1.1 429 Too Many Requests');
  header('Retry-After: 600');
  exit('Too many login attempts. Please try again later.');
}


// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the username and password from the form
  $username = $_POST['username'];
  $password = $_POST['password'];
  $ip_address = $_SERVER['REMOTE_ADDR']; // Get user's IP address


    // Validate the reCAPTCHA response
    $recaptcha_secret = "Your_key";
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response";
    $recaptcha = json_decode(file_get_contents($recaptcha_url));
    if (!$recaptcha->success) {
      // reCAPTCHA validation failed, show an error message and exit
      exit("reCAPTCHA validation failed.");
    }

   // Save login attempt to database
   $stmt = $db->prepare("INSERT INTO login_attempts (ip_address, attempt_time) VALUES (?, NOW())");
   $stmt->bind_param('s', $ip_address);
   $stmt->execute();
   $stmt->close();

  // Create a request to the zainblue.com to authenticate the user
  $response = sendHttpRequest('https://zainblue.com/dologin.php', array(
    'username' => $username,
    'password' => $password,
  ));

  // if the response text contains the string "incorrect" then the login failed
  if (strpos($response, 'Incorrect') !== false) {
    $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
    $_SESSION['last_attempt_time'] = time();

    // Display error message to user
    if ($_SESSION['login_attempts'] < 3) {
      echo 'Login failed. Please try again.';
    } else {
      echo 'Too many login attempts. Please wait 10 minutes before trying again.';
    }
  } else {
    // Redirect the user to the clientarea.php page
    header('Location: https://zainblue.com/clientarea.php?language=english');
    exit();
  }
}
?>

<!--  google captcha -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script src="https://www.google.com/recaptcha/api.js?render=Your_key"></script>
<!-- Create the login form -->
<form method="post">
  <label for="username">Username:</label>
  <input type="text" name="username" required>
  <br>
  <label for="password">Password:</label>
  <input type="password" name="password" required>
  <br>
  <div class="g-recaptcha" data-sitekey="Your_key"></div>
  <br>
  <button type="submit">Login</button>
</form>

