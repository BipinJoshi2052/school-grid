<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($staffs)): ?>
                <tr>
                    <td colspan="5" class="text-center">No unassigned staff available</td>
                </tr>
            <?php else: ?>
                <?php foreach ($staffs as $index => $staff): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($staff['name']); ?></td>
                        <td><?php echo htmlspecialchars($staff['department'] ? $staff['department']['title'] : '-'); ?></td>
                        <td><?php echo htmlspecialchars($staff['position'] ? $staff['position']['title'] : '-'); ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm selectStaff" 
                                    data-id="<?php echo $id; ?>"
                                    data-seat-plan-id="<?php echo $seatPlanId; ?>"
                                    data-staff-id="<?php echo $staff['id']; ?>" 
                                    data-building-id="<?php echo $buildingId; ?>" 
                                    data-room-index="<?php echo $roomIndex; ?>"                                    
                                    data-row="<?php echo $row; ?>">
                                Select
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>