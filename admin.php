<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        header('Location: login.php');
        exit();
    }

    require 'database.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // I'll do this later
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
</head>
<body>
    <h1>Create Small Groups!</h1>
    <form action="create.php" method="post">
        <label for="member_data">Upload a CSV file with member data</label>
        <br>
        <input type="file" name="member_data" id="member_data"></input>
        <br>
        <br>
        <label for="min_group_size">Minimum group Size</label>
        <input type="number" name="min_group_size" id="min_group_size"></input>
        <br>
        <label for="max_group_size">Maximum group Size</label>
        <input type="number" name="max_group_size" id="max_group_size"></input>
        <br>
        <br>
        <input type="submit" value="Create">
    </form>
</body>