<?php require_once '../views/inc/header.php'; ?>
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700">Total Tasks Today</h2>
            <p class="text-3xl font-bold text-blue-500"><?php echo htmlspecialchars($data['stats']['total']); ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700">Completed</h2>
            <p class="text-3xl font-bold text-green-500"><?php echo htmlspecialchars($data['stats']['completed']); ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700">Missed</h2>
            <p class="text-3xl font-bold text-red-500"><?php echo htmlspecialchars($data['stats']['missed']); ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700">Due</h2>
            <p class="text-3xl font-bold text-yellow-500"><?php echo htmlspecialchars($data['stats']['due']); ?></p>
        </div>
    </div>

    <!-- Today's Tasks and Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Tasks -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Today's Tasks</h2>
            <div class="overflow-y-auto h-64">
                <?php foreach($data['today_tasks'] as $task): ?>
                    <div class="border-b py-2">
                        <p class="font-semibold"><?php echo htmlspecialchars($task->area_name); ?></p>
                        <p class="text-sm text-gray-600">
                            <?php echo htmlspecialchars($task->scheduled_start_time); ?> - <?php echo htmlspecialchars($task->scheduled_end_time); ?> |
                            Status: <span class="font-bold"><?php echo htmlspecialchars($task->status); ?></span>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- New Alerts -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">New Alerts</h2>
            <div class="overflow-y-auto h-64">
                <?php foreach($data['new_alerts'] as $alert): ?>
                    <div class="border-b py-2">
                        <p class="font-semibold text-red-600"><?php echo htmlspecialchars($alert->message); ?></p>
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($alert->created_at); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once '../views/inc/footer.php'; ?>
