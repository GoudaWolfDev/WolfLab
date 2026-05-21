<?php
/**
 * WolfLab Security Environment - Login Portal
 */

session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error_message = '';
$sql_query_executed = '';
$db_file = __DIR__ . '/users.db';

// Check if database exists
if (!file_exists($db_file)) {
    $error_message = "Database file (users.db) not found. Please run 'php init_db.php' to initialize the database.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && file_exists($db_file)) {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    try {
        $db = new PDO("sqlite:" . $db_file);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Educational Vulnerability:
        // Direct string interpolation into raw SQL without sanitization or prepared statements.
        // This allows SQL Injection (Bypass with: admin' OR '1'='1 or ' OR 1=1-- -).
        // Verbose SQL errors are also output to the user.
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $sql_query_executed = $query; // Keep record to show in error box or debug logs
        
        $result = $db->query($query);
        $user = $result->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Set session variables (weak session management: session IDs are default PHP session cookie parameters)
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Invalid Credentials. Access Denied.";
        }

    } catch (PDOException $e) {
        // Educational Vulnerability: Exposing raw SQL error messages directly to the client
        $error_message = "Database Error: " . $e->getMessage() . "<br><br><strong>Executed Query:</strong><br><code style='color:#00f2fe;'>" . htmlspecialchars($sql_query_executed) . "</code>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WolfLab Secure Portal - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <div class="cyber-card">
            <div class="brand">
                <h1>WolfLab</h1>
                <p>Security Command Center</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="cyber-alert">
                    <strong>SYSTEM WARNING:</strong><br>
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Operator Username</label>
                    <div class="input-wrapper">
                        <span class="icon">></span>
                        <input type="text" id="username" name="username" class="cyber-input" placeholder="e.g., admin" required autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Security Access Key</label>
                    <div class="input-wrapper">
                        <span class="icon">*</span>
                        <input type="password" id="password" name="password" class="cyber-input" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="cyber-btn">Authorize</button>
            </form>
        </div>
    </div>

    <footer>
        WolfLab Security Environment &copy; <?php echo date('Y'); ?> | Authorized Operations Only <span>[!]</span>
    </footer>

</body>
</html>
