@extends('layouts.app')
@php
    $urlUserId = request()->id ? Crypt::decrypt(request()->id) : null;
@endphp
<style>
    /* chatページだけnavbarアイコン・ロゴ間隔を広げる */
    .nav-body {
        gap: 1.2rem !important;
    }
</style>
<livewire:vocabulary-modal />

{{-- chat screen main template --}}
@section('content')
<div class="container w-75 h-100 my-2">
    <div style="display:flex;gap:24px;">

        {{-- left side: chat partner selection form --}}
        <div style="width:220px;min-width:180px;">
            <form id="user-select-form" method="GET" action="/chat">
                <div class="mb-2">
                    <label>Select Chat Partner:</label>
                    <div style="max-height:320px;overflow-y:auto;">

                        @foreach($users as $user)
                        <button type="submit" name="to_user_id" value="{{ Crypt::encrypt($user->id) }}"
                            class="btn btn-light w-100 mb-2"
                            style="display:flex;align-items:center;gap:10px;padding:6px 10px;text-align:left;">

                            @if($user->profile && $user->profile->avatar)
                            <img src="{{ $user->profile->avatar }}" class="rounded-circle"
                                style="width:32px;height:32px;object-fit:cover;">
                            @else
                            <i class="fas fa-user-circle" style="font-size:32px;color:#bbb;"></i>
                            @endif
                            <span>{{ $user->name }}</span>
                        </button>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        {{-- right side: chat main body --}}
        <div style="flex:1;">
            {{-- chat room title --}}
            <h5>Chat with {{ $toUser->name ?? '...' }}</h5>
            {{-- display message area --}}
            <div id="chat-box"
                style="height:400px;overflow-y:scroll;border:1px solid #ccc;padding:10px;margin-bottom:10px;">
            </div>
            {{-- send messages area--}}
            <form id="chat-form" enctype="multipart/form-data">
                <input type="hidden" name="to_user_id" id="to_user_id" value="{{ $urlUserId ?? $to_user_id }}">
                <div class="mb-2" style="display:flex;align-items:center;gap:8px;">
                    {{-- upload pictures--}}
                    <label for="image"
                        style="cursor:pointer;width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:#e0e0e0;border-radius:50%;">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="9" y="4" width="2" height="12" rx="1" fill="#333" />
                            <rect x="4" y="9" width="12" height="2" rx="1" fill="#333" />
                        </svg>
                    </label>
                    <input type="file" name="image" id="image" class="form-control" style="display:none;">
                    {{-- message＋emoji＋send button input area（emoji and send button on the right end） --}}
                    <div style="display:flex;flex:1;position:relative;align-items:center;">
                        <input type="text" name="content" id="content" class="form-control"
                            placeholder="Enter your message" style="width:100%;padding-right:96px;">
                        <input list="emojis" name="emoji" id="emoji" class="form-control" placeholder="😊"
                            style="position:absolute;right:48px;top:0;width:40px;height:100%;border:none;background:transparent;">
                        <datalist id="emojis">
                            <option value="😀">😀</option>
                            <option value="😂">😂</option>
                            <option value="😊">😊</option>
                            <option value="😍">😍</option>
                            <option value="👍">👍</option>
                            <option value="🙏">🙏</option>
                            <option value="🎉">🎉</option>
                            <option value="🥺">🥺</option>
                            <option value="😎">😎</option>
                            <option value="😭">😭</option>
                            <option value="😡">😡</option>
                            <option value="💖">💖</option>
                            <option value="💡">💡</option>
                            <option value="🔥">🔥</option>
                            <option value="🌟">🌟</option>
                            <option value="🐈">🐈</option>
                        </datalist>
                        {{-- send button --}}
                        <button type="submit" class="btn btn-yellow"
                            style="position:absolute;right:0;top:0;width:40px;min-width:40px;height:100%;padding:0;display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path d="M2 21L23 12L2 3V10L17 12L2 14V21Z" fill="#fff" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Three-step report modal -->
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Report</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <!-- Hidden: target message -->
                    <input type="hidden" id="report_message_id">

                    <!-- Step 1: Confirm -->
                    <div id="report-step-1">
                        <div style="margin-bottom:10px;">
                            <!-- message image (if any) -->
                            <span id="report_message_image_wrapper" style="display:none;">
                                <img id="report_message_image" src=""
                                    style="max-width:100px;max-height:100px;border-radius:8px;">
                            </span>
                            <!-- message content -->
                            <div>
                                <strong>Reported Message:</strong>
                                <p id="report_message_content" style="font-size:1.1em;word-break:break-all;"></p>
                            </div>
                        </div>
                        <p class="mb-0">Are you sure you want to report this message?</p>
                    </div>

                    <!-- Step 2: Details -->
                    <div id="report-step-2" style="display:none;">
                        <div class="mb-3">
                            <div class="form-label mb-3 fw-bold">Please provide details</div>
                            <select id="report_reason" name="violation_reason_id" class="form-select" required>
                                <option value="" selected disabled>{{ __('Select a reason') }}</option>
                                @foreach($violationReasons as $reason)
                                <option value="{{ $reason->id }}">
                                    {{ $reason->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"></label>
                            <textarea id="report_details" name="detail" class="form-control" rows="3"
                                placeholder="Additional details (optional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Screenshot etc (optional)</label>
                            <input type="file" id="report_file" name="file" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <!-- Step 3: Submitted -->
                    <div id="report-step-3" style="display:none;">
                        <p class="fw-bold">Your report has been submitted.</p>
                        <p class="mt-1"> Thank you for your cooperation!</p>
                    </div>
                </div>

                <!-- Footer (dynamic buttons) -->
                <div class="modal-footer">
                    <!-- Step 1 buttons -->
                    <div id="report-footer-1">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="report-next-1">Next</button>
                    </div>

                    <!-- Step 2 buttons -->
                    <div id="report-footer-2" style="display:none;">
                        <button type="button" class="btn btn-secondary me-2" id="report-back-2">Back</button>
                        <button type="button" class="btn btn-danger" id="report-submit-2">Report</button>
                    </div>

                    <!-- Step 3 buttons -->
                    <div id="report-footer-3" style="display:none;">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- JavaScript for chat functionality --}}
