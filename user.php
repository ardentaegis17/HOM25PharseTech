<?php
    session_start();

    include "database.php";

    $nameErr = $profilePictureErr = "";
    $name = "";
    $hobbiesErr = array("", "");
    $hobbies = array("", "");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile-picture"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["profile-picture"]["tmp_name"]);
        if (empty($check)) {
            $profilePictureErr = "File is not an image.";
            $uploadOk = 0;
        } else if (file_exists($target_file)) {
            $profilePictureErr = "Sorry, file already exists.";
            $uploadOk = 0;
        } else if ($_FILES["profile-picture"]["size"] > 500000) {
            $profilePictureErr = "Sorry, your file is too large.";
            $uploadOk = 0;
        } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $profilePictureErr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        } else if ($uploadOk == 0) {
            $profilePictureErr = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["profile-picture"]["tmp_name"], $target_file)) {
                $profilePictureErr = "The file ". htmlspecialchars( basename( $_FILES["profile-picture"]["name"])). " has been uploaded.";
            } else {
                $profilePictureErr = "Sorry, there was an error uploading your file.";
            }
        }

        if (empty($_POST["user"])) {
            $nameErr = "Username is required";
        } else {
            $user = test_input($_POST["user"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                $nameErr = "Only letters and white space allowed";
            } else {
                $_SESSION["user"] = $user;
            }
        }

        if (!empty($_POST["hobby1"])) {
            array_push($hobbies, test_input($_POST["hobby1"]));
            if (!preg_match("/^[a-zA-Z-' ]*$/",$hobbies[0])) {
                array_push($hobbiesErr, "Only letters and white space allowed");
            }
        }

        if (!empty($_POST["hobby2"])) {
            array_push($hobbies, test_input($_POST["hobby2"]));
            if (!preg_match("/^[a-zA-Z-' ]*$/",$hobbies[1])) {
                array_push($hobbiesErr, "Only letters and white space allowed");
            }
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>User</title>
</head>
<body>
    <h1>Welcome, user!</h1>
    <form action="user.php" method="POST" enctype="multipart/form-data">
        <label for="profile-picture">Picture</label>
        <input type="file" name="profile-picture" accept="image/*" required></input>
        <br>
        <br>
        <label for="name">Name</label>
        <input type="text" name="name" placeholder="Your name" required></input>
        <span class="error"> <?php echo $nameErr;?></span>
        <br>
        <br>
        <label for="age-range">Age:</label>
        <select name="age-range" id="age-range" required>
            <option value="0-17">0-17</option>
            <option value="18-25">18-25</option>
            <option value="26-35">26-35</option>
            <option value="36-50">36-50</option>
            <option value="51+">51+</option>
        </select>
        <br>
        <br>
        <label for="field">Choose your field:</label>
        <select name="field" id="field" required>
            <option value="Data Specialist">Data Specialist</option>
            <option value="Backend Engineer">Backend Engineer</option>
            <option value="UX UI Designer">UX UI Designer</option>
            <option value="Fullstack programmer">Fullstack programmer</option>
            <option value="Applications developer">Applications developer</option>
            <option value="Automations Engineer">Automations Engineer</option>
            <option value="Game developer">Game developer</option>
        </select>
        <br>
        <br>
        Rate your expertise from 1 to 10:
        <br>
        <label for="SQL">SQL</label>
        <input type="number" name="SQL" min="0" max="10" required></input>
        <br>
        <label for="Java Script">Java Script</label>
        <input type="number" name="Java Script" min="0" max="10" required></input>
        <br>
        <label for="C#">C#</label>
        <input type="number" name="C#" min="0" max="10" required></input>
        <br>
        <label for="Java">Java</label>
        <input type="number" name="Java" min="0" max="10" required></input>
        <br>
        <label for="Python">Python</label>
        <input type="number" name="Python" min="0" max="10" required></input>
        <br>
        <label for="Visual basic">Visual Basic</label>
        <input type="number" name="Visual basic" min="0" max="10" required></input>
        <br>
        <label for="C++">C++</label>
        <input type="number" name="C++" min="0" max="10" required></input>
        <br>
        <label for="C">C</label>
        <input type="number" name="C" min="0" max="10" required></input>
        <br>
        <label for="Ruby">Ruby</label>
        <input type="number" name="Ruby" min="0" max="10" required></input>
        <br>
        <label for="golang">golang</label>
        <input type="number" name="golang" min="0" max="10" required></input>
        <br>
        <label for="R">R</label>
        <input type="number" name="R" min="0" max="10" required></input>
        <br>
        <label for="Rust">Rust</label>
        <input type="number" name="Rust" min="0" max="10" required></input>
        <br>
        <br>
        <label for="experience">Years of experience:</label>
        <select name="experience" id="experience" required>
            <option value="0-1">0-1</option>
            <option value="2-4">2-4</option>
            <option value="5-7">5-7</option>
            <option value="8-10">8-10</option>
            <option value="10+">10+</option>
        </select>
        <br>
        <br>
        <label for="country">Country of Residence:</label>
        <select name="country" required>
            <option value="Singapore">Singapore</option>
        </select>
        <br>
        <label for="city">City/Town of Residence:</label>
        <select name="city" required>
            <option value="Ang Mo Kio">Ang Mo Kio</option>
            <option value="Bedok">Bedok</option>
            <option value="Bishan">Bishan</option>
            <option value="Bukit Batok">Bukit Batok</option>
            <option value="Bukit Merah">Bukit Merah</option>
            <option value="Bukit Panjang">Bukit Panjang</option>
            <option value="Bukit Timah">Bukit Timah</option>
            <option value="Central Area">Central Area</option>
            <option value="Choa Chu Kang">Choa Chu Kang</option>
            <option value="Clementi">Clementi</option>
            <option value="Geylang">Geylang</option>
            <option value="Hougang">Hougang</option>
            <option value="Jurong East">Jurong East</option>
            <option value="Jurong West">Jurong West</option>
            <option value="Kallang/Whampoa">Kallang/Whampoa</option>
            <option value="Marine Parade">Marine Parade</option>
            <option value="Pasir Ris">Pasir Ris</option>
            <option value="Punggol">Punggol</option>
            <option value="Queenstown">Queenstown</option>
            <option value="Sembawang">Sembawang</option>
            <option value="Sengkang">Sengkang</option>
            <option value="Serangoon">Serangoon</option>
            <option value="Tampines">Tampines</option>
            <option value="Toa Payoh">Toa Payoh</option>
            <option value="Woodlands">Woodlands</option>
            <option value="Yishun">Yishun</option>
        </select>
        <br>
        <br>
        Tell us about your hobbies:
        <br>
        <label for="hobby1">First Hobby</label>
        <input type="text" name="hobby1" placeholder="Your First Hobby" <?php echo $hobbiesErr[0];?>></input>
        <br>
        <label for="hobby2">Second Hobby</label>
        <input type="text" name="hobby2" placeholder="Your Second Hobby" <?php echo $hobbiesErr[1];?>></input>
        <br>
        <br>
        <label for="funny-question">Do you shower? (just kidding, its not required)</label>
        <br>
        <input type="radio" id="yes" name="shower" value="yes">
        <label for="yes">Yes</label>
        <br>
        <input type="radio" id="no" name="shower" value="no">
        <label for="no">No</label>
        <br>
        <br>
        <button type="submit">Submit</button>
        <br>
        <br>
        <input type="reset"></input>
    </form>
    <a href="index.php">Back to home</a>
</body>
</html>
