<?php
// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Include necessary files
require_once __DIR__ . '/../config/database.php';

$db = new Database();

// Get today's date
$today = date('Y-m-d');

// Get all active schedules that are valid for today
$db->query("
    SELECT * FROM irrigation_schedules
    WHERE is_active = 1 AND start_date <= :today AND end_date >= :today
");
$db->bind(':today', $today);
$schedules = $db->resultSet();

foreach ($schedules as $schedule) {
    // A simple approach: assign to the user who created the schedule
    // A more complex system might have a dedicated user assignment table
    $assigned_staff_id = $schedule->created_by;

    // Check if a task for this schedule and date already exists
    $db->query("
        SELECT * FROM irrigation_tasks
        WHERE schedule_id = :schedule_id AND scheduled_date = :today
    ");
    $db->bind(':schedule_id', $schedule->schedule_id);
    $db->bind(':today', $today);
    $existingTask = $db->single();

    if (!$existingTask) {
        $db->query("
            INSERT INTO irrigation_tasks (schedule_id, area_id, assigned_staff_id, scheduled_date, scheduled_start_time, scheduled_end_time, status)
            VALUES (:schedule_id, :area_id, :assigned_staff_id, :scheduled_date, :scheduled_start_time, :scheduled_end_time, 'DUE')
        ");
        $db->bind(':schedule_id', $schedule->schedule_id);
        $db->bind(':area_id', $schedule->area_id);
        $db->bind(':assigned_staff_id', $assigned_staff_id);
        $db->bind(':scheduled_date', $today);
        $db->bind(':scheduled_start_time', $schedule->start_time);
        $db->bind(':scheduled_end_time', $schedule->end_time);
        $db->execute();
        echo "Generated task for schedule ID: {$schedule->schedule_id}\n";
    } else {
        echo "Task already exists for schedule ID: {$schedule->schedule_id}\n";
    }
}

echo "Task generation completed for {$today}\n";
