<?php
    include 'database.php';
    class Event {
        private $cover;
        private $id;
        private $name;
        private $address;
        private $location;
        private $timing;
        private $description;

        function __construct($cover, $id, $name, $address, $location, $timing, $description) {
            $this->cover = $cover;
            $this->id = $id;
            $this->name = $name;
            $this->address = $address;
            $this->location = $location;
            $this->timing = $timing;
            $this->description = $description;
        }

        function set_cover($cover) {
            $this->cover = $cover;
        }

        function get_cover() {
            return $this->cover;
        }

        function set_id($id) {
            $this->id = $id;
        }

        function get_id() {
            return $this->id;
        }

        function set_name($name) {
            $this->name = $name;
        }

        function get_name() {
            return $this->name;
        }

        function set_address($address) {
            $this->address = $address;
        }

        function get_address() {
            return $this->address;
        }

        function set_location($location) {
            $this->location = $location;
        }

        function get_location() {
            return $this->location;
        }

        function set_timing($timing) {
            $this->timing = $timing;
        }

        function get_timing() {
            return $this->timing;
        }

        function set_description($description) {
            $this->description = $description;
        }

        function get_description() {
            return $this->description;
        }
    }

    $events_array = array();
    $target_dir = "uploads/";
    $uploadOk = 1;
    
    $sql = "SELECT * FROM events";
    try {
        $events = $conn->query($sql);
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    if ($events->num_rows > 0) {
        while ($row = $events->fetch_assoc()) {
            if (isset($row['cover'], $row['event_id'], $row['name'], $row['address'], $row['location'], $row['timing'], $row['description'])) {
                $event = new Event($row['cover'], $row['event_id'], $row['name'], $row['address'], $row['location'], $row['timing'], $row['description']);
                array_push($events_array, $event);
            }
        }
    } else {
        echo "0 results";
    }

    $coverError = $nameError = $addressError = $locationError = $timingError = $descriptionError = "";
    $cover = $name = $address = $location = $timing = $description = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $target_file = $target_dir . basename($_FILES["cover"]["name"]);
        move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file);
        $cover = file_get_contents($target_file);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_SPECIAL_CHARS);
        $timing = filter_input(INPUT_POST, 'timing', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($name)) {
            $nameError = "Name is required";
        } else if (empty($address)) {
            $addressError = "Address is required";
        } else if (empty($location)) {
            $locationError = "Location is required";
        } else if (empty($timing)) {
            $timingError = "Timing is required";
        } else {
            $sql = "INSERT INTO events (cover, name, address, location, timing, description)
                    VALUES ('$target_file', '$name', '$address', '$location', '$timing', '$description')";
            try {
                $conn->query($sql);
            } catch (mysqli_sql_exception $e) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        $conn->close();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Events</title>
    </head>
    <body>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <label for="cover">Cover:</label>
            <input type="file" id="cover" name="cover">
            <span><?php echo $coverError;?></span>
            <br>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name">
            <span><?php echo $nameError;?></span>
            <br>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address">
            <span><?php echo $addressError;?></span>
            <br>
            <label for="location">Location:</label>
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
            <span><?php echo $locationError;?></span>
            <br>
            <label for="timing">Timing:</label>
            <input type="datetime-local" id="timing" name="timing">
            <span><?php echo $timingError;?></span>
            <br>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description">
            <span><?php echo $descriptionError;?></span>
            <br>
            <input type="submit" value="Submit">
        </form>

        <h1>Events</h1>
        <div id="events">
            <?php
                foreach ($events_array as $event) {
                    echo "<img src='" . $target_file . "'?>/> <br>";
                    echo "Name: " . $event->get_name() . "<br>";
                    echo "Address: " . $event->get_address() . "<br>";
                    echo "Location: " . $event->get_location() . "<br>";
                    echo "Timing: " . $event->get_timing() . "<br>";
                    echo "Description: " . $event->get_description() . "<br><br><br>";
                }
            ?>
        </div>
    </body>
</html>