<?php
// Department + access level dropdowns for employee forms.
// $person = the employee being edited (optional), $allowedRoles = roles this user may give.
$person      = $person ?? [];
$departments = config('SunSon')->departments;
$current     = old('department', $person['department'] ?? '');
$currentRole = old('role', $person['role'] ?? 'employee');

// Older accounts may have a department that is not in the client's list (e.g. "Operations").
if ($current !== '' && ! in_array($current, $departments, true)) {
    $departments[] = $current;
}
?>
<label>Department *
    <select name="department" required>
        <option value="">Select department</option>
        <?php foreach ($departments as $department): ?>
            <option value="<?= esc($department, 'attr') ?>" <?= $current === $department ? 'selected' : '' ?>><?= esc($department) ?></option>
        <?php endforeach; ?>
    </select>
</label>
<?php if (! isset($person['employee_id']) || (int) $person['employee_id'] !== (int) session('user_id')): ?>
    <label>Account access *
        <select name="role" required>
            <?php foreach ($allowedRoles as $value => $label): ?>
                <option value="<?= esc($value, 'attr') ?>" <?= $currentRole === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
<?php endif; ?>
