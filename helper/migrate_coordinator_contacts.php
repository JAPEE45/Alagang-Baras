<?php
/**
 * Migration helper
 * - Adds coordinators.contact_number if missing
 * - Previews rows where coordinators.contact_number is empty and users.password_ looks like a phone number
 * - On confirmation (confirm=1) copies users.password_ -> coordinators.contact_number for those rows
 * - Optionally resets users.password_ to a generated temporary password and outputs a CSV mapping
 *
 * Usage:
 *  - Visit in browser to preview: http://localhost/Alagang-Baras/helper/migrate_coordinator_contacts.php
 *  - To run migration (and reset passwords): add ?confirm=1&reset=1
 */

require_once __DIR__ . '/../config/db.php';

function safeHeader($s) { echo '<h3>' . htmlspecialchars($s) . '</h3>'; }

// Ensure column exists
try {
    $colStmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'coordinators' AND COLUMN_NAME = 'contact_number' LIMIT 1");
    $colStmt->execute();
    $colExists = (bool)$colStmt->fetch();
    if (!$colExists) {
        $pdo->exec("ALTER TABLE coordinators ADD COLUMN contact_number VARCHAR(50) NULL AFTER fullname");
        $colExists = true;
    }
} catch (Exception $e) {
    echo '<p style="color:darkred">Failed to ensure contact_number column: ' . htmlspecialchars($e->getMessage()) . '</p>';
    $colExists = false;
}

// Find candidate rows where coordinators.contact_number is NULL/empty and users.password_ looks like phone
$previewSql = "SELECT c.coordinator_id, c.user_id, c.fullname, c.barangay_assigned, c.contact_number, u.username, u.password_ FROM coordinators c JOIN users u ON c.user_id = u.user_id WHERE (c.contact_number IS NULL OR c.contact_number = '')";
// We'll filter password_ by regex in PHP for portability
$stmt = $pdo->prepare($previewSql);
$stmt->execute();
$rows = $stmt->fetchAll();

$candidates = [];
foreach ($rows as $r) {
    $pw = isset($r['password_']) ? trim($r['password_']) : '';
    if ($pw !== '' && preg_match('/^[0-9\s\-\+]+$/', $pw)) {
        $candidates[] = $r;
    }
}

safeHeader('Coordinator contact migration helper');
echo '<p>Detected ' . count($candidates) . ' candidate row(s) where coordinator.contact_number is empty and users.password_ looks like a phone number.</p>';

if (count($candidates) === 0) {
    echo '<p>No candidates found. Nothing to migrate.</p>';
    exit;
}

echo '<table border="1" cellpadding="6" style="border-collapse:collapse"><tr><th>coordinator_id</th><th>user_id</th><th>username</th><th>fullname</th><th>barangay</th><th>password_ (will be used as contact)</th></tr>';
foreach ($candidates as $r) {
    echo '<tr>' .
         '<td>' . htmlspecialchars($r['coordinator_id']) . '</td>' .
         '<td>' . htmlspecialchars($r['user_id']) . '</td>' .
         '<td>' . htmlspecialchars($r['username']) . '</td>' .
         '<td>' . htmlspecialchars($r['fullname']) . '</td>' .
         '<td>' . htmlspecialchars($r['barangay_assigned']) . '</td>' .
         '<td>' . htmlspecialchars($r['password_']) . '</td>' .
         '</tr>';
}
echo '</table>';

$confirm = isset($_GET['confirm']) && $_GET['confirm'] == '1';
$resetPasswords = isset($_GET['reset']) && $_GET['reset'] == '1';

if (!$confirm) {
    echo '<p>To perform the migration and copy these values into <code>coordinators.contact_number</code>, open this URL with <code>?confirm=1</code> (add <code>&reset=1</code> to also reset users.password_).</p>';
    echo '<p><a href="?confirm=1">Run migration (copy contact only)</a> | <a href="?confirm=1&reset=1">Run migration and reset passwords</a></p>';
    exit;
}

// Perform migration
$mapping = [];
try {
    $pdo->beginTransaction();
    foreach ($candidates as $r) {
        $user_id = $r['user_id'];
        $contact = trim($r['password_']);
        // Update coordinators.contact_number
        $u = $pdo->prepare('UPDATE coordinators SET contact_number = ? WHERE user_id = ?');
        $u->execute([$contact, $user_id]);

        $newPw = null;
        if ($resetPasswords) {
            $newPw = 'Coord' . rand(1000, 9999);
            $up = $pdo->prepare('UPDATE users SET password_ = ? WHERE user_id = ?');
            $up->execute([$newPw, $user_id]);
        }
        $mapping[] = ['user_id' => $user_id, 'username' => $r['username'], 'contact' => $contact, 'new_password' => $newPw];
    }
    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo '<p style="color:red">Migration failed: ' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}

// Output results and CSV download link
echo '<p style="color:green">Migration completed. ' . count($mapping) . ' rows updated.</p>';
if ($resetPasswords) echo '<p>Passwords were reset for migrated users. Store them securely.</p>';

if (!empty($mapping)) {
    echo '<h4>Mapping</h4>';
    echo '<table border="1" cellpadding="6" style="border-collapse:collapse"><tr><th>user_id</th><th>username</th><th>contact</th><th>new_password</th></tr>';
    foreach ($mapping as $m) {
        echo '<tr><td>' . htmlspecialchars($m['user_id']) . '</td><td>' . htmlspecialchars($m['username']) . '</td><td>' . htmlspecialchars($m['contact']) . '</td><td>' . htmlspecialchars($m['new_password'] ?? '') . '</td></tr>';
    }
    echo '</table>';

    // CSV
    $csv = "user_id,username,contact,new_password\n";
    foreach ($mapping as $m) {
        $csv .= implode(',', array_map(function($v){ return '"' . str_replace('"','""',$v) . '"'; }, [$m['user_id'],$m['username'],$m['contact'],$m['new_password']])) . "\n";
    }
    $csvFile = __DIR__ . '/migrated_coordinators.csv';
    file_put_contents($csvFile, $csv);
    echo '<p>CSV written to <code>helper/migrated_coordinators.csv</code></p>';
}

?>
