<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="registration-page legal-page">
    <section id="privacy" class="account-card">
        <p class="eyebrow">Legal notice</p>
        <h1>Privacy policy</h1>
        <p class="draft-note">Draft for class use. Sun Son Solar should review this text before the site goes live.</p>
        <p>Sun Son Solar collects the details you give us when you sign up, request a quote, book a service, or contact support: your name, birthdate, gender, email address, phone number, home or business address, and your service history and payments.</p>
        <p>We use this information only to provide and schedule our services, send receipts, answer support requests, and, if you allow it in your profile, send reminders and promotions. Technicians only see the details needed for a job: your name, address, and the service booked.</p>
        <p>For employees, the "Time in at site" button records your name, the time, and your location at that moment, to track attendance and field activity.</p>
        <p>Passwords are stored as secure hashes, never as readable text. You can update your details or preferences anytime from your profile, or contact us to ask about your data, in line with the Philippine Data Privacy Act of 2012.</p>
    </section>

    <section id="terms" class="account-card legal-section">
        <h1>Terms of use</h1>
        <p>Bookings made on this site are requests until Sun Son Solar approves them. We may adjust a schedule and will notify you on your dashboard when we do.</p>
        <p>Prices shown are starting prices. Final costs are confirmed after consultation and site assessment.</p>
        <p>Keep your username and password private. Sun Son Solar may deactivate accounts that are misused.</p>
        <a class="btn btn-light" href="<?= site_url('/') ?>">Back to home page</a>
    </section>
</main>

<?= $this->include('partials/site_footer') ?>
<?= $this->endSection() ?>
