/* =========================
*  PHP VARIABLES 
* ========================= */
const contactId = window.appData.contactId;
const contactInfo = window.appData.contactInfo;
const userId = window.appData.userId;


/* =========================
*  ELEMENT REFERENCES
* ========================= */
const placeholder = document.getElementById('chatPlaceholder');
const chatBox = document.getElementById('chatMessages');
const messagesList = document.getElementById('messagesList');
const sidebar = document.querySelector('.conversation-sidebar');
const chatArea=document.querySelector('.chat-area');
const chatWith = document.getElementById('chatWith');
const msgInput = document.getElementById('messageInput');
const sendBtn = document.getElementById('sendMessage');

/* =========================
*  STATE
* ========================= */
let currentOtherId = null;
let lastServerId = 0; // Atualiza vindo do fetch_new
let lastDisplayedId = 0; // Atualiza sempre que inserimos algo
let isPolling = false;

// Auto-load contact from URL if provided
if (contactId && contactInfo){
    currentOtherId = contactId;
    chatWith.textContent =contactInfo['username']; 
    placeholder.style.display = 'none';
    chatBox.style.display = 'flex';
    messagesList.innerHTML = `<p class="text-center">Start a conversation with ${chatWith.textContent}...</p>`;

    const existingContact = document.querySelector(`.conversation-item[data-other-user-id="${currentOtherId}"]`);
    if (existingContact && !existingContact.classList.contains('new-contact')) {
        loadConversation(existingContact);
    }
}


/* =========================
 *  HELPERS
 * ========================= */
function fmtTime(iso) {
    const d = new Date(iso);
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function msgHTML(m) {
    const who = (m.sender == userId) ? 'sent' : 'received';
    const sanitized = m.text.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return `<div class="message ${who}" data-id="${m.id}">
                <p>${sanitized}</p>
                <span class="timestamp">${fmtTime(m.timeStamp)}</span>
            </div>`;
}

function scrollToBottom() {
    setTimeout(() => {
        messagesList.scrollTop = messagesList.scrollHeight;
    }, 10);
}

/* =========================
 *  LOAD FULL CONVERSATION
 * ========================= */
async function loadConversation(item) {
    currentOtherId = item.dataset.otherUserId;
    chatWith.textContent = item.querySelector('.username').textContent;
    placeholder.style.display = 'none';
    chatBox.style.display = 'flex';
    messagesList.innerHTML = '<p style="text-align:center">Loading…</p>';

    document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
    item.classList.add('active');


    try {
        const res = await fetch(`/controller/chatAreaController.php?action=fetch&other_id=${currentOtherId}`);
        const data = await res.json();

        messagesList.innerHTML = data.map(msgHTML).join('');
        scrollToBottom();

        const maxId = data.reduce((mx, m) => Math.max(mx, Number(m.id)), 0);
        lastServerId = maxId;
        lastDisplayedId = maxId;
    } catch (e) {
        messagesList.innerHTML = '<p style="text-align:center;color:red">Error loading messages</p>';
        console.error(e);
    }
}

/* =========================
 *  POLLING FOR NEW MESSAGES
 * ========================= */
async function pollNew() {
    if (!currentOtherId || isPolling) return;
    isPolling = true;
    try {
        const res = await fetch(`../controller/chatAreaController.php?action=fetch_new&other_id=${currentOtherId}&after=${lastServerId}`);
        if (!res.ok) throw new Error('poll failed');
        const data = await res.json();

        data.forEach(m => {
            if (!messagesList.querySelector(`.message[data-id="${m.id}"]`)) {
                messagesList.insertAdjacentHTML('beforeend', msgHTML(m));
                lastDisplayedId = Math.max(lastDisplayedId, Number(m.id));
            }
            lastServerId = Math.max(lastServerId, Number(m.id));
        });

        if (data.length) scrollToBottom();
    } catch (e) {
        console.error(e);
    } finally {
        isPolling = false;
    }
}
setInterval(pollNew, 2000);

async function sendMessage() {
    const text = msgInput.value.trim();
    if (!text || !currentOtherId) return;
        msgInput.value = '';
        try {
            const res = await fetch('/controller/chatAreaController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'send',
                    other_id: currentOtherId,
                    text
                })
            });
            if (!res.ok) throw new Error('send failed');
            const r = await res.json(); // { ok:1, id:…, timeStamp:… }

            // Handle first message in a new conversation
            const newContact = document.querySelector(`.conversation-item.new-contact[data-other-user-id="${currentOtherId}"]`);
            if (newContact) {
                newContact.classList.remove('new-contact');
                newContact.dataset.conversationId = r.id;
            }

            // Update sidebar preview
            refreshSidebarPreview(currentOtherId, {
                sender: userID,
                text: text,
                timeStamp: r.timeStamp
            });

            // This will pick up our message from the server
            pollNew();
        } catch (e) {
            console.error(e);
        }
}

function refreshSidebarPreview(otherId, msg) {
    const row = document.querySelector(
        `.conversation-item[data-other-user-id="${otherId}"]`
    );
    if (!row) return; // should not happen
    // // prefix ("Me: ") only if current user sent it
    const prefix = (msg.sender == userId) ? 'Me: ' : '';
    row.querySelector('.message-preview').textContent = prefix + (msg.text.length > 20 ? msg.text.slice(0, 20) + '…' : msg.text);

    // update relative time
    row.querySelector('.time').textContent = 'now';

    // OPTIONAL: move row to top of list so newest chats bubble up
    const list = row.parentElement;
    list.insertBefore(row, list.firstElementChild);
}

/* =========================
 *  EVENT LISTENERS
 * ========================= */
document.querySelectorAll('.conversation-item').forEach(el => el.addEventListener('click', () =>{ 
    loadConversation(el);
    sidebar.classList.remove('active');
    chatArea.classList.add('active');
}));
sendBtn.addEventListener('click', sendMessage);
msgInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});



