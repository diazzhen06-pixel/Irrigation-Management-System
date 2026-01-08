<?php require_once '../views/inc/header.php'; ?>
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Irrigation Areas</h1>
        <a href="index.php?url=irrigationareas/add" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Area</a>
    </div>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Area Name</th>
                <th class="py-2 px-4 border-b">Barangay Coverage</th>
                <th class="py-2 px-4 border-b">Estimated Hectares</th>
                <th class="py-2 px-4 border-b">Type</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['areas'] as $area) : ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($area->area_name); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($area->barangay_coverage); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($area->estimated_hectares); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($area->area_type); ?></td>
                    <td class="py-2 px-4 border-b">
                        <a href="index.php?url=irrigationareas/edit/<?php echo htmlspecialchars($area->area_id); ?>" class="text-blue-500">Edit</a>
                        <form action="index.php?url=irrigationareas/delete/<?php echo $area->area_id; ?>" method="post" class="inline">
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
