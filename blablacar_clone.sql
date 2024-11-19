CREATE DATABASE blablacar_clone;

USE blablacar_clone;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Rides Table (For both cars and bikes)
CREATE TABLE rides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT, -- foreign key to users table (user who posted the ride)
    origin VARCHAR(255) NOT NULL,
    destination VARCHAR(255) NOT NULL,
    vehicle_type ENUM('car', 'bike') NOT NULL,
    ride_date DATE NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    seats_available INT NOT NULL,
    ride_time TIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT, -- foreign key to users table (user who booked the ride)
    ride_id INT, -- foreign key to rides table
    seats_booked INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ride_id) REFERENCES rides(id) ON DELETE CASCADE
);

