-- Tabel Users
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'customer',
    google_id VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Tipe Kamar (Room Types)
CREATE TABLE IF NOT EXISTS room_types (
    id SERIAL PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL
);

-- Tabel Kamar Fisik (Rooms)
CREATE TABLE IF NOT EXISTS rooms (
    id SERIAL PRIMARY KEY,
    room_type_id INTEGER NOT NULL,
    room_number VARCHAR(20) NOT NULL,
    status VARCHAR(20) DEFAULT 'available', -- available, maintenance, booked
    CONSTRAINT fk_room_type FOREIGN KEY (room_type_id) REFERENCES room_types (id) ON DELETE CASCADE
);

-- Tabel Bookings
CREATE TABLE IF NOT EXISTS bookings (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    room_id INTEGER NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    total_price DECIMAL(12, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending', -- pending, confirmed, cancelled, completed
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_booking_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_booking_room FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE SET NULL
);

-- Tabel Payments
CREATE TABLE IF NOT EXISTS payments (
    id SERIAL PRIMARY KEY,
    booking_id INTEGER NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    provider VARCHAR(50) NOT NULL, -- DANA, GOPAY
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payment_booking FOREIGN KEY (booking_id) REFERENCES bookings (id) ON DELETE CASCADE
);

-- Seeding Data Awal (Optional - Admin & Tipe Kamar)
INSERT INTO users (name, email, password, role) VALUES 
('Admin Hotel', 'admin@hotel48.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON CONFLICT (email) DO NOTHING;
-- Password default: 'password' (ini hash bcrypt dummy user Laravel/umum)

INSERT INTO room_types (type_name, description, price) VALUES
('Standard Room', 'Kamar nyaman dengan fasilitas standar.', 300000),
('Deluxe Room', 'Kamar lebih luas dengan pemandangan kota.', 500000),
('Suite Room', 'Kamar mewah dengan ruang tamu terpisah.', 1000000)
ON CONFLICT DO NOTHING;
