<?php
// Shows the green/red messages after a form is sent.
// Controllers set them with ->with('success', '...') or ->with('error', '...'),
// and validation errors come from ->withInput() after a failed validateData().
$errors = $errors ?? session('errors') ?? [];
?>
<?php if (session('success')): ?>
    <div class="form-message success-message" role="status"><?= esc(session('success')) ?></div>
<?php endif; ?>

<?php if (session('error')): ?>
    <div class="form-message error-message" role="alert"><?= esc(session('error')) ?></div>
<?php endif; ?>

<?php if (! empty($errors)): ?>
    <div class="form-message error-message" role="alert">
        <strong>Please correct the following:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
