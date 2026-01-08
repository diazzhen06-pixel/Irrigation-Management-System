<?php require_once '../views/inc/header.php'; ?>
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Log Activity for Task #<?php echo htmlspecialchars($data['task']->task_id); ?></h1>
    <form action="index.php?url=irrigationtasks/log/<?php echo htmlspecialchars($data['task']->task_id); ?>" method="post">
        <?php echo csrf_token_input(); ?>
        <div class="mb-4">
            <label for="actual_start_time" class="block text-gray-700">Actual Start Time</label>
            <input type="datetime-local" name="actual_start_time" id="actual_start_time" class="w-full border px-4 py-2 rounded">
        </div>
        <div class="mb-4">
            <label for="actual_end_time" class="block text-gray-700">Actual End Time</label>
            <input type="datetime-local" name="actual_end_time" id="actual_end_time" class="w-full border px-4 py-2 rounded">
        </div>
        <div class="mb-4">
            <label for="area_served" class="block text-gray-700">Area Served (Hectares)</label>
            <input type="number" step="0.01" name="area_served" id="area_served" class="w-full border px-4 py-2 rounded">
        </div>
        <div class="mb-4">
            <label for="issues_encountered" class="block text-gray-700">Issues Encountered</label>
            <textarea name="issues_encountered" id="issues_encountered" class="w-full border px-4 py-2 rounded"></textarea>
        </div>
        <div class="mb-4">
            <label for="remarks" class="block text-gray-700">Remarks</label>
            <textarea name="remarks" id="remarks" class="w-full border px-4 py-2 rounded"></textarea>
        </div>
        <div class="mb-4">
            <label for="status" class="block text-gray-700">Status</label>
            <select name="status" id="status" class="w-full border px-4 py-2 rounded <?php echo (!empty($data['status_err'])) ? 'border-red-500' : ''; ?>">
                <option value="">Select Status</option>
                <option value="IN-PROGRESS" <?php echo ($data['status'] == 'IN-PROGRESS') ? 'selected' : ''; ?>>In-Progress</option>
                <option value="COMPLETED" <?php echo ($data['status'] == 'COMPLETED') ? 'selected' : ''; ?>>Completed</option>
                <option value="MISSED" <?php echo ($data['status'] == 'MISSED') ? 'selected' : ''; ?>>Missed</option>
                <option value="CANCELLED" <?php echo ($data['status'] == 'CANCELLED') ? 'selected' : ''; ?>>Cancelled</option>
            </select>
            <span class="text-red-500 text-xs italic"><?php echo $data['status_err']; ?></span>
        </div>
        <div class="mb-4">
            <label for="cancellation_reason" class="block text-gray-700">Reason for Cancellation/Miss</label>
            <textarea name="cancellation_reason" id="cancellation_reason" class="w-full border px-4 py-2 rounded"></textarea>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Log Activity</button>
    </form>
</div>
<?php require_once '../views/inc/footer.php'; ?>
