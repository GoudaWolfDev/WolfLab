<?php
/**
 * WolfLab Security Environment - Control Dashboard
 */

session_start();

// Session verification (Weak Session Handling: checked using just session status, no IP binding or expiration check)
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: login.php");
    exit;
}

$db_file = __DIR__ . '/users.db';
$users_list = [];
$total_users = 0;

try {
    if (file_exists($db_file)) {
        $db = new PDO("sqlite:" . $db_file);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch total number of users
        $count_res = $db->query("SELECT COUNT(*) as count FROM users");
        $total_users = $count_res->fetch(PDO::FETCH_ASSOC)['count'];

        // Fetch all users to display in the operator directory
        // Educational Vulnerability: Exposing all database records (usernames, hashed/plain passwords) directly on the admin-like panel.
        $users_res = $db->query("SELECT id, username, role FROM users");
        $users_list = $users_res->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    // Ignore db errors inside dashboard or log quietly
    $db_error = $e->getMessage();
}

// Generate realistic simulated security alerts / logs
$fake_logs = [
    ['time' => '15:42:01', 'lvl' => 'info', 'msg' => 'Intrusion Detection System started on adapter wlan0mon.'],
    ['time' => '15:43:12', 'lvl' => 'info', 'msg' => 'Firewall configuration reloaded. 14 rules active.'],
    ['time' => '15:44:05', 'lvl' => 'warn', 'msg' => 'Brute-force attempt detected from IP 192.168.1.105 on SSH.'],
    ['time' => '15:44:30', 'lvl' => 'info', 'msg' => 'Operator session created successfully: ' . htmlspecialchars($_SESSION['username'])],
    ['time' => '15:45:18', 'lvl' => 'alert', 'msg' => 'Critical: Unauthorized request intercepted on endpoint api/v1/auth.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WolfLab Security Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="dashboard-container">
        
        <header class="dashboard-header">
            <div>
                <h2>WOLF<span>LAB</span> CONTROL PANEL</h2>
                <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 5px; font-family: var(--font-mono);">
                    OPERATOR SECURE SESSION ACTIVE
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <div class="user-badge">
                    [+] <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)
                </div>
                <a href="logout.php" class="btn-logout">Terminate Session</a>
            </div>
        </header>

        <!-- Dynamic Grid Info -->
        <div class="db-grid">
            <div class="db-card">
                <h3>Database Node Status</h3>
                <div class="db-val green">ONLINE</div>
                <p style="color: var(--text-muted); font-size: 0.8rem; margin-top: 5px; font-family: var(--font-mono);">
                    SQLite Connection: Active
                </p>
            </div>

            <div class="db-card">
                <h3>Authorized Operators</h3>
                <div class="db-val"><?php echo $total_users; ?></div>
                <p style="color: var(--text-muted); font-size: 0.8rem; margin-top: 5px; font-family: var(--font-mono);">
                    Active DB user accounts
                </p>
            </div>

            <div class="db-card">
                <h3>Threat Risk Level</h3>
                <div class="db-val red">CRITICAL</div>
                <p style="color: var(--text-muted); font-size: 0.8rem; margin-top: 5px; font-family: var(--font-mono);">
                    Active simulated mitigations
                </p>
            </div>
        </div>

        <!-- Terminal Console and Registered Users Layout -->
        <div style="display: grid; grid-template-columns: 3fr 2fr; gap: 20px; margin-top: 30px;">
            
            <!-- Terminal Live Logs -->
            <div class="db-card" style="grid-column: span 1;">
                <h3>Terminal Real-Time Monitoring</h3>
                <div class="terminal-console">
                    <?php foreach ($fake_logs as $log): ?>
                        <div class="terminal-line">
                            <span class="timestamp">[<?php echo $log['time']; ?>]</span>
                            <span class="level <?php echo $log['lvl']; ?>"><?php echo strtoupper($log['lvl']); ?>:</span>
                            <span class="message"><?php echo $log['msg']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Operator Directory -->
            <div class="db-card" style="grid-column: span 1;">
                <h3>Operator Database Registry</h3>
                <div style="overflow-x: auto;">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($users_list) > 0): ?>
                                <?php foreach ($users_list as $u): ?>
                                    <tr>
                                        <td>#<?php echo htmlspecialchars($u['id']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($u['username']); ?></strong></td>
                                        <td><span style="color: var(--primary-cyan);"><?php echo htmlspecialchars($u['role']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; color: var(--text-muted);">No records found. Check init_db.php.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <footer>
        WolfLab Security Environment &copy; <?php echo date('Y'); ?> | Local Sandbox Educational Lab <span>[!]</span>
    </footer>

</body>
</html>
