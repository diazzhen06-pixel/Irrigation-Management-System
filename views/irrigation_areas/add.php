<?php require_once '../views/inc/header.php'; ?>
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Add Irrigation Area</h1>
    <form action="index.php?url=irrigationareas/add" method="post">
        <?php echo csrf_token_input(); ?>
        <div class="mb-4">
            <label for="area_name" class="block text-gray-700">Area Name</label>
            <input type="text" name="area_name" id="area_name" class="w-full border px-4 py-2 rounded <?php echo (!empty($data['area_name_err'])) ? 'border-red-500' : ''; ?>" value="<?php echo htmlspecialchars($data['area_name']); ?>" required>
            <span class="text-red-500 text-xs italic"><?php echo htmlspecialchars($data['area_name_err']); ?></span>
        </div>
        <div class="mb-4">
            <label for="barangay_coverage" class="block text-gray-700">Barangay Coverage</label>
            <input type="text" name="barangay_coverage" id="barangay_coverage" class="w-full border px-4 py-2 rounded" value="<?php echo htmlspecialchars($data['barangay_coverage']); ?>">
        </div>
        <div class="mb-4">
            <label for="estimated_hectares" class="block text-gray-700">Estimated Hectares</label>
            <input type="number" step="0.01" name="estimated_hectares" id="estimated_hectares" class="w-full border px-4 py-2 rounded <?php echo (!empty($data['estimated_hectares_err'])) ? 'border-red-500' : ''; ?>" value="<?php echo htmlspecialchars($data['estimated_hectares']); ?>" required>
            <span class="text-red-500 text-xs italic"><?php echo htmlspecialchars($data['estimated_hectares_err']); ?></span>
        </div>
        <div class="mb-4">
            <label for="notes" class="block text-gray-700">Notes</label>
            <textarea name="notes" id="notes" class="w-full border px-4 py-2 rounded"></textarea>
        </div>
        <div class="mb-4">
            <label for="area_type" class="block text-gray-700">Area Type</label>
            <select name="area_type" id="area_type" class="w-full border px-4 py-2 rounded">
                <option value="SYSTEM">System</option>
                <option value="MAIN_CANAL">Main Canal</option>
                <option value="LATERAL_CANAL">Lateral Canal</option>
                <option value="SERVICE_AREA">Service Area</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="parent_id" class="block text-gray-700">Parent Area</label>
            <input type="number" name="parent_id" id="parent_id" class="w-full border px-4 py-2 rounded">
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Area</button>
    </form>
</div>
<?php require_once '../views/inc/footer.php'; ?>
