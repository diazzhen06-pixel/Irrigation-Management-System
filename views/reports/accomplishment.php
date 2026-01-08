<?php require_once '../views/inc/header.php'; ?>
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Irrigation Accomplishment Report</h1>

    <div class="bg-white p-4 rounded-lg shadow-md mb-4">
        <form>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="w-full border p-2 rounded" value="<?php echo $data['startDate']; ?>">
                </div>
                <div>
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="w-full border p-2 rounded" value="<?php echo $data['endDate']; ?>">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Generate</button>
                    <a href="?start_date=<?php echo $data['startDate']; ?>&end_date=<?php echo $data['endDate']; ?>&export=csv" class="ml-2 bg-green-500 text-white px-4 py-2 rounded">Export to CSV</a>
                </div>
            </div>
        </form>
    </div>

    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Date</th>
                <th class="py-2 px-4 border-b">Area</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Staff</th>
                <th class="py-2 px-4 border-b">Area Served (ha)</th>
                <th class="py-2 px-4 border-b">Issues</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['tasks'] as $task) : ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($task->scheduled_date); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($task->area_name); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($task->status); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($task->staff_name); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($task->area_served); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($task->issues_encountered); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../views/inc/footer.php'; ?>
