<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<main class="dashboard-page dashboard-wide">
    <?= $this->include('partials/admin_nav') ?>
    <?= $this->include('partials/flash') ?>

    <div class="dash-grid">
        <section class="account-card">
            <p class="eyebrow">Messages for employees</p>
            <h2>Post an announcement</h2>
            <form method="post" action="<?= site_url('admin/announcements') ?>" class="registration-form">
                <?= csrf_field() ?>
                <label>Title *<input type="text" name="title" value="<?= esc(old('title'), 'attr') ?>" maxlength="150" required></label>
                <label>Category *
                    <select name="category" required>
                        <?php foreach (config('SunSon')->announcementCategories as $category): ?>
                            <option value="<?= $category ?>" <?= old('category') === $category ? 'selected' : '' ?>><?= $category ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Message *<textarea name="message" rows="5" required><?= esc(old('message')) ?></textarea></label>
                <button class="btn btn-primary" type="submit">Post announcement</button>
            </form>
        </section>

        <section class="account-card">
            <h2>Posted announcements</h2>
            <?php if (empty($announcements)): ?>
                <p class="empty-state">No announcements yet.</p>
            <?php else: ?>
                <ul class="notice-list">
                    <?php foreach ($announcements as $post): ?>
                        <li class="notice notice-<?= esc(strtolower($post['category']), 'attr') ?>">
                            <div class="notice-top">
                                <span class="badge badge-<?= esc(strtolower($post['category']), 'attr') ?>"><?= esc($post['category']) ?></span>
                                <small><?= nice_datetime($post['created_at']) ?> · <?= esc($post['posted_by']) ?></small>
                            </div>
                            <h3><?= esc($post['title']) ?></h3>
                            <p><?= nl2br(esc($post['message'])) ?></p>
                            <form method="post" action="<?= site_url('admin/announcements/' . $post['announcement_id'] . '/delete') ?>" onsubmit="return confirm('Remove this announcement?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="link-button danger">Remove</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </div>
</main>
<?= $this->endSection() ?>
