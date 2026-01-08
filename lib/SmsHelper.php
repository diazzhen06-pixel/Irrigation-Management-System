<?php
/**
 * A generic class to send SMS messages via an HTTP API.
 */
class SmsHelper {
    /**
     * Sends an SMS message.
     *
     * @param string $recipient The phone number of the recipient.
     * @param string $message The content of the message.
     * @return bool True on success, false on failure.
     */
    public function send($recipient, $message) {
        // This is a placeholder for a real SMS API integration.
        // For this project, we will simulate the SMS by logging it to a file.

        $logMessage = "[" . date('Y-m-d H:i:s') . "] SMS to " . $recipient . ": " . $message . "\n";

        // Define the log file path
        $logFilePath = __DIR__ . '/../sms_sent.log';

        // Append the message to the log file
        if (file_put_contents($logFilePath, $logMessage, FILE_APPEND)) {
            return true; // Simulate success
        } else {
            return false; // Simulate failure
        }
    }
}
