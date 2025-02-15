<?php
    session_start();

    include "database.php";

    $profilePictureErr = $nameErr = $ageErr = $phone_numberErr = $emailErr = $profile_link1Err = $profile_link2Err = $countryErr = $locationErr = $specializationErr = $years_of_expErr = $sql_rankErr = $javascript_rankErr = $csharp_rankErr = $java_rankErr = $python_rankErr = $vb_rankErr = $cplus_rankErr = $c_rankErr = $ruby_rankErr = $golang_rankErr = $r_rankErr = $rust_rankErr = $gen_hobbies1Err = $gen_hobbies2Err = $showerErr = "";
    $name = $age = $phone_number = $email = $profile_link1 = $profile_link2 = $country = $location = $specialization = $years_of_exp = $sql_rank = $javascript_rank = $csharp_rank = $java_rank = $python_rank = $vb_rank = $cplus_rank = $c_rank = $ruby_rank = $golang_rank = $r_rank = $rust_rank = $gen_hobbies1 = $gen_hobbies2 = $shower = "";
    $target_dir = "uploads/";
    $uploadOk = 1;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $target_file = $target_dir . basename($_FILES["user_photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["user_photo"]["tmp_name"]);
        if (empty($check)) {
            $profilePictureErr = "Profile.";
            $uploadOk = 0;
        } else if (file_exists($target_file)) {
            $profilePictureErr = "Sorry, file already exists.";
            $uploadOk = 0;
        } else if ($_FILES["user_photo"]["size"] > 500000) {
            $profilePictureErr = "Sorry, your file is too large.";
            $uploadOk = 0;
        } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $profilePictureErr = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        } else if ($uploadOk == 0) {
            $profilePictureErr = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["user_photo"]["tmp_name"], $target_file)) {
                $profilePictureErr = "The file ". htmlspecialchars( basename( $_FILES["user_photo"]["name"])). " has been uploaded.";
                $uploadOk = 1;
            } else {
                $profilePictureErr = "Sorry, there was an error uploading your file.";
                $uploadOk = 0;
            }
        }

        if (empty($_POST["name"])) {
            $nameErr = "name is required";
        } else {
            $name = test_input($_POST["name"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                $nameErr = "Only letters and white space allowed";
            } else {
                $_SESSION["name"] = $name;
                $nameErr = "";
            }
        }
        
        if (!empty($_POST["age"])) {
            $age = test_input($_POST["age"]);
        }

        if (!empty($_POST["phone_number"])) {
            $phone_number = test_input($_POST["phone_number"]);
            if (!preg_match("/^[0-9]*$/",$phone_number)) {
                $phone_numberErr = "Only numbers allowed";
            } else {
                $phone_numberErr = "";
            }
        }

        if (!empty($_POST["email"])) {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            } else {
                $emailErr = "";
            }
        }

        if (!empty($_POST["profile_link1"])) {
            $profile_link1 = test_input($_POST["profile_link1"]);
            if (!filter_var($profile_link1, FILTER_VALIDATE_URL)) {
                $profile_link1Err = "Invalid URL";
            } else {
                $profile_link1Err = "";
            }
        }

        if (!empty($_POST["profile_link2"])) {
            $profile_link2 = test_input($_POST["profile_link2"]);
            if (!filter_var($profile_link2, FILTER_VALIDATE_URL)) {
                $profile_link2Err = "Invalid URL";
            } else {
                $profile_link2Err = "";
            }
        }
        
        if (empty($_POST["country"])) {
            $countryErr = "country is required";
        } else {
            $country = test_input($_POST["country"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$country)) {
                $countryErr = "Only letters and white space allowed";
            } else {
                $countryErr = "";
            }
        }

        if (empty($_POST["location"])) {
            $locationErr = "location is required";
        } else {
            $location = test_input($_POST["location"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$location)) {
                $locationErr = "Only letters and white space allowed";
            } else {
                $locationErr = "";
            }
        }

        if (empty($_POST["specialization"])) {
            $specializationErr = "specialization is required";
        } else {
            $specialization = test_input($_POST["specialization"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$specialization)) {
                $specializationErr = "Only letters and white space allowed";
            } else {
                $specializationErr = "";
            }
        }

        if (empty($_POST["years_of_exp"])) {
            $years_of_expErr = "years_of_exp is required";
        } else {
            $years_of_exp = test_input($_POST["years_of_exp"]);
            if (!preg_match("/^[0-9-]*$/",$years_of_exp)) {
                $years_of_expErr = "Only letters and hyphens allowed";
            } else {
                $years_of_expErr = "";
            }
        }

        if (empty($_POST["sql_rank"])) {
            $sql_rankErr = "sql_rank is required";
        } else {
            $sql_rank = test_input($_POST["sql_rank"]);
            if (!preg_match("/^[0-9]*$/",$sql_rank)) {
                $sql_rankErr = "Only numbers allowed";
            } else {
                $sql_rankErr = "";
            }
        }

        if (empty($_POST["javascript_rank"])) {
            $javascript_rankErr = "javascript_rank is required";
        } else {
            $javascript_rank = test_input($_POST["javascript_rank"]);
            if (!preg_match("/^[0-9]*$/",$javascript_rank)) {
                $javascript_rankErr = "Only numbers allowed";
            } else {
                $javascript_rankErr = "";
            }
        }

        if (empty($_POST["csharp_rank"])) {
            $csharp_rankErr = "csharp_rank is required";
        } else {
            $csharp_rank = test_input($_POST["csharp_rank"]);
            if (!preg_match("/^[0-9]*$/",$csharp_rank)) {
                $csharp_rankErr = "Only numbers allowed";
            } else {
                $csharp_rankErr = "";
            }
        }

        if (empty($_POST["java_rank"])) {
            $java_rankErr = "java_rank is required";
        } else {
            $java_rank = test_input($_POST["java_rank"]);
            if (!preg_match("/^[0-9]*$/",$java_rank)) {
                $java_rankErr = "Only numbers allowed";
            } else {
                $java_rankErr = "";
            }
        }

        if (empty($_POST["python_rank"])) {
            $python_rankErr = "python_rank is required";
        } else {
            $python_rank = test_input($_POST["python_rank"]);
            if (!preg_match("/^[0-9]*$/",$python_rank)) {
                $python_rankErr = "Only numbers allowed";
            } else {
                $python_rankErr = "";
            }
        }

        if (empty($_POST["vb_rank"])) {
            $vb_rankErr = "vb_rank is required";
        } else {
            $vb_rank = test_input($_POST["vb_rank"]);
            if (!preg_match("/^[0-9]*$/",$vb_rank)) {
                $vb_rankErr = "Only numbers allowed";
            } else {
                $vb_rankErr = "";
            }
        }

        if (empty($_POST["cplus_rank"])) {
            $cplus_rankErr = "cplus_rank is required";
        } else {
            $cplus_rank = test_input($_POST["cplus_rank"]);
            if (!preg_match("/^[0-9]*$/",$cplus_rank)) {
                $cplus_rankErr = "Only numbers allowed";
            } else {
                $cplus_rankErr = "";
            }
        }

        if (empty($_POST["c_rank"])) {
            $c_rankErr = "c_rank is required";
        } else {
            $c_rank = test_input($_POST["c_rank"]);
            if (!preg_match("/^[0-9]*$/",$c_rank)) {
                $c_rankErr = "Only numbers allowed";
            } else {
                $c_rankErr = "";
            }
        }

        if (empty($_POST["ruby_rank"])) {
            $ruby_rankErr = "ruby_rank is required";
        } else {
            $ruby_rank = test_input($_POST["ruby_rank"]);
            if (!preg_match("/^[0-9]*$/",$ruby_rank)) {
                $ruby_rankErr = "Only numbers allowed";
            } else {
                $ruby_rankErr = "";
            }
        }

        if (empty($_POST["golang_rank"])) {
            $golang_rankErr = "golang_rank is required";
        } else {
            $golang_rank = test_input($_POST["golang_rank"]);
            if (!preg_match("/^[0-9]*$/",$golang_rank)) {
                $golang_rankErr = "Only numbers allowed";
            } else {
                $golang_rankErr = "";
            }
        }

        if (empty($_POST["r_rank"])) {
            $r_rankErr = "r_rank is required";
        } else {
            $r_rank = test_input($_POST["r_rank"]);
            if (!preg_match("/^[0-9]*$/",$r_rank)) {
                $r_rankErr = "Only numbers allowed";
            } else {
                $r_rankErr = "";
            }
        }

        if (empty($_POST["rust_rank"])) {
            $rust_rankErr = "rust_rank is required";
        } else {
            $rust_rank = test_input($_POST["rust_rank"]);
            if (!preg_match("/^[0-9]*$/",$rust_rank)) {
                $rust_rankErr = "Only numbers allowed";
            } else {
                $rust_rankErr = "";
            }
        }

        if (!empty($_POST["gen_hobbies1"])) {
            $gen_hobbies1 = test_input($_POST["gen_hobbies1"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$gen_hobbies1)) {
                $gen_hobbies1Err = "Only letters and white space allowed";
            } else {
                $gen_hobbies1Err = "";
            }
        }

        if (!empty($_POST["gen_hobbies2"])) {
            $gen_hobbies2 = test_input($_POST["gen_hobbies2"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$gen_hobbies2)) {
                $gen_hobbies2Err = "Only letters and white space allowed";
            } else {
                $gen_hobbies2Err = "";
            }
        }

        $sql = "INSERT INTO user (name, age, phone_number, email, profile_link1, profile_link2, country, location, specialization, years_of_exp, sql_rank, javascript_rank, csharp_rank, java_rank, python_rank, vb_rank, cplus_rank, c_rank, ruby_rank, golang_rank, r_rank, rust_rank, gen_hobbies1, gen_hobbies2, shower)
                VALUES ('$name', '$age', '$phone_number', '$email', '$profile_link1', '$profile_link2', '$country', '$location', '$specialization', '$years_of_exp', '$sql_rank', '$javascript_rank', '$csharp_rank', '$java_rank', '$python_rank', '$vb_rank', '$cplus_rank', '$c_rank', '$ruby_rank', '$golang_rank', '$r_rank', '$rust_rank', '$gen_hobbies1', '$gen_hobbies2', '$shower')";
        
        try {
            $conn->query($sql);
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        
        mysqli_close($conn);
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
    <title>name</title>
</head>
<body>
    <h1>Welcome, name!</h1>
    <form action="name.php" method="POST" enctype="multipart/form-data">
        <label for="user_photo">Picture</label>
        <input type="file" name="user_photo" accept="image/*" required></input>
        <br>
        <br>
        <label for="name">Name</label>
        <input type="text" name="name" placeholder="Your name" required></input>
        <span class="error"> <?php echo $nameErr;?></span>
        <br>
        <br>
        <label for="age">Age:</label>
        <select name="age" id="age" required>
            <option value="0-17">0-17</option>
            <option value="18-25">18-25</option>
            <option value="26-35">26-35</option>
            <option value="36-50">36-50</option>
            <option value="51+">51+</option>
        </select>
        <br>
        <br>
        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" placeholder="Your phone number"></input>
        <br>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="Your email"></input>
        <br>
        <br>
        <label for="profile_link1">Social Media Link 1:</label>
        <input type="url" name="profile_link1" placeholder="Your social media link"></input>
        <br>
        <label for="profile_link2">Social Media Link 2:</label>
        <input type="url" name="profile_link2" placeholder="Your social media link"></input>
        <br>
        <br>
        <label for="country">Country of Residence:</label>
        <select name="country" required>
            <option value="Singapore">Singapore</option>
        </select>
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
        <br>
        <br>
        <label for="specialization">Choose your specialization:</label>
        <select name="specialization" id="specialization" required>
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
        <label for="years_of_exp">Years of experience:</label>
        <select name="years_of_exp" id="years_of_exp" required>
            <option value="0-1">0-1</option>
            <option value="2-4">2-4</option>
            <option value="5-7">5-7</option>
            <option value="8-10">8-10</option>
            <option value="10+">10+</option>
        </select>
        <br>
        <br>
        Rate your expertise from 1 to 10:
        <br>
        <label for="sql_rank">SQL</label>
        <input type="number" name="sql_rank" min="0" max="10" required></input>
        <br>
        <label for="javascript_rank">Java Script</label>
        <input type="number" name="javascript_rank" min="0" max="10" required></input>
        <br>
        <label for="csharp_rank">C#</label>
        <input type="number" name="csharp_rank" min="0" max="10" required></input>
        <br>
        <label for="java_rank">Java</label>
        <input type="number" name="java_rank" min="0" max="10" required></input>
        <br>
        <label for="python_rank">Python</label>
        <input type="number" name="python_rank" min="0" max="10" required></input>
        <br>
        <label for="vb_rank">Visual Basic</label>
        <input type="number" name="vb_rank" min="0" max="10" required></input>
        <br>
        <label for="cplus_rank">C++</label>
        <input type="number" name="cplus_rank" min="0" max="10" required></input>
        <br>
        <label for="c_rank">C</label>
        <input type="number" name="c_rank" min="0" max="10" required></input>
        <br>
        <label for="ruby_rank">Ruby</label>
        <input type="number" name="ruby_rank" min="0" max="10" required></input>
        <br>
        <label for="golang_rank">golang</label>
        <input type="number" name="golang_rank" min="0" max="10" required></input>
        <br>
        <label for="r_rank">R</label>
        <input type="number" name="r_rank" min="0" max="10" required></input>
        <br>
        <label for="rust_rank">Rust</label>
        <input type="number" name="rust_rank" min="0" max="10" required></input>
        <br>
        <br>
        Tell us about your hobbies:
        <br>
        <label for="gen_hobbies1">First Hobby</label>
        <input type="text" name="gen_hobbies1" placeholder="Your First Hobby" <?php echo $hobbiesErr[0];?>></input>
        <br>
        <label for="gen_hobbies2">Second Hobby</label>
        <input type="text" name="gen_hobbies2" placeholder="Your Second Hobby" <?php echo $hobbiesErr[1];?>></input>
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
