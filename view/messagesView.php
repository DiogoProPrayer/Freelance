<?php function drawMessagesHeader()
{ ?>
    <!DOCTYPE html>
    <html lang='en-US'>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Freelance</title>
        <link rel="icon" href="/images/logo2.png">
        <link rel="stylesheet" href="/css/base.css">
        <link rel="stylesheet" href="/css/components.css">
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/footer.css">
        <link rel="stylesheet" href="/css/message.css">
    </head>

    <body>
    <?php } ?>


    <?php
    function drawMessagesSection($conversations, $contact_info, $contact_id, $user_id)
    { ?>

        <body>

            <main class="messages-container">
                <div class="messages-wrapper">

                    <!-- Sidebar: Conversation List -->
                    <aside class="conversation-sidebar active shadow rounded pad-md">
                        <h2 class="messages-header">All Messages</h2>
                        <ul class="conversation-list">
                            <?php if (empty($conversations) && !$contact_info): ?>
                                <li class="no-conversations">No conversations yet.</li>
                            <?php else: ?>
                                <?php if ($contact_info && !in_array($contact_id, array_column($conversations, 'other_user_id'))): ?>
                                    <!-- Add new contact if they're not in our existing conversations -->
                                    <li class="conversation-item transition new-contact active"
                                        data-conversation-id="new"
                                        data-other-user-id="<?php echo $contact_info->getUserId(); ?>">
                                        <div class="conversation-avatar">
                                            <img src="<?php echo htmlspecialchars('../' . ($contact_info->getProfileImage() ?? '../images/default_user.jpg')); ?>" alt="Avatar">
                                        </div>
                                        <div class="conversation-details">
                                            <div class="conversation-header">
                                                <span class="username"><?php echo htmlspecialchars($contact_info->getUsername()); ?></span>
                                                <span class="time">New</span>
                                            </div>
                                            <p class="message-preview">New conversation</p>
                                        </div>
                                    </li>
                                <?php endif; ?>

                                <?php foreach ($conversations as $conv): ?>
                                    <?php
                                    if (isset($conv['profileImage'])) {
                                        if (strpos($conv['profileImage'], '../') !== 0) {
                                            $conv['profileImage'] = '../' . ltrim($conv['profileImage'], '/');
                                        }
                                    } else {
                                        $conv['profileImage'] = '../images/default_user.jpg';
                                    }

                                    $is_sender = $conv['sender'] == $user_id;
                                    $prefix = $is_sender ? 'Me: ' : '';
                                    $preview = strlen($conv['text']) > 20 ? substr($conv['text'], 0, 20) . '...' : $conv['text'];
                                    $time_diff = time() - strtotime($conv['timeStamp']);
                                    $time_display = $time_diff < 3600 ? round($time_diff / 60) . 'm' : round($time_diff / 3600) . 'h';
                                    $is_active = ($contact_id && $conv['other_user_id'] == $contact_id) ? 'active' : '';
                                    ?>
                                    <li class="conversation-item transition <?php echo $is_active; ?>"
                                        data-conversation-id="<?php echo $conv['id']; ?>"
                                        data-other-user-id="<?php echo $conv['other_user_id']; ?>">
                                        <div class="conversation-avatar">
                                            <img src="<?php echo htmlspecialchars($conv['profileImage']); ?>" alt="Avatar">
                                            <span class="online-status"></span>
                                        </div>
                                        <div class="conversation-details">
                                            <div class="conversation-header">
                                                <span class="username"><?php echo htmlspecialchars($conv['username']); ?></span>
                                                <span class="time"><?php echo $time_display; ?></span>
                                            </div>
                                            <p class="message-preview"><?php echo $prefix . htmlspecialchars($preview); ?></p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </aside>
                <?php } ?>


                <?php
                function drawChatArea($contact_id, $contact_info, $user_id)
                { ?>
                    <!-- Main Chat Area -->
                    <section class="chat-area" id="chatArea">
                        <div class="chat-placeholder" id="chatPlaceholder">
                            <img src="/images/chat-placeholder.png" alt="Chat Placeholder" class="placeholder-image">
                            <h3>Pick up where you left off</h3>
                            <p>Select a conversation and chat away.</p>
                        </div>
                        <div class="chat-messages" id="chatMessages" style="display: none;">
                            <div class="chat-header">
                                <h3 id="chatWith"></h3>
                            </div>
                            <div class="messages-list" id="messagesList">
                                <!-- Messages will be loaded here -->
                            </div>
                            <div class="message-input">
                                <textarea id="messageInput" placeholder="Type a message..."></textarea>
                                <button id="sendMessage" class="transition rounded pad-md">Send</button>
                            </div>
                        </div>
                    </section>


                </div>
            </main>

            <script>
                window.appData = {
                    contactId: <?php echo json_encode($contact_id); ?>,
                    contactInfo: <?php echo json_encode($contact_info); ?>,
                    userId: <?php echo json_encode($user_id); ?>
                };
            </script>
            <script src="/js/message.js"></script>
        </body>
    <?php } ?>