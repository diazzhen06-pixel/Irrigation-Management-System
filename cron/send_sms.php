<?php
// Set the correct timezone
date_default_timezone_set('Asia/Manila');

// Include necessary files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/SmsHelper.php';

$db = new Database();
$smsHelper = new SmsHelper();

$db->query("SELECT * FROM sms_queue WHERE status = 'PENDING' OR (status = 'FAILED' AND retry_count < 3) ORDER BY created_at ASC");
$messages = $db->resultSet();

foreach ($messages as $message) {
    // Mark as sending
    $db->query("UPDATE sms_queue SET status = 'SENDING', last_attempt_at = NOW() WHERE queue_id = :id");
    $db->bind(':id', $message->queue_id);
    $db->execute();

    // Send the SMS
    if ($smsHelper->send($message->recipient_number, $message->message)) {
        // On success, remove from queue and log to sent messages
        $db->query("DELETE FROM sms_queue WHERE queue_id = :id");
        $db->bind(':id', $message->queue_id);
        $db->execute();

        $db->query("INSERT INTO sms_logs (recipient_number, message, status, response) VALUES (:recipient, :message, 'SENT', 'OK')");
        $db->bind(':recipient', $message->recipient_number);
        $db->bind(':message', $message->message);
        $db->execute();
        echo "Successfully sent SMS to {$message->recipient_number}\n";
    } else {
        // On failure, update retry count and status
        $db->query("UPDATE sms_queue SET status = 'FAILED', retry_count = retry_count + 1 WHERE queue_id = :id");
        $db->bind(':id', $message->queue_id);
        $db->execute();
        echo "Failed to send SMS to {$message->recipient_number}\n";
    }
}

echo "SMS queue processing completed.\n";