<script>
    const targetMessageId = new URLSearchParams(window.location.search).get('message_id');
    const loadedMessages = new Set();  // * Global set to track loaded message IDs
    let previousMessage = null; // * To track previous message for date comparison

    function fetchMessages(to_user_id){// fetch message list from server
        return fetch(`/chat/fetch?to_user_id=${to_user_id}`)
            .then(res => res.json())
    }

    function formatMessage(msg, box, myId, previousMessage) { // format a single message for display
        // image tag
        let imgTag = (msg.image_path && msg.image_path !== 'null' && msg.image_path.length > 0)
            ? `<img src='${msg.image_path}' style='max-width:100px;'>`
            : "";

        // emoji tag
        let emojiTag = msg.emoji ? msg.emoji : "";

        // read/unread display
        let readTag = msg.is_read
            ? '<span style="color:gray;">(Read)</span>'
            : '<span style="color:gray;">(Unread)</span>';

        // time tag(Japanese time zone)
        let dateString = new Date(msg.sent_at + 'Z').toLocaleString('ja-JP', { timeZone: 'Asia/Tokyo' }).slice(0, -3);

         // date tag (only if different from previous message)

        let dateTag = msg.sent_at
        ? `<span style='color:gray;font-size:0.9em;'>${dateString.split(' ')[0]}</span>`
        : "";

        if (previousMessage) {
            const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
            let prevDateString = new Date(previousMessage.sent_at + 'Z').toLocaleString('ja-JP', { timeZone: tz|| 'Asia/Tokyo' }).slice(0, -3);
            if (dateString.split(' ')[0] === prevDateString.split(' ')[0]) {
                dateTag = ""; // no date tag if same date as previous message
            }
        }

        let timeTag = msg.sent_at
            ? `<span style='color:gray;font-size:0.9em;'>${dateString.split(' ')[1]}</span>`
            : "";

        // alignment, background color, and name
        let align = msg.user_id == myId ? "right" : "left";
        let bgColor = msg.user_id == myId ? "#e0f7fa" : "#fff9e1";

        let avatarTag;
        if (msg.user_id == myId) {
            avatarTag = msg.user_avatar != null && msg.user_avatar?.length > 0
                ? `<img src='${msg.user_avatar}' class="rounded-circle" style="width:32px;height:32px;">`
                : `<i class="fas fa-user-circle" style="font-size:32px;color:#bbb;"></i>`;
        } else {
            avatarTag = msg.partner_avatar != null && msg.partner_avatar?.length > 0
                ? `<img src='${msg.partner_avatar}' class="rounded-circle" style="width:32px;height:32px;">`
                : `<i class="fas fa-user-circle" style="font-size:32px;color:#bbb;"></i>`;
        }
        
        // name tag
        let nameTag = msg.user_name;

        // report icon (not for own messages or emoji-only messages)
         let isEmojiOnly = (!msg.content || msg.content.trim() === "") && msg.emoji;
         let reportTag = msg.user_id != myId && !isEmojiOnly
         ? `<span style='cursor:pointer;color:#d32f2f;font-size:1.0em;' title='Report'
         onclick='openReportModal(${msg.id}, \`${esc(msg.content)}\`, \`${esc(msg.image_path ?? "")}\`)'>
         <i class="fa-solid fa-flag"></i></span>`
         : "";
 
        // translate icon (always shown)
        let translateTag = [
            `<span style='cursor:pointer;color:#1976d2;font-size:1.0em;margin-left:4px;' title='Translate'
            onclick="translateMessage(${msg.id}, '${esc(msg.content)}')">
            <i class="fa-solid fa-language" style="color:#A19E9B;"></i></span>`,
            `<span style='cursor:pointer;color:#28a745;font-size:1.0em;margin-left:4px;cursor:pointer;' title='Add to Vocabulary'
            onclick="addToVocabulary(${msg.id}, '${esc(msg.content)}')">
            <i class='fa fa-plus ' style="color:#ECA133;"></i></span>`
        ].join("");

        // append formatted message to chat box
        box.innerHTML += [
            `<div style='text-align:center;'>${dateTag}</div>`,

            `<div style='text-align:${align};margin:5px 0;padding:5px;
            border-radius:8px;max-width:70%;display:inline-block;float:${align};
            clear:both;position:relative;'>`,

            //name and avatar display
            (msg.user_id != myId
                ? `<div style=\"display:flex;align-items:center;justify-content:flex-start;gap:8px;\" class="mb-1">${avatarTag}<strong class="mx-0">${nameTag}</strong></div>`
                : `<!-- <div style=\"display:flex;align-items:center;justify-content:flex-start;gap:8px;\" class="mb-1">${avatarTag}<strong class="mx-0">${nameTag}</strong></div> -->`),
            
            // image display
            imgTag ? `<div style='margin-top:4px;' class="mb-1">${imgTag}</div>` : "",
            
            // translation display area
            msg.content || emojiTag ? `
            <span id="msg-content-${msg.id}" data-original="${esc(msg.content)}" data-translated="false" style="background:${bgColor};padding:4px 8px 2px 8px;border-radius:6px;display:inline-block;">
            ${esc(msg.content)} ${emojiTag}
            </span>
            <div id="msg-translation-${msg.id}" style="color:#6B6B6B;margin-top:2px;"></div>
            ` : "",
            
            `<div id="msg-meta-${msg.id}" style='margin-top:4px;font-size:0.9em;color:gray;'>${readTag} ${timeTag}</div>`,
            reportTag,
            translateTag,
            `</div><div style='clear:both;'></div>`,
        ].join("");
    }

    // function to fetch and display message list
    function displayMessages(reload=false) {
        // get selected user ID
        const to_user_id = document.getElementById('to_user_id')?.value || document.getElementById('form_to_user_id')?.value;
        if (!to_user_id) return;
        if (reload) previousMessage = null; // * reset previousMessage if reloading all
        if (reload) loadedMessages.clear(); // * clear loadedMessages if reloading all
        const box = document.getElementById('chat-box');
        if (reload) box.innerHTML = ''; // * Clear existing messages if reloading all

        fetchMessages(to_user_id)
            .then(data => {
                const myId = {{ auth()->id() }};
                data.messages.forEach(msg => {
                    if (loadedMessages.has(msg.id)) {
                        // Update read status if already loaded
                        const metaTag = document.getElementById(`msg-meta-${msg.id}`);
                        if (metaTag) {
                            if (msg.is_read) {
                                metaTag.innerHTML = metaTag.innerHTML.replace('(Unread)', '(Read)');
                            } else {
                                metaTag.innerHTML = metaTag.innerHTML.replace('(Read)', '(Unread)');
                            }
                        }
                        return; // * skip adding/modifying already loaded messages
                    }

                    formatMessage(msg, box, myId, previousMessage); // * format and add message to chat box
                    loadedMessages.add(msg.id);
                    previousMessage = msg; // * Update previousMessage for next iteration
                });

                if (reload) box.scrollTop = box.scrollHeight; // auto scroll to bottom
                if (targetMessageId) { const el = document.getElementById(`msg-content-${targetMessageId}`); 
                    if (el) { 
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' }); 
                        el.style.backgroundColor = '#fff3cd'; 
                    } 
                }
            });
    }

    displayMessages();
    setInterval(displayMessages, 2000);

    const form = document.getElementById('chat-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // send message to server
        const fd = new FormData(form);
        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: fd
        })
        .then(res => res.json())
        .then(() => {
            // reset form after sending and fetch latest messages
            form.reset();
            document.getElementById('content').value = '';
            document.getElementById('emoji').value = '';
            document.getElementById('image').value = '';
            fetchMessages();
        });
    });
    

   function openReportModal(messageId, messageContent = '', messageImage = '') {
    document.getElementById('report_message_id').value = messageId;

    //set message content
    document.getElementById('report_message_content').textContent = messageContent;

    // set message image (if any)
    const imageWrapper = document.getElementById('report_message_image_wrapper');
    const imageTag = document.getElementById('report_message_image');
    if (messageImage && messageImage.trim().length > 0) {
        imageTag.src = messageImage;
        imageWrapper.style.display = 'inline-block';
    } else {
        imageTag.src = '';
        imageWrapper.style.display = 'none';
    }

    // reset form fields and steps
    document.getElementById('report_reason').value = '';
    document.getElementById('report_details').value = '';
    document.getElementById('report_file').value = '';
    document.getElementById('report-step-1').style.display = 'block';
    document.getElementById('report-footer-1').style.display = 'flex';
    document.getElementById('report-step-2').style.display = 'none';
    document.getElementById('report-footer-2').style.display = 'none';
    document.getElementById('report-step-3').style.display = 'none';
    document.getElementById('report-footer-3').style.display = 'none';

    const modal = new bootstrap.Modal(document.getElementById('reportModal'));
    modal.show();
}

    // close modal (for Bootstrap)
    function closeReportModal() {
        var modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
        if (modal) modal.hide();
    }

    // report modal step navigation
    document.addEventListener ('DOMContentLoaded', function () {
        const step1 = document.getElementById('report-step-1');
        const step2 = document.getElementById('report-step-2');
        const step3 = document.getElementById('report-step-3');

        const footer1 = document.getElementById('report-footer-1');
        const footer2 = document.getElementById('report-footer-2');
        const footer3 = document.getElementById('report-footer-3');

        document.getElementById('report-next-1').addEventListener('click', () => {
        step1.style.display = 'none';
        footer1.style.display = 'none';
        step2.style.display = 'block';
        footer2.style.display = 'flex';
    });

    document.getElementById('report-back-2').addEventListener('click', () => {
        step2.style.display = 'none';
        footer2.style.display = 'none';
        step1.style.display = 'block';
        footer1.style.display = 'flex';
    });

    document.getElementById('report-submit-2').addEventListener('click', () => {
    const messageId = document.getElementById('report_message_id').value;
    const reasonId = document.getElementById('report_reason').value;
    const details = document.getElementById('report_details').value;
    const file = document.getElementById('report_file').files[0];

    if (!reasonId) {
        alert('Please select a reason for the report.');
        document.getElementById('report_reason').classList.add('is-invalid');
        return;
    } else {
        document.getElementById('report_reason').classList.remove('is-invalid');
    }

    const fd = new FormData();
    fd.append('violation_reason_id', reasonId);
    fd.append('detail', details);
    if (file) {
        fd.append('file', file);
    }

    fetch(`/chat/report/${messageId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: fd,
        credentials: 'same-origin'
    })
        .then(async res => {
        if (!res.ok) {
                const errorData = await res.json();
                console.error('Validation error:', errorData);
                alert('Failed to report.: ' + (errorData.message || 'Unknown error'));
                throw new Error('Validation failed');
            }
            return res.json();
        })
        .then(data => {
            document.getElementById('report-step-2').style.display = 'none';
            document.getElementById('report-footer-2').style.display = 'none';
            document.getElementById('report-step-3').style.display = 'block';
            document.getElementById('report-footer-3').style.display = 'flex';
        })
        .catch(error => {
            console.error('❌ Report error:', error);
            alert('An error occurred while sending the report.');
            });
        });
});

// translation function
function translateMessage(messageId, content) {
    const target = document.querySelector(`#msg-content-${messageId}`);
    const translationDiv = document.querySelector(`#msg-translation-${messageId}`); // translation display area
    if (!target || !translationDiv) return;

    // reverse translation
    if (target.dataset.translated === "true") {
        translationDiv.textContent = ""; 
        target.dataset.translated = "false";
        return;
    }

    // call API
    fetch('/translate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ text: content })
    })
    .then(async res => {
        if (!res.ok) {
            const text = await res.text();
            throw new Error(`Server error ${res.status}: ${text}`);
        }
        return res.json();
    })
    .then(data => {
        if (data.translated) {
            translationDiv.textContent = data.translated; //display translated text
            target.dataset.translated = "true";
        } else {
            throw new Error('No translated text in response');
        }
    })
    .catch(err => {
        console.error('Translation error:', err);
        alert('Failed to translate.');
    });
}

function addToVocabulary(msgId, content) {
    // 翻訳内容を取得
    let translation = '';
    const translationDiv = document.getElementById(`msg-translation-${msgId}`);

    if (translationDiv && translationDiv.textContent) {
        translation = translationDiv.textContent;
    }

    if (translation && translation.trim() !== '') {
        // 翻訳済みなら即modal
        document.dispatchEvent(new CustomEvent('openVocabularyModal', { detail: { front: content, back: translation } }));
    } else {
        // 未翻訳ならAPI呼び出し→modal
        fetch('/translate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ text: content })
        })
        .then(async res => {
            if (!res.ok) {
                const text = await res.text();
                throw new Error(`Server error ${res.status}: ${text}`);
            }
            return res.json();
        })
        .then(data => {
            const translated = data.translated || '';
            document.dispatchEvent(new CustomEvent('openVocabularyModal', { detail: { front: content, back: translated } }));
        })
        .catch(err => {
            console.error('Error adding vocabulary:', err);
            alert('翻訳に失敗しました');
        });
    }
}

function esc(str) {
  return str.replace(/[&<>"']/g, function(match) {
    switch (match) {
      case '&': return '&amp;';
      case '<': return '&lt;';
      case '>': return '&gt;';
      case '"': return '&quot;';
      case "'": return '&#39;'; // または &apos;
      default: return match;
    }
  });
}
</script>
@endsection