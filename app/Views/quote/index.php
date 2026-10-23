<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="registration-page">
    <section class="account-card">
        <div class="section-heading">
            <p class="eyebrow">Free quote</p>
            <h1>Request a free quote</h1>
            <p>Tell us a little about your property and we will contact you with a personalized solar estimate. No account needed.</p>
        </div>

        <?= $this->include('partials/flash') ?>

        <form class="registration-form" method="post" action="<?= site_url('quote') ?>">
            <?= csrf_field() ?>
            <div class="registration-grid">
                <label>Full name *<input type="text" name="fullname" value="<?= esc(old('fullname', session('name') ?? ''), 'attr') ?>" required></label>
                <label>Email address *<input type="email" name="email" value="<?= esc(old('email', session('email') ?? ''), 'attr') ?>" required></label>
                <label>Phone number *<input type="text" name="phone" value="<?= esc(old('phone'), 'attr') ?>" required></label>
                <label>Interested in *
                    <select name="service_interest" required>
                        <option value="">Choose one</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= esc($service['name'], 'attr') ?>" <?= old('service_interest') === $service['name'] ? 'selected' : '' ?>><?= esc($service['name']) ?></option>
                        <?php endforeach; ?>
                        <option value="Full solar system" <?= old('service_interest') === 'Full solar system' ? 'selected' : '' ?>>Full solar system (design to installation)</option>
                    </select>
                </label>
                <label class="registration-full">Property address *<input type="text" name="address" value="<?= esc(old('address'), 'attr') ?>" required></label>
                <label>Average monthly electric bill (₱) <span>(optional)</span><input type="number" name="monthly_bill" min="0" step="0.01" value="<?= esc(old('monthly_bill'), 'attr') ?>"></label>
                <label class="registration-full">Message <span>(optional)</span><textarea name="message" rows="4"><?= esc(old('message')) ?></textarea></label>
            </div>
            <div class="registration-actions"><button class="btn btn-primary" type="submit">Send quote request</button><a class="btn btn-light" href="<?= site_url('/') ?>">Back to home</a></div>
        </form>
    </section>
</main>

<footer class="registration-footer"><a href="<?= site_url('/') ?>">Return to Sun Son Solar home page</a></footer>
<?= $this->endSection() ?>
