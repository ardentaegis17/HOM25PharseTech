<?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        header('Location: login.php');
        exit();
    }

    require 'database.php';
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $memberDataErr = $minGroupSizeErr = $maxGroupSizeErr = "";
    $memberData = $minGroupSize = $maxGroupSize = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $spreadsheet = IOFactory::load($_FILES["member_data"]["tmp_name"]);
            $memberData = tempnam(sys_get_temp_dir(), 'member_data') . '.xlsx';
            $writer = new Xlsx($spreadsheet);
            $writer->save($memberData);
        } catch (Exception $e) {
            $memberDataErr = "Invalid file";
        }

        $minGroupSize = filter_input(INPUT_POST, 'min_group_size', FILTER_SANITIZE_NUMBER_INT);
        $maxGroupSize = filter_input(INPUT_POST, 'max_group_size', FILTER_SANITIZE_NUMBER_INT);
        
        if (empty($_FILES["member_data"]["name"])) {
            $memberDataErr = "Member data is required";
        } else if (empty($_POST["min_group_size"])) {
            $minGroupSizeErr = "Minimum group size is required";
        } else if ($_POST["min_group_size"] < 1) {
            $minGroupSizeErr = "Minimum group size must be at least 1";
        } else if (empty($_POST["max_group_size"])) {
            $maxGroupSizeErr = "Maximum group size is required";
        } else if ($_POST["max_group_size"] < 1) {
            $maxGroupSizeErr = "Maximum group size must be at least 1";
        } else if ($_POST["max_group_size"] < $minGroupSize) {
            $maxGroupSizeErr = "Maximum group size must be greater than or equal to minimum group size";
        } else {
            $py = shell_exec("python3 Data_algorithm_creation.py $memberData $minGroupSize $maxGroupSize");
            echo "<h2>$py</h2>";

            if (strpos ($py, "Error") !== false) {
                $memberDataErr = "Invalid file";
            } else {
                echo "<a href=\"groups.php\">View Groups</a>";
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
</head>
<body>
    <h1>Create Small Groups!</h1>
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <label for="member_data">Upload a CSV file with member data</label>
        <br>
        <input type="file" name="member_data" id="member_data" required></input>
        <span class="error"><?php echo $memberDataErr; ?></span>
        <br>
        <br>
        <label for="min_group_size">Minimum group Size</label>
        <input type="number" name="min_group_size" id="min_group_size" min="0" required></input>
        <span class="error"><?php echo $minGroupSizeErr; ?></span>
        <br>
        <label for="max_group_size">Maximum group Size</label>
        <input type="number" name="max_group_size" id="max_group_size" min="0" required></input>
        <span class="error"><?php echo $maxGroupSizeErr; ?></span>
        <br>
        <br>
        <input type="submit" value="Create">
    </form>
</body>
</html>