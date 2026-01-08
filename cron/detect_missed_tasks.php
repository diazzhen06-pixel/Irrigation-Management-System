<?php
// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Include necessary files
require_once __DIR__ . '/../config/database.php';

$db = new Database();

// Get the current date and time
$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');

// Find tasks that are due, scheduled for today or earlier,
// and where the scheduled_end_time has passed.
$db->query("
    UPDATE irrigation_tasks
    SET status = 'MISSED'
    WHERE status = 'DUE'
    AND scheduled_date <= :today
    AND CONCAT(scheduled_date, ' ', scheduled_end_time) < :now
");
$db->bind(':today', $today);
$db->bind(':now', $now);

$db->execute();

$rowCount = $db->rowCount();

if ($rowCount > 0) {
    // Get the IDs of the tasks that were just missed
    $db->query("
        SELECT task_id, area_id FROM irrigation_tasks
        WHERE status = 'MISSED'
        AND CONCAT(scheduled_date, ' ', scheduled_end_time) < :now
    ");
    $db->bind(':now', $now);
    $missedTasks = $db->resultSet();

    foreach($missedTasks as $task) {
        $db->query("
            INSERT INTO alerts (alert_type, related_id, message)
            VALUES ('MISSED_IRRIGATION', :task_id, :message)
        ");
        $db->bind(':task_id', $task->task_id);
        $db->bind(':message', 'Missed irrigation task for area ID: ' . $task->area_id);
        $db->execute();
    }
}

echo "Checked for missed tasks. Found and updated {$rowCount} tasks.\n";
