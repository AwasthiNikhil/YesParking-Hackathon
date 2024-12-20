CREATE DATABASE yesparking;

USE yesparking;

CREATE TABLE parking_spots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 6),
    longitude DECIMAL(10, 6),
    price DECIMAL(10, 2),
    capacity INT,
    description TEXT
);
