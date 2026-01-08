<?php require_once '../views/inc/header.php'; ?>
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Daily Irrigation Tasks</h1>
        <form>
            <input type="date" name="date" value="<?php echo $data['date']; ?>" onchange="this.form.submit()">
        </form>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach($data['tasks'] as $task) : ?>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="font-bold"><?php echo htmlspecialchars($task->area_name); ?></h3>
                <p>Scheduled: <?php echo htmlspecialchars($task->scheduled_start_time) . ' - ' . htmlspecialchars($task->scheduled_end_time); ?></p>
                <p>Status: <span class="font-semibold <?php
                    switch($task->status) {
                        case 'COMPLETED': echo 'text-green-500'; break;
                        case 'MISSED': echo 'text-red-500'; break;
                        case 'IN-PROGRESS': echo 'text-yellow-500'; break;
                        default: echo 'text-gray-500';
                    }
                ?>"><?php echo htmlspecialchars($task->status); ?></span></p>
                <p>Assigned to: <?php echo htmlspecialchars($task->assigned_staff); ?></p>
                <a href="index.php?url=irrigationtasks/log/<?php echo htmlspecialchars($task->task_id); ?>" class="text-blue-500 mt-4 inline-block">Log Activity</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require_once '../views/inc/footer.php'; ?>
