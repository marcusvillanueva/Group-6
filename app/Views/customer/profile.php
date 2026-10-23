<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/customer_nav') ?>
    <?= $this->include('partials/flash') ?>

    <div class="dash-grid dash-grid-wide-left">
        <section class="account-card">
            <p class="eyebrow">Profile</p>
            <h2>Personal information</h2>
            <form method="post" action="<?= site_url('customer/profile') ?>" class="registration-form">
                <?= csrf_field() ?>
                <div class="registration-grid">
                    <?= $this->include('partials/person_fields') ?>
                    <label>Username <span>(cannot be changed)</span><input type="text" value="<?= esc($person['username'], 'attr') ?>" disabled></label>
                    <label class="registration-full">Address *<input type="text" name="address" value="<?= esc(old('address', $person['address']), 'attr') ?>" required></label>
                </div>

                <fieldset id="preferences" class="pref-box">
                    <legend>Preferences</legend>
                    <label class="check-row"><input type="checkbox" name="receive_reminders" value="1" <?= $person['receive_reminders'] ? 'checked' : '' ?>> Send me reminders before my scheduled services</label>
                    <label class="check-row"><input type="checkbox" name="receive_promotions" value="1" <?= $person['receive_promotions'] ? 'checked' : '' ?>> Send me promotions and new offers</label>
                    <p class="small-note">Important updates about your bookings are always sent.</p>
                </fieldset>

                <div class="registration-actions"><button class="btn btn-primary" type="submit">Save changes</button></div>
            </form>
        </section>

        <section id="password" class="account-card">
            <p class="eyebrow">Security</p>
            <h2>Change password</h2>
            <form method="post" action="<?= site_url('customer/profile/password') ?>" class="registration-form">
                <?= csrf_field() ?>
                <label>Current password *<input type="password" name="current_password" autocomplete="current-password" required></label>
                <label>New password * <span>(at least 8 characters)</span><input type="password" name="new_password" autocomplete="new-password" minlength="8" required></label>
                <label>Confirm new password *<input type="password" name="confirm_password" autocomplete="new-password" minlength="8" required></label>
                <button class="btn btn-primary" type="submit">Update password</button>
            </form>
        </section>
    </div>
</main>
<?= $this->endSection() ?>
