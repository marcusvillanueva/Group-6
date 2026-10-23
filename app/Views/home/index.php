<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main>
    <?php if (session('login_error')): ?>
        <div class="form-message error-message" role="alert"><?= esc(session('login_error')) ?></div>
    <?php endif; ?>
    <?= $this->include('partials/flash') ?>

    <section class="hero section" aria-labelledby="hero-title">
        <div class="hero-copy">
            <p class="eyebrow">Clean energy for modern living</p>
            <h1 id="hero-title">Power your future with solar</h1>
            <p class="hero-text">Sun Son Solar helps homes and businesses cut energy costs, improve sustainability, and build long-term energy independence with personalized renewable energy solutions.</p>

            <div class="cta-group">
                <a href="<?= site_url('quote') ?>" class="btn btn-primary">Request a free quote</a>
                <a href="#services" class="btn btn-secondary">Explore services</a>
            </div>

            <div class="feature-pills" aria-label="Key solar benefits">
                <span>Lower power bills</span>
                <span>Cleaner living</span>
                <span>Energy independence</span>
            </div>

            <ul class="hero-metrics">
                <li><strong>30%</strong><span>Average savings</span></li>
                <li><strong>24/7</strong><span>Monitoring support</span></li>
                <li><strong>100%</strong><span>Committed service</span></li>
            </ul>
        </div>

        <div class="hero-visual" aria-label="Solar energy overview">
            <div class="hero-card main-card">
                <p class="mini-label">Solar performance</p>
                <div class="performance-ring"><span>82%</span></div>
                <div class="solar-stats">
                    <div><strong>11.4 kW</strong><span>System size</span></div>
                    <div><strong>4.6k</strong><span>Monthly savings</span></div>
                </div>
            </div>
            <div class="hero-card floating-card">Energy independence starts here.</div>
        </div>
    </section>

    <section id="about" class="section company-intro">
        <div class="section-heading">
            <p class="eyebrow">Why Sun Son Solar</p>
            <h2>Smarter solar for a brighter tomorrow.</h2>
        </div>

        <div class="intro-grid">
            <div class="intro-copy">
                <p>We design reliable solar systems that make it easier for families and businesses to enjoy clean power, lower utility bills, and long-term peace of mind. Our mission is to make renewable energy simple, accessible, and rewarding.</p>
                <p class="intro-founders">Founded by Katherine "Kat" Sinagaraw and her father, Santino "Sonny" Sinagaraw, with our first office in Pasig City.</p>
            </div>

            <div class="benefits" aria-label="Solar benefits list">
                <article class="benefit-item">
                    <span class="benefit-icon">✓</span>
                    <div><h3>Cost savings</h3><p>Reduce monthly electric bills and protect against rising rates.</p></div>
                </article>
                <article class="benefit-item">
                    <span class="benefit-icon">✓</span>
                    <div><h3>Sustainability</h3><p>Lower your carbon footprint and support a cleaner energy future.</p></div>
                </article>
                <article class="benefit-item">
                    <span class="benefit-icon">✓</span>
                    <div><h3>Energy independence</h3><p>Generate your own power and rely less on traditional utility grids.</p></div>
                </article>
            </div>
        </div>
    </section>

    <section id="services" class="section services">
        <div class="section-heading center">
            <p class="eyebrow">Our services</p>
            <h2>Simple solutions from consultation to maintenance.</h2>
        </div>

        <div class="service-grid">
            <article class="service-card"><span class="service-number">01</span><h3>Consultation</h3><p>We assess your property, usage, and goals to build the right solar plan for your needs.</p></article>
            <article class="service-card"><span class="service-number">02</span><h3>Installation</h3><p>Our installers handle each step with attention to safety and project planning.</p></article>
            <article class="service-card"><span class="service-number">03</span><h3>Maintenance</h3><p>Keep your system performing with regular inspections, support, and monitoring.</p></article>
        </div>

        <?php if (! empty($services)): ?>
            <div class="all-services">
                <p class="mini-label">Everything we offer</p>
                <div class="feature-pills">
                    <?php foreach ($services as $service): ?>
                        <span><?= esc($service['name']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <section id="products" class="section products">
        <div class="section-heading center">
            <p class="eyebrow">Our products</p>
            <h2>Quality solar equipment for every setup.</h2>
        </div>

        <div class="service-grid product-grid">
            <?php foreach ($products as $product): ?>
                <article class="service-card">
                    <span class="service-number"><?= esc(strtoupper($product['category'])) ?></span>
                    <h3><?= esc($product['name']) ?></h3>
                    <p><?= esc($product['description']) ?></p>
                    <p class="product-price"><?= esc(peso($product['price'])) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="login" class="section account-section">
        <div class="section-heading">
            <p class="eyebrow">Access your account</p>
            <h2>Customer accounts and secure employee access.</h2>
        </div>

        <?php if (is_logged_in()): ?>
            <article class="account-card logged-in-card">
                <h3>You are logged in as <?= esc(session('name')) ?></h3>
                <p>Account type: <?= esc(role_label(session('role'))) ?></p>
                <div class="registration-actions">
                    <a class="btn btn-primary" href="<?= dashboard_url() ?>">Go to my dashboard</a>
                    <a class="btn btn-light" href="<?= site_url('logout') ?>">Log out</a>
                </div>
            </article>
        <?php else: ?>
            <div class="account-grid">
                <article class="account-card signup-card">
                    <h3>Customer sign up</h3>
                    <p>New customers can create their own account to book services, track schedules, and view receipts.</p>
                    <a class="btn btn-primary" href="<?= site_url('register') ?>">Create customer account</a>
                </article>

                <article class="account-card login-card">
                    <h3>Customer login</h3>
                    <p>Already have a customer account? Log in to access your account.</p>
                    <form class="mini-form" action="<?= site_url('login') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="account_type" value="customer">
                        <label for="customer-username">Username
                            <input type="text" id="customer-username" name="username" autocomplete="username" required>
                        </label>
                        <label for="customer-password">Password
                            <input type="password" id="customer-password" name="password" autocomplete="current-password" required>
                        </label>
                        <button class="btn btn-primary" type="submit">Log in as customer</button>
                    </form>
                </article>

                <article class="account-card login-card">
                    <h3>Employee login</h3>
                    <p>Employee accounts are created by authorized HR or Admin staff. Employees cannot register themselves here.</p>
                    <form class="mini-form" action="<?= site_url('login') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="account_type" value="employee">
                        <label for="employee-username">Username
                            <input type="text" id="employee-username" name="username" autocomplete="username" required>
                        </label>
                        <label for="employee-password">Password
                            <input type="password" id="employee-password" name="password" autocomplete="current-password" required>
                        </label>
                        <button class="btn btn-primary" type="submit">Log in securely</button>
                    </form>
                </article>
            </div>
        <?php endif; ?>
    </section>

    <section class="section testimonials">
        <div class="section-heading center">
            <p class="eyebrow">Customer stories</p>
            <h2>Real people. Real savings. Real results.</h2>
        </div>
        <div class="testimonial-grid">
            <article class="testimonial-card"><p>“The process was simple and the team explained every part clearly. Our bills dropped right away.”</p><div class="person"><strong>Lurie Pabora</strong><span>Homeowner</span></div></article>
            <article class="testimonial-card"><p>“We wanted to reduce our carbon footprint without sacrificing performance. Sun Son Solar delivered.”</p><div class="person"><strong>Vin De Lara</strong><span>Small business owner</span></div></article>
            <article class="testimonial-card"><p>“The installation was professional, the maintenance support has been excellent, and the savings are visible.”</p><div class="person"><strong>Izaack Reyes</strong><span>Property manager</span></div></article>
        </div>
    </section>

    <section id="quote" class="section cta-banner">
        <div><p class="eyebrow">Ready to switch?</p><h2>Start your solar journey with a free consultation.</h2></div>
        <div class="cta-group cta-banner-actions">
            <a href="<?= site_url('quote') ?>" class="btn btn-secondary">Request a free quote</a>
            <?php if (session('account_type') === 'customer'): ?>
                <a href="<?= site_url('customer/schedules') ?>" class="btn btn-primary">Schedule a service</a>
            <?php else: ?>
                <a href="<?= site_url('register') ?>" class="btn btn-primary">Schedule a service</a>
            <?php endif; ?>
        </div>
    </section>

    <section id="faq" class="section faq-news">
        <div class="faq-box">
            <div class="section-heading"><p class="eyebrow">FAQ</p><h2>Common questions.</h2></div>
            <div class="faq-list">
                <div><h3>How long does installation take?</h3><p>Installation time depends on the project, design approval, and property requirements.</p></div>
                <div><h3>Do you offer maintenance plans?</h3><p>Contact the company to confirm the available maintenance and monitoring options.</p></div>
                <div><h3>Is solar worth it for my home?</h3><p>A consultation can review your energy usage, property layout, and goals to help determine a suitable option.</p></div>
                <div><h3>How do I book a service?</h3><p>Create a free customer account, then choose a service, date, and time from the Schedules page of your dashboard.</p></div>
            </div>
        </div>

        <div class="news-box">
            <div class="section-heading"><p class="eyebrow">Blog &amp; news</p><h2>Insights for smarter energy choices.</h2></div>
            <div class="news-list">
                <article><span>April 2026</span><h3>How solar can cut utility costs year-round</h3></article>
                <article><span>March 2026</span><h3>Why businesses are switching to sustainable energy</h3></article>
                <article><span>February 2026</span><h3>Sun Son Solar expands support for commercial rooftops</h3></article>
            </div>
        </div>
    </section>
</main>

<?= $this->include('partials/site_footer') ?>
<?= $this->endSection() ?>
