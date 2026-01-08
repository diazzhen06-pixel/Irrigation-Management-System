<?php
require_once '../models/AuditLog.php';

function log_audit($action, $target_resource = null, $target_id = null, $change_details = null) {
    if (isLoggedIn()) {
        $auditLog = new AuditLog();
        $auditLog->logAction($_SESSION['user_id'], $action, $target_resource, $target_id, $change_details);
    }
}
