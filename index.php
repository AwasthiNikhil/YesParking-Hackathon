<?php
// Include the database connection
include 'db.php';

if (isset($_POST['destination'])) {
    // Get the user-entered destination (this could be used for more advanced search)
    $destination = $_POST['destination'];

    // For now, let's show all parking spots
    $sql = "SELECT * FROM parking_spots";
    $result = $conn->query($sql);
} else {
    $result = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Spot Finder</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<body>
    <h2>Find Parking Spots</h2>

    <form action="index.php" method="POST">
        <label for="destination">Enter a destination:</label>
        <input type="text" name="destination" required>
        <button type="submit">Search</button>
    </form>

    <!-- Map container -->
    <div id="map" style="height: 500px; width: 100%;"></div>

    <script>
        // Initialize the map
        var map = L.map('map').setView([51.505, -0.09], 13); // Default map center (can be adjusted)
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;

            // Center the map on the user's location
            map.setView([userLat, userLng], 13);
        });


        // Add OpenStreetMap tiles (free)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        <?php if ($result && $result->num_rows > 0): ?>
            // Loop through the parking spots and create markers
            <?php while ($row = $result->fetch_assoc()): ?>
                // Create a marker for each parking spot
                var marker = L.marker([<?php echo $row['latitude']; ?>, <?php echo $row['longitude']; ?>]).addTo(map);

                // Bind a popup to each marker with the parking spot details
                marker.bindPopup(`
                    <strong><?php echo $row['name']; ?></strong><br>
                    Address: <?php echo $row['address']; ?><br>
                    Price: $<?php echo $row['price']; ?><br>
                    Capacity: <?php echo $row['capacity']; ?><br>
                    Description: <?php echo $row['description']; ?><br>
                `);
            <?php endwhile; ?>
        <?php endif; ?>
    </script>

</body>

</html>