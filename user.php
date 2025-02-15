<?php
    session_start();

    include "database.php";

    $userPhotoErr = $nameErr = $phone_numberErr = $emailErr = $profile_link1Err = $profile_link2Err = $countryErr = $locationErr = $specializationErr = $sql_rankErr = $javascript_rankErr = $csharp_rankErr = $java_rankErr = $python_rankErr = $vb_rankErr = $cplus_rankErr = $c_rankErr = $ruby_rankErr = $golang_rankErr = $r_rankErr = $rust_rankErr = $gen_hobbies1Err = $gen_hobbies2Err = $showerErr = "";
    $name = $phone_number = $email = $profile_link1 = $profile_link2 = $country = $location = $specialization = $sql_rank = $javascript_rank = $csharp_rank = $java_rank = $python_rank = $vb_rank = $cplus_rank = $c_rank = $ruby_rank = $golang_rank = $r_rank = $rust_rank = $gen_hobbies1 = $gen_hobbies2 = $shower = "";
    
    $sql = "SELECT * FROM users";
    try {
        $users = $conn->query($sql);
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_photo = $_FILES["user_photo"]["name"];
        $userPhotoFileType = strtolower(pathinfo($user_photo, PATHINFO_EXTENSION));
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_NUMBER_INT);
        $profile_link1 = filter_input(INPUT_POST, 'profile_link1', FILTER_SANITIZE_URL);
        $profile_link2 = filter_input(INPUT_POST, 'profile_link2', FILTER_SANITIZE_URL);
        $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_SPECIAL_CHARS);
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_SPECIAL_CHARS);
        $specialization = filter_input(INPUT_POST, 'specialization', FILTER_SANITIZE_SPECIAL_CHARS);
        $sql_rank = filter_input(INPUT_POST, 'sql_rank', FILTER_SANITIZE_NUMBER_INT);
        $javascript_rank = filter_input(INPUT_POST, 'javascript_rank', FILTER_SANITIZE_NUMBER_INT);
        $csharp_rank = filter_input(INPUT_POST, 'csharp_rank', FILTER_SANITIZE_NUMBER_INT);
        $java_rank = filter_input(INPUT_POST, 'java_rank', FILTER_SANITIZE_NUMBER_INT);
        $python_rank = filter_input(INPUT_POST, 'python_rank', FILTER_SANITIZE_NUMBER_INT);
        $vb_rank = filter_input(INPUT_POST, 'vb_rank', FILTER_SANITIZE_NUMBER_INT);
        $cplus_rank = filter_input(INPUT_POST, 'cplus_rank', FILTER_SANITIZE_NUMBER_INT);
        $c_rank = filter_input(INPUT_POST, 'c_rank', FILTER_SANITIZE_NUMBER_INT);
        $ruby_rank = filter_input(INPUT_POST, 'ruby_rank', FILTER_SANITIZE_NUMBER_INT);
        $golang_rank = filter_input(INPUT_POST, 'golang_rank', FILTER_SANITIZE_NUMBER_INT);
        $r_rank = filter_input(INPUT_POST, 'r_rank', FILTER_SANITIZE_NUMBER_INT);
        $rust_rank = filter_input(INPUT_POST, 'rust_rank', FILTER_SANITIZE_NUMBER_INT);
        $gen_hobbies1 = filter_input(INPUT_POST, 'gen_hobbies1', FILTER_SANITIZE_SPECIAL_CHARS);
        $gen_hobbies2 = filter_input(INPUT_POST, 'gen_hobbies2', FILTER_SANITIZE_SPECIAL_CHARS);
        $shower = filter_input(INPUT_POST, 'shower', FILTER_SANITIZE_SPECIAL_CHARS);
        $check = getimagesize($_FILES["user_photo"]["tmp_name"]);
        
        if(empty($name)) {
            $nameErr = "Name is required";
        } else if(empty($email)) {
            $emailErr = "Email is required";
        } else if ($conn->query("SELECT * FROM users WHERE email = '$email'")->num_rows > 0) {
            $emailErr = "Email already exists";
        } else if(empty($country)) {
            $countryErr = "Country is required";
        } else if(empty($location)) {
            $locationErr = "Location is required";
        } else if(empty($specialization)) {
            $specializationErr = "Specialization is required";
        } else if(empty($sql_rank) && $sql_rank != 0) {
            $sql_rankErr = "SQL rank is required";
        } else if(empty($javascript_rank) && $javascript_rank != 0) {
            $javascript_rankErr = "Javascript rank is required";
        } else if(empty($csharp_rank) && $csharp_rank != 0) {
            $csharp_rankErr = "C# rank is required";
        } else if(empty($java_rank) && $java_rank != 0) {
            $java_rankErr = "Java rank is required";
        } else if(empty($python_rank) && $python_rank != 0) {
            $python_rankErr = "Python rank is required";
        } else if(empty($vb_rank) && $vb_rank != 0) {
            $vb_rankErr = "Visual Basic rank is required";
        } else if(empty($cplus_rank) && $cplus_rank != 0) {
            $cplus_rankErr = "C++ rank is required";
        } else if(empty($c_rank) && $c_rank != 0) {
            $c_rankErr = "C rank is required";
        } else if(empty($ruby_rank) && $ruby_rank != 0) {
            $ruby_rankErr = "Ruby rank is required";
        } else if(empty($golang_rank) && $golang_rank != 0) {
            $golang_rankErr = "Golang rank is required";
        } else if(empty($r_rank) && $r_rank != 0) {
            $r_rankErr = "R rank is required";
        } else if(empty($rust_rank) && $rust_rank != 0) {
            $rust_rankErr = "Rust rank is required";
        } else if(empty($gen_hobbies1) && $gen_hobbies1 != 0) {
            $gen_hobbies1Err = "General hobby 1 is required";
        } else if(empty($gen_hobbies2) && $gen_hobbies2 != 0) {
            $gen_hobbies2Err = "General hobby 2 is required";
        } else if (empty($check)) {
            $userPhotoErr = "Profile picture is required";
        } else if ($_FILES["user_photo"]["size"] > 500000) {
            $userPhotoErr = "Sorry, your file is too large.";
        } else if (
            $userPhotoFileType != "jpg" && 
            $userPhotoFileType != "png" && 
            $userPhotoFileType != "jpeg" && 
            $userPhotoFileType != "gif"
            ) {
            $userPhotoErr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            $sql = "INSERT INTO users (user_photo, name, phone_number, email, profile_link1, profile_link2, location, specialization, sql_rank, javascript_rank, csharp_rank, java_rank, python_rank, vb_rank, cplus_rank, c_rank, ruby_rank, golang_rank, r_rank, rust_rank, gen_hobbies1, gen_hobbies2)
                    VALUES ('$user_photo', '$name', '$phone_number', '$email', '$profile_link1', '$profile_link2', '$location', '$specialization', '$sql_rank', '$javascript_rank', '$csharp_rank', '$java_rank', '$python_rank', '$vb_rank', '$cplus_rank', '$c_rank', '$ruby_rank', '$golang_rank', '$r_rank', '$rust_rank', '$gen_hobbies1', '$gen_hobbies2')";
            try {
                $conn->query($sql);
            } catch (mysqli_sql_exception $e) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $_SESSION['user'] = $email;

            header('Location: index.php');
            exit();
        }
        mysqli_close($conn);
        session_destroy();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>User</title>
</head>
<body>
    <h1>Edit Profile</h1>
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST" enctype="multipart/form-data">
        <label for="user_photo">Profile Picture</label>
        <br>
        <input type="file" name="user_photo" accept="image/*" required></input>
        <span class="error"> <?php echo $userPhotoErr;?></span>
        <br>
        <br>
        <label for="name">Name: </label>
        <input type="text" name="name" placeholder="Your name" required></input>
        <span class="error"> <?php echo $nameErr;?></span>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="Your email" required></input>
        <span class="error"> <?php echo $emailErr;?></span>
        <br>
        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" placeholder="Your phone number"></input>
        <span class="error"> <?php echo $phone_numberErr;?></span>
        <br>
        <label for="profile_link1">Social Media Link 1:</label>
        <input type="url" name="profile_link1" placeholder="Your social media link"></input>
        <span class="error"> <?php echo $profile_link1Err;?></span>
        <br>
        <label for="profile_link2">Social Media Link 2:</label>
        <input type="url" name="profile_link2" placeholder="Your social media link"></input>
        <span class="error"> <?php echo $profile_link2Err;?></span>
        <br>
        <br>
        <label for="country">Country of Residence:</label>
        <select name="country" required>
            <option value="Singapore">Singapore</option>
        </select>
        <span class="error"> <?php echo $countryErr;?></span>
        <br>
        <label for="location">City/Town of Residence:</label>
        <select name="location" required>
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
        <span class="error"> <?php echo $locationErr;?></span>
        <br>
        <br>
        <label for="specialization">Choose your specialization:</label>
        <select name="specialization" id="specialization" required>
            <option value="Applications developer">Applications developer</option>
            <option value="Automations Engineer">Automations Engineer</option>
            <option value="Backend Engineer">Backend Engineer</option>
            <option value="Data Specialist">Data Specialist</option>
            <option value="Fullstack programmer">Fullstack programmer</option>
            <option value="Game developer">Game developer</option>
            <option value="UX UI Designer">UX UI Designer</option>
        </select>
        <span class="error"> <?php echo $specializationErr;?></span>
        <br>
        Rate your expertise from 0 to 10:
        <br>
        <label for="sql_rank">SQL</label>
        <input type="number" name="sql_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $sql_rankErr;?></span>
        <br>
        <label for="javascript_rank">Java Script</label>
        <input type="number" name="javascript_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $javascript_rankErr;?></span>
        <br>
        <label for="csharp_rank">C#</label>
        <input type="number" name="csharp_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $csharp_rankErr;?></span>
        <br>
        <label for="java_rank">Java</label>
        <input type="number" name="java_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $java_rankErr;?></span>
        <br>
        <label for="python_rank">Python</label>
        <input type="number" name="python_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $python_rankErr;?></span>
        <br>
        <label for="vb_rank">Visual Basic</label>
        <input type="number" name="vb_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $vb_rankErr;?></span>
        <br>
        <label for="cplus_rank">C++</label>
        <input type="number" name="cplus_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $cplus_rankErr;?></span>
        <br>
        <label for="c_rank">C</label>
        <input type="number" name="c_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $c_rankErr;?></span>
        <br>
        <label for="ruby_rank">Ruby</label>
        <input type="number" name="ruby_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $ruby_rankErr;?></span>
        <br>
        <label for="golang_rank">golang</label>
        <input type="number" name="golang_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $golang_rankErr;?></span>
        <br>
        <label for="r_rank">R</label>
        <input type="number" name="r_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $r_rankErr;?></span>
        <br>
        <label for="rust_rank">Rust</label>
        <input type="number" name="rust_rank" min="0" max="10" required></input>
        <span class="error"> <?php echo $rust_rankErr;?></span>
        <br>
        <br>
        Tell us about your hobbies:
        <br>
        <label for="gen_hobbies1">First Hobby: </label>
        <input type="text" name="gen_hobbies1" placeholder="Your First Hobby" <?php echo $gen_hobbies1Err;?>></input>
        <span class="error"> <?php echo $gen_hobbies1Err;?></span>
        <br>
        <label for="gen_hobbies2">Second Hobby: </label>
        <input type="text" name="gen_hobbies2" placeholder="Your Second Hobby" <?php echo $gen_hobbies2Err;?>></input>
        <span class="error"> <?php echo $gen_hobbies2Err;?></span>
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
        <input type="reset"></input>
    </form>
    <br>
    <a href="index.php">Back to home</a>
    <br>
    <br>
</body>
</html>

<?php
    
?>