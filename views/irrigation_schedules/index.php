<?php require_once '../views/inc/header.php'; ?>
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Irrigation Schedules</h1>
        <a href="index.php?url=irrigationschedules/add" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Schedule</a>
    </div>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Area</th>
                <th class="py-2 px-4 border-b">Start Date</th>
                <th class="py-2 px-4 border-b">End Date</th>
                <th class="py-2 px-4 border-b">Time</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['schedules'] as $schedule) : ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($schedule->area_name); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($schedule->start_date); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($schedule->end_date); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($schedule->start_time) . ' - ' . htmlspecialchars($schedule->end_time); ?></td>
                    <td class="py-2 px-4 border-b">
                        <a href="index.php?url=irrigationschedules/edit/<?php echo htmlspecialchars($schedule->schedule_id); ?>" class="text-blue-500">Edit</a>
                        <form action="index.php?url=irrigationschedules/delete/<?php echo $schedule->schedule_id; ?>" method="post" class="inline">
                            <?php echo csrf_token_input(); ?>
                            <button type="submit" class="text-red-500">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../views/inc/footer.php'; ?>
