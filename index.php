<?php
    session_start();

    $usernameErr = $passwordErr = "";
    $username = $password = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["username"])) {
            $usernameErr = "Username is required";
        } else {
            $username = test_input($_POST["username"]);
        }

        if (empty($_POST["password"])) {
            $passwordErr = "Password is required";
        } else {
            $password = test_input($_POST["password"]);
        }

        if ($username == "admin") {
            if ($password == "password") {
                $_SESSION['admin'] = true;
                header('Location: admin.php');
                exit();
            } else {
                $passwordErr = "Incorrect password";
            }
        } else {
            $_SESSION['admin'] = false;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>Welcome to the Small Group Creator!</h1>
    <h2>Log In</h2>
    <form action="login.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username"></input>
        <br>
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password"></input>
        <br>
        <br>
        <input type="submit" value="Log In">
    </form>
</body>
</html>