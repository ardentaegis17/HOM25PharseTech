<?php
    session_start();

    $usernameErr = $passwordErr = "";
    $username = $password = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["username"])) {
            $usernameErr = "Username is required";
        } else {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        }

        if (empty($_POST["password"])) {
            $passwordErr = "Password is required";
        } else {
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
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
            header('Location: user.php');
            exit();
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
    <form action="index.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username"></input>
        <span class="error"><?php echo $usernameErr; ?></span>
        <br>
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password"></input>
        <span class="error"><?php echo $passwordErr; ?></span>
        <br>
        <br>
        <input type="submit" value="Log In">
    </form>
</body>
</html>