<?php $home = site_url('/'); ?>
<footer id="support" class="site-footer">
    <div class="footer-grid">
        <div><div class="brand footer-brand">SUN SON SOLAR</div><p>We help people and businesses move toward cleaner energy with trusted solar guidance and dependable service.</p></div>
        <div><h3>Company</h3><ul><li><a href="<?= $home ?>#about">About us</a></li><li><a href="<?= $home ?>#services">Services</a></li><li><a href="<?= $home ?>#products">Products</a></li><li><a href="<?= site_url('quote') ?>">Request a quote</a></li></ul></div>
        <div><h3>Contact</h3><ul><li><a href="mailto:hello@sunsonsolar.com">hello@sunsonsolar.com</a></li><li>0929 123 0983</li><li>Pasig City, Metro Manila</li><?php if (session('account_type') === 'customer'): ?><li><a href="<?= site_url('customer/support') ?>">Open a support ticket</a></li><?php endif; ?></ul></div>
        <div><h3>Follow us</h3><ul><li><a href="#support">Instagram</a></li><li><a href="#support">Facebook</a></li><li><a href="#support">LinkedIn</a></li></ul></div>
    </div>
    <div class="footer-bottom"><span>© <?= date('Y') ?> Sun Son Solar. All rights reserved.</span><div><a href="<?= site_url('legal') ?>#privacy">Privacy policy</a><a href="<?= site_url('legal') ?>#terms">Terms</a><a href="<?= $home ?>#faq">FAQ</a></div></div>
</footer>
