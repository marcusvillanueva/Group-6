<?php
// Chat-style list of messages in a support ticket.
// $messages = rows from ticket_messages, $viewer = 'customer' or 'staff'
?>
<div class="chat-thread">
    <?php foreach ($messages as $msg): ?>
        <div class="chat-bubble <?= $msg['sender_type'] === $viewer ? 'mine' : 'theirs' ?>">
            <div class="chat-meta">
                <strong><?= esc($msg['sender_name']) ?></strong>
                <?php if ($msg['sender_type'] === 'staff'): ?><span class="badge badge-type">Sun Son Solar</span><?php endif; ?>
                <small><?= nice_datetime($msg['created_at']) ?></small>
            </div>
            <p><?= nl2br(esc($msg['message'])) ?></p>
        </div>
    <?php endforeach; ?>
</div>
