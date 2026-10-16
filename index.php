<?php
session_start();

$login_error = "";
if (isset($_SESSION["login_error"])) {
    $login_error = $_SESSION["login_error"];
    unset($_SESSION["login_error"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sun Son Solar | Power Your Future</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell">
        <header class="site-header">
            <a class="brand" href="index.php" aria-label="Sun Son Solar home">SUN SON SOLAR</a>

            <nav class="main-nav" aria-label="Main navigation">
                <a href="#services">Services</a>
                <a href="#about">Learn about the company</a>
                <a href="#support">Reach out support</a>
            </nav>

            <div class="nav-actions">
                <a href="#login" class="btn btn-light">Login</a>
                <a href="#quote" class="btn btn-primary">Get a quote</a>
            </div>
        </header>

        <main>
            <?php if ($login_error != ""): ?>
                <div class="form-message error-message" role="alert">
                    <?php echo htmlspecialchars($login_error, ENT_QUOTES, "UTF-8"); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET["registered"]) && $_GET["registered"] == "1"): ?>
                <div class="form-message success-message" role="status">
                    Your customer account was created. You can now log in below.
                </div>
            <?php endif; ?>

            <section class="hero section" aria-labelledby="hero-title">
                <div class="hero-copy">
                    <p class="eyebrow">Clean energy for modern living</p>
                    <h1 id="hero-title">Power your future with solar</h1>
                    <p class="hero-text">Sun Son Solar helps homes and businesses cut energy costs, improve sustainability, and build long-term energy independence with personalized renewable energy solutions.</p>

                    <div class="cta-group">
                        <a href="#quote" class="btn btn-primary">Request a free quote</a>
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
            </section>

            <section id="login" class="section account-section">
                <div class="section-heading">
                    <p class="eyebrow">Access your account</p>
                    <h2>Customer accounts and secure employee access.</h2>
                </div>

                <div class="account-grid">
                    <article class="account-card signup-card">
                        <h3>Customer sign up</h3>
                        <p>New customers can create their own account to manage their information and access future service features.</p>
                        <a class="btn btn-primary" href="register_customer.php">Create customer account</a>
                    </article>

                    <article class="account-card login-card">
                        <h3>Customer login</h3>
                        <p>Already have a customer account? Log in to access your account.</p>
                        <form class="mini-form" action="login.php" method="post">
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
                        <form class="mini-form" action="login.php" method="post">
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
                <a href="#support" class="btn btn-primary">Schedule a service</a>
            </section>

            <section id="faq" class="section faq-news">
                <div class="faq-box">
                    <div class="section-heading"><p class="eyebrow">FAQ</p><h2>Common questions.</h2></div>
                    <div class="faq-list">
                        <div><h3>How long does installation take?</h3><p>Installation time depends on the project, design approval, and property requirements.</p></div>
                        <div><h3>Do you offer maintenance plans?</h3><p>Contact the company to confirm the available maintenance and monitoring options.</p></div>
                        <div><h3>Is solar worth it for my home?</h3><p>A consultation can review your energy usage, property layout, and goals to help determine a suitable option.</p></div>
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

        <footer id="support" class="site-footer">
            <div class="footer-grid">
                <div><div class="brand footer-brand">SUN SON SOLAR</div><p>We help people and businesses move toward cleaner energy with trusted solar guidance and dependable service.</p></div>
                <div><h3>Company</h3><ul><li><a href="#about">About us</a></li><li><a href="#services">Services</a></li><li><a href="#quote">Request a quote</a></li></ul></div>
                <div><h3>Contact</h3><ul><li><a href="mailto:hello@sunsonsolar.com">hello@sunsonsolar.com</a></li><li>(555) 214-7748</li><li>780 Greenway Ave, Suite 200</li></ul></div>
                <div><h3>Follow us</h3><ul><li><a href="#support">Instagram</a></li><li><a href="#support">Facebook</a></li><li><a href="#support">LinkedIn</a></li></ul></div>
            </div>
            <div class="footer-bottom"><span>© 2026 Sun Son Solar. All rights reserved.</span><div><a href="#support">Privacy policy</a><a href="#support">Terms</a><a href="#faq">FAQ</a></div></div>
        </footer>
    </div>
</body>
</html>
