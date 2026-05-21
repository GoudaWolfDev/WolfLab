<?php
/**
 * WolfLab - Database Initializer
 * This script initializes the SQLite database for educational purposes.
 */

// Define database file path
$db_file = __DIR__ . '/users.db';

echo "[*] Initializing WolfLab SQLite Database...\n";

try {
    // Create (or open) the SQLite database
    $db = new PDO("sqlite:" . $db_file);
    
    // Set error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop existing table if it exists to start fresh
    $db->exec("DROP TABLE IF EXISTS users");
    
    // Create the users table
    $query = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOCTINCREMENT, -- Note: SQLite autoincrement typo or standard AUTOINCREMENT? It is AUTOINCREMENT in SQLite. Let's use INTEGER PRIMARY KEY AUTOINCREMENT
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL
    )";
    // Let's write the correct SQLite syntax: INTEGER PRIMARY KEY AUTOINCREMENT
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL
    )");
    
    echo "[+] Table 'users' created successfully.\n";
    
    // Insert default administrator user (Plaintext password for intentional weak credential hashing/design demonstration)
    // Username: admin
    // Password: admin / 123456
    $stmt = $db->prepare("INSERT OR IGNORE INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->execute([
        ':username' => 'admin',
        ':password' => '123456',
        ':role' => 'administrator'
    ]);
    
    // Also insert a test user for dashboard display
    $stmt->execute([
        ':username' => 'guest',
        ':password' => 'guest123',
        ':role' => 'user'
    ]);

    echo "[+] Default administrator inserted: admin / 123456\n";
    echo "[+] Default guest inserted: guest / guest123\n";
    echo "[+] Database initialization complete. 'users.db' is ready.\n";

} catch (PDOException $e) {
    echo "[-] Database Error: " . $e->getMessage() . "\n";
    exit(1);
}
