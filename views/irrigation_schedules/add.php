<?php require_once '../views/inc/header.php'; ?>
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Add Irrigation Schedule</h1>
    <form action="index.php?url=irrigationschedules/add" method="post">
        <?php echo csrf_token_input(); ?>
        <div class="mb-4">
            <label for="area_id" class="block text-gray-700">Irrigation Area</label>
            <select name="area_id" id="area_id" class="w-full border px-4 py-2 rounded <?php echo (!empty($data['area_id_err'])) ? 'border-red-500' : ''; ?>" required>
                <option value="">Select Area</option>
                <?php foreach($data['areas'] as $area): ?>
                    <option value="<?php echo htmlspecialchars($area->area_id); ?>" <?php echo ($data['area_id'] == $area->area_id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($area->area_name); ?></option>
                <?php endforeach; ?>
            </select>
            <span class="text-red-500 text-xs italic"><?php echo htmlspecialchars($data['area_id_err']); ?></span>
        </div>
        <div class="mb-4">
            <label for="start_date" class="block text-gray-700">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="w-full border px-4 py-2 rounded <?php echo (!empty($data['start_date_err'])) ? 'border-red-500' : ''; ?>" value="<?php echo htmlspecialchars($data['start_date']); ?>" required>
            <span class="text-red-500 text-xs italic"><?php echo htmlspecialchars($data['start_date_err']); ?></span>
        </div>
        <div class="mb-4">
            <label for="end_date" class="block text-gray-700">End Date</label>
            <input type="date" name="end_date" id="end_date" class="w-full border px-4 py-2 rounded <?php echo (!empty($data['end_date_err'])) ? 'border-red-500' : ''; ?>" value="<?php echo htmlspecialchars($data['end_date']); ?>" required>
            <span class="text-red-500 text-xs italic"><?php echo htmlspecialchars($data['end_date_err']); ?></span>
        </div>
        <div class="mb-4">
            <label for="start_time" class="block text-gray-700">Start Time</label>
            <input type="time" name="start_time" id="start_time" class="w-full border px-4 py-2 rounded <?php echo (!empty($data['start_time_err'])) ? 'border-red-500' : ''; ?>" value="<?php echo htmlspecialchars($data['start_time']); ?>" required>
            <span class="text-red-500 text-xs italic"><?php echo htmlspecialchars($data['start_time_err']); ?></span>
        </div>
        <div class="mb-4">
            <label for="end_time" class="block text-gray-700">End Time</label>
            <input type="time" name="end_time" id="end_time" class="w-full border px-4 py-2 rounded <?php echo (!empty($data['end_time_err'])) ? 'border-red-500' : ''; ?>" value="<?php echo htmlspecialchars($data['end_time']); ?>" required>
            <span class="text-red-500 text-xs italic"><?php echo htmlspecialchars($data['end_time_err']); ?></span>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Schedule</button>
    </form>
</div>
<?php require_once '../views/inc/footer.php'; ?>
