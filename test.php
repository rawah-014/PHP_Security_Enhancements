
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

if (isset($_POST['token']) &&  isset($_POST['username']) && isset($_POST['password'])) {
  $token = $_POST['token'];
    $username = $_POST['username'];
  $password = $_POST['password'];
  $ip_address = $_SERVER['REMOTE_ADDR']; // Get user's IP address

  // Save login attempt to database
  $stmt = $db->prepare("INSERT INTO login_attempts (ip_address, attempt_time) VALUES (?, NOW())");
  $stmt->bind_param('s', $ip_address);
  $stmt->execute();
  $stmt->close();

  // Send login request to remote server
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://zainblue.com/dologin.php');
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'token' => $token,
    'username' => $username,
    'password' => $password,
  )));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  file_put_contents("log.txt", $response?"true":"false");
  curl_close($ch);
 // var_dump($response);

  // Check if login was successful
  if (strpos($response, 'login') !== false) {
    // Login failed, increment login attempts and save time of last attempt
    $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
    $_SESSION['last_attempt_time'] = time();

    // Display error message to user
    if ($_SESSION['login_attempts'] < 3) {
      echo 'Login failed. Please try again.';
    } else {
      echo 'Too many login attempts. Please wait 3 minutes before trying again.';
    }
  } else {
    // Login successful, redirect to ZainBlue dashboard page
    header('Location: https://zainblue.com/clientarea.php?language=english');
    exit();
  }
}
?>


<form method="post">
<input type="hidden" name="token" value="be619432a134ab558dc73e35527d29b512eb2463">
  <label for="email">Email:</label>
  <input type="text" name="username" required>
  <br>
  <label for="password">Password:</label>
  <input type="password" name="password" required>
  <br>
  <button type="submit">Login</button>
</form>