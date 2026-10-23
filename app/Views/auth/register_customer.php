<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="registration-page">
    <section class="account-card">
        <div class="section-heading"><p class="eyebrow">Customer registration</p><h1>Create your account</h1><p>Fill out the required fields below. Employee accounts must be created by HR or Admin.</p></div>

        <?= $this->include('partials/flash') ?>

        <form class="registration-form" method="post" action="<?= site_url('register') ?>">
            <?= csrf_field() ?>
            <div class="registration-grid">
                <?= $this->include('partials/person_fields') ?>
                <label>Username *<input type="text" name="username" value="<?= esc(old('username'), 'attr') ?>" autocomplete="username" required></label>
                <label class="registration-full">Address *<input type="text" name="address" value="<?= esc(old('address'), 'attr') ?>" required></label>
                <label class="registration-full">Password * <span>(at least 8 characters)</span><input type="password" name="password" autocomplete="new-password" minlength="8" required></label>
            </div>
            <p class="privacy-note">Your password is stored as a hash, not as readable text. See our <a href="<?= site_url('legal') ?>#privacy">privacy policy</a>.</p>
            <div class="registration-actions"><button class="btn btn-primary" type="submit">Create customer account</button><a class="btn btn-light" href="<?= site_url('/') ?>">Cancel</a></div>
        </form>
    </section>
</main>

<footer class="registration-footer"><a href="<?= site_url('/') ?>">Return to Sun Son Solar home page</a></footer>
<?= $this->endSection() ?>
