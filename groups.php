<?php
    include 'database.php';

    $groups = array();

    $sql = "SELECT * FROM users";
    try {
        $users = $conn->query($sql);
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    foreach ($users as $user) {
        $user_group = $user['groupings_cluster'];
        if (empty($groups[$user_group])) {
            $groups += array($user_group => array());
        }
        array_push($groups[$user_group], $user['email']);
    }

    foreach ($groups as $group => $members) {
        echo "<h2>Group $group</h2>";
        echo "<ul>";
        foreach ($members as $member) {
            echo "<li>$member</li>";
        }
        echo "</ul>";
    }
?>