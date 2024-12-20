<?php
// Include the database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission for adding or updating parking spots
    $id = $_POST['id'] ?? null; // If editing, there's an ID

    $name = $_POST['name'];
    $address = $_POST['address'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];

    if ($id) {
        // Update the existing parking spot
        $sql = "UPDATE parking_spots SET name='$name', address='$address', latitude=$latitude, longitude=$longitude, price=$price, capacity=$capacity, description='$description' WHERE id=$id";
    } else {
        // Insert new parking spot
        $sql = "INSERT INTO parking_spots (name, address, latitude, longitude, price, capacity, description) 
                VALUES ('$name', '$address', $latitude, $longitude, $price, $capacity, '$description')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Parking spot saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch the parking spots for editing if necessary
$id = $_GET['id'] ?? null;
if ($id) {
    $result = $conn->query("SELECT * FROM parking_spots WHERE id=$id");
    $parking_spot = $result->fetch_assoc();
} else {
    $parking_spot = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Parking Spots</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<body>
    <h2>Manage Parking Spot</h2>
    <div id="map" style="height: 500px; width: 100%;"></div>

    <!-- <form action="admin.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $parking_spot['id'] ?? ''; ?>">
        <label for="name">Name:</label><br>
        <input type="text" name="name" value="<?php echo $parking_spot['name'] ?? ''; ?>" required><br><br>

        <label for="address">Address:</label><br>
        <input type="text" name="address" value="<?php echo $parking_spot['address'] ?? ''; ?>" required><br><br>

        <label for="latitude">Latitude:</label><br>
        <input type="text" name="latitude" value="<?php echo $parking_spot['latitude'] ?? ''; ?>" required><br><br>

        <label for="longitude">Longitude:</label><br>
        <input type="text" name="longitude" value="<?php echo $parking_spot['longitude'] ?? ''; ?>" required><br><br>

        <label for="price">Price:</label><br>
        <input type="text" name="price" value="<?php echo $parking_spot['price'] ?? ''; ?>" required><br><br>

        <label for="capacity">Capacity:</label><br>
        <input type="number" name="capacity" value="<?php echo $parking_spot['capacity'] ?? ''; ?>" required><br><br>

        <label for="description">Description:</label><br>
        <textarea name="description" required><?php echo $parking_spot['description'] ?? ''; ?></textarea><br><br>

        <button type="submit">Save</button>
    </form> -->
    <script>
        // Initialize the map
        var map = L.map('map').setView([51.505, -0.09], 13); // Default map center (can be adjusted)
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;

            // Center the map on the user's location
            map.setView([userLat, userLng], 13);
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        map.on('click', function(e) {
            var latlng = e.latlng;

            // Create a new marker at the clicked location
            var marker = L.marker(latlng).addTo(map);

            // You can show a form to let the admin fill in the details of the parking spot
            var name = prompt("Enter parking spot name:");
            var address = prompt("Enter parking spot address:");
            var price = prompt("Enter parking spot price:");
            var capacity = prompt("Enter parking spot capacity:");
            var description = prompt("Enter parking spot description:");

            // Save the details to the database or display them in a form
            // You can send this information via AJAX to save in the database
        });
    </script>
</body>

</html>