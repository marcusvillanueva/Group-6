<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sun Son Solar') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
</head>
<body>
    <div class="page-shell">
        <?= $this->include('partials/header') ?>

        <?= $this->renderSection('content') ?>
    </div>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
