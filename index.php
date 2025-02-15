<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <h1>Log In</h1>
    <form action="login.php" method="post">
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" required />
      <br />
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required />
      <br />
      <input type="submit" value="Log In" />
    </form>
  </body>
</html>

<?php
  $userErr = $passErr = "";
  $user = $pass = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["user"])) {
          $userErr = "Username is required";
      } else {
          $user = test_input($_POST["user"]);
          // check if name only contains letters and whitespace
          if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
              $nameErr = "Only letters and white space allowed";
          } else {
              $_SESSION["user"] = $user;
          }
      }

      if (empty($_POST["pass"])) {
          $passErr = "Password is required";
      } else {
          $pass = test_input($_POST["pass"]);
          // check if password contains 8 characters
          if (strlen($pass) < 8) {
              $passErr = "Your password is too short";
          } else {
              $_SESSION["pass"] = $pass;
          }
      }

      if (
          !empty($_SESSION["user"]) and
          !empty($_SESSION["pass"])
      ) {
          header("Location: home.php"); // redirect to home page
      }
  }

  function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
  }
?>