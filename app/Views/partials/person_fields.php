<?php
// The personal details every customer and employee has (from the client interview).
// Used by: customer sign up, HR/Admin "create employee", edit user, customer profile.
// $person = existing values when editing (optional). old() keeps what was typed after an error.
$person = $person ?? [];
$value  = static fn (string $field) => old($field, $person[$field] ?? '');
$gender = $value('gender');
?>
<label>First name *<input type="text" name="firstname" value="<?= esc($value('firstname'), 'attr') ?>" required></label>
<label>Last name *<input type="text" name="lastname" value="<?= esc($value('lastname'), 'attr') ?>" required></label>
<label>Middle name <span>(optional)</span><input type="text" name="middlename" value="<?= esc($value('middlename'), 'attr') ?>"></label>
<label>Birthdate *<input type="date" name="birthdate" value="<?= esc($value('birthdate'), 'attr') ?>" required></label>
<label>Gender *
    <select name="gender" required>
        <option value="">Select one</option>
        <?php foreach (config('SunSon')->genders as $option): ?>
            <option value="<?= esc($option, 'attr') ?>" <?= $gender === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
        <?php endforeach; ?>
    </select>
</label>
<label>Email address *<input type="email" name="email" value="<?= esc($value('email'), 'attr') ?>" required></label>
<label>Phone number *<input type="text" name="phone" value="<?= esc($value('phone'), 'attr') ?>" required></label>
