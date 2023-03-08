<?php

session_start();
$db = new mysqli('localhost', 'root', '', 'phploginapp');

// Check if user has attempted to login three times in the last 3 minutes
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3 && time() - $_SESSION['last_attempt_time'] <= 180) {
  // Block user's IP address for 3 minutes
  header('HTTP/1.1 429 Too Many Requests');
  header('Retry-After: 180');
  exit('Too many login attempts. Please try again later.');
}


// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the username and password from the form
  $username = $_POST['username'];
  $password = $_POST['password'];
  $ip_address = $_SERVER['REMOTE_ADDR']; // Get user's IP address

   // Save login attempt to database
   $stmt = $db->prepare("INSERT INTO login_attempts (ip_address, attempt_time) VALUES (?, NOW())");
   $stmt->bind_param('s', $ip_address);
   $stmt->execute();
   $stmt->close();

  // Create a cURL handle
  $ch = curl_init();

  // Set the cURL options
  curl_setopt($ch, CURLOPT_URL, 'https://zainblue.com/dologin.php');
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, array(
    'username' => $username,
    'password' => $password
  ));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  // Send the request and get the response
  $response = curl_exec($ch);

  // Check if the authentication was successful
  if (curl_getinfo($ch, CURLINFO_HTTP_CODE) === 302 &&
      strpos(curl_getinfo($ch, CURLINFO_REDIRECT_URL), 'incorrect=true') === false /* &&
      strpos($response, 'Set-Cookie: WHMCSUser=') !== false */) {
    // Redirect the user to the clientarea.php page
    header('Location: https://zainblue.com/clientarea.php?language=english');
    exit();
  } else {

    $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
    $_SESSION['last_attempt_time'] = time();

    // Display error message to user
    if ($_SESSION['login_attempts'] < 3) {
      echo 'Login failed. Please try again.';
    } else {
      echo 'Too many login attempts. Please wait 3 minutes before trying again.';
    }
    // Show an alert that the login failed
    echo '<script>alert("Login failed. Please try again.")</script>';
  }

  // Close the cURL handle
  curl_close($ch);
}
?>

<!-- Create the login form -->
<form method="post">
  <label for="username">Username:</label>
  <input type="text" name="username" required>
  <br>
  <label for="password">Password:</label>
  <input type="password" name="password" required>
  <br>
  <button type="submit">Login</button>
</form>
