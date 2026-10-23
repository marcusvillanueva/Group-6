<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
// One section for services and one for products; both use the same form layout.
$sections = [
    'services' => ['Services', 'Service categories, pricing and availability. Customers can only book available services.', $services, 'service_id'],
    'products' => ['Products', 'Products shown on the homepage. Unavailable products are hidden.', $products, 'product_id'],
];
?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <?php foreach ($sections as $kind => [$heading, $help, $items, $key]): ?>
        <section id="<?= $kind ?>" class="account-card section-gap">
            <p class="eyebrow">Settings</p>
            <h2><?= $heading ?></h2>
            <p><?= $help ?> Leave the price blank to show "Price on request".</p>

            <div class="settings-list">
                <div class="settings-row settings-header">
                    <span>Name</span><span>Category</span><span>Description</span><span>Price (₱)</span><span>Available</span><span></span>
                </div>
                <?php foreach ($items as $item): ?>
                    <form method="post" action="<?= site_url('admin/settings/' . $kind . '/save') ?>" class="settings-row <?= $item['is_available'] ? '' : 'row-muted' ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $item[$key] ?>">
                        <input type="text" name="name" value="<?= esc($item['name'], 'attr') ?>" aria-label="Name" required>
                        <input type="text" name="category" value="<?= esc($item['category'], 'attr') ?>" aria-label="Category" required>
                        <input type="text" name="description" value="<?= esc((string) $item['description'], 'attr') ?>" aria-label="Description">
                        <input type="number" name="price" min="0" step="0.01" value="<?= esc((string) $item['price'], 'attr') ?>" aria-label="Price" placeholder="On request">
                        <label class="check-row"><input type="checkbox" name="is_available" value="1" <?= $item['is_available'] ? 'checked' : '' ?>> <span class="sr-label">Available</span></label>
                        <button class="btn btn-light btn-small" type="submit">Save</button>
                    </form>
                <?php endforeach; ?>

                <form method="post" action="<?= site_url('admin/settings/' . $kind . '/save') ?>" class="settings-row settings-new">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="0">
                    <input type="text" name="name" placeholder="New <?= $kind === 'services' ? 'service' : 'product' ?> name" aria-label="Name" required>
                    <input type="text" name="category" placeholder="Category" aria-label="Category" required>
                    <input type="text" name="description" placeholder="Short description" aria-label="Description">
                    <input type="number" name="price" min="0" step="0.01" placeholder="On request" aria-label="Price">
                    <label class="check-row"><input type="checkbox" name="is_available" value="1" checked> <span class="sr-label">Available</span></label>
                    <button class="btn btn-primary btn-small" type="submit">Add</button>
                </form>
            </div>
        </section>
    <?php endforeach; ?>
</main>
<?= $this->endSection() ?>
