// JS for Verify Modal logic

document.addEventListener('DOMContentLoaded', function() {
    function attachVerifyBtnListeners() {
        document.querySelectorAll('.verify-btn').forEach(btn => {
            if (!btn.classList.contains('verify-listener')) {
                btn.classList.add('verify-listener');
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const requestId = btn.getAttribute('data-request-id');
                    const studentId = btn.getAttribute('data-student-id');
                    const studentName = btn.getAttribute('data-student-name');
                    openVerifyModal({ studentId, studentName, requestId });
                });
            }
        });
    }
    attachVerifyBtnListeners();
    // If your table is updated via AJAX/pagination, re-call attachVerifyBtnListeners()
    document.addEventListener('ajaxTableUpdated', attachVerifyBtnListeners);
});

function openVerifyModal({ studentId, studentName, requestId }) {
    // Show loading modal
    showVerifyModal('loading', { studentId, studentName });
    // AJAX to backend to check student and docs
    fetch(`/registrar/verify-modal/${requestId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.found) {
            showVerifyModal('found', data);
        } else {
            showVerifyModal('notfound', data);
        }
    })
    .catch(() => showVerifyModal('error'));
}

function showVerifyModal(state, data = {}) {
    let modal = document.getElementById('verifyModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'verifyModal';
        modal.className = 'import-modal';
        document.body.appendChild(modal);
    }
    modal.style.display = 'flex';
    // Always set the modal inner HTML structure
    modal.innerHTML = `<div class='import-modal-content' style='max-width:500px;'>
        <div class='import-modal-header'><h3 class='import-modal-title'>Verify Request</h3>
        <button class='import-modal-close' onclick='closeVerifyModal()'>&times;</button></div>
        <div class='import-modal-body' id='verifyModalBody'></div>
    </div>`;
    const body = modal.querySelector('#verifyModalBody');
    if (state === 'loading') {
        body.innerHTML = `<div style='text-align:center;padding:2rem;'>Checking student record...</div>`;
    } else if (state === 'found') {
        // Build eligibility status display
        let eligibilityHtml = '';
        if (data.eligibility) {
            const eligibility = data.eligibility;
            if (eligibility.eligible) {
                eligibilityHtml = `<div style='background:#d4edda;border:1px solid #c3e6cb;border-radius:6px;padding:12px;margin-bottom:1rem;color:#155724;'>
                    <i class='fas fa-check-circle'></i> <strong>Eligibility Check Passed</strong><br>
                    <small>${eligibility.message}</small>
                </div>`;
            } else {
                eligibilityHtml = `<div style='background:#f8d7da;border:1px solid #f5c6cb;border-radius:6px;padding:12px;margin-bottom:1rem;color:#721c24;'>
                    <i class='fas fa-exclamation-triangle'></i> <strong>Eligibility Check Failed</strong><br>
                    <small>${eligibility.message}</small>
                    ${eligibility.invalid_documents && eligibility.invalid_documents.length > 0 ? 
                        `<br><small><strong>Invalid Documents:</strong> ${eligibility.invalid_documents.join(', ')}</small>` : ''}
                </div>`;
            }
        }
        
        // Build student info display
        const studentInfoHtml = `
            <div style='background:#f8f9fa;border-radius:6px;padding:12px;margin-bottom:1rem;'>
                <div style='margin-bottom:0.5rem;'><b>Student:</b> ${data.full_name}</div>
                <div style='margin-bottom:0.5rem;'><b>Year Level:</b> ${data.year_level || 'N/A'}</div>
                <div style='margin-bottom:0.5rem;'><b>Status:</b> ${data.status || 'N/A'}</div>
                <div><b>Currently Enrolled:</b> ${data.is_enrolled ? 'Yes' : 'No'}</div>
            </div>
        `;
        
        // Build documents list
        const documentsHtml = `<div style='margin-bottom:1rem;'><b>Requested Documents:</b><ul style='margin-top:0.5rem;padding-left:20px;'>${data.documents.map(doc => `<li>${doc}</li>`).join('')}</ul></div>`;
        
        // Build form with conditional approval button
        const canApprove = data.eligibility && data.eligibility.eligible;
        const approveButtonHtml = canApprove 
            ? `<button type='submit' class='action-btn approve-btn' style='flex:1;background:#4CAF50;'>Approve</button>`
            : `<button type='button' class='action-btn' style='flex:1;background:#ccc;cursor:not-allowed;' disabled>Cannot Approve (Eligibility Failed)</button>`;
        
        body.innerHTML = studentInfoHtml + eligibilityHtml + documentsHtml + `
            <form method='POST' action='/registrar/approve/${data.request_id}' id='approveForm'>
                <input type='hidden' name='_token' value='${window.csrfToken || document.querySelector('meta[name="csrf-token"]').content}'>
                <div style='display:flex;flex-direction:column;gap:.35rem;margin-top:.5rem;'>
                    <label for='registrarNotes' style='font-weight:600;color:#8B0000;'>Description / Notes for Requester</label>
                    <small style='color:#555;'>Anything you type here will be sent to the requester in their email notification.</small>
                    <textarea id='registrarNotes' name='registrar_notes' placeholder='Provide additional context or instructions (optional)' style='width:100%;min-height:90px;border:1px solid #ccc;border-radius:6px;padding:10px;resize:vertical;'></textarea>
                </div>
                <div style='display:flex;gap:.5rem;margin-top:1rem;'>
                  ${approveButtonHtml}
                  <button type='button' class='action-btn reject-btn' style='flex:1;' onclick='switchToReject(${data.request_id})'>Reject</button>
                </div>
            </form>`;
        
        // Prevent form submission if eligibility check failed
        if (!canApprove) {
            const form = body.querySelector('#approveForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Cannot approve this request. ' + (data.eligibility ? data.eligibility.message : 'Eligibility check failed.'));
                });
            }
        }
    } else if (state === 'notfound') {
        body.innerHTML = `<div style='color:#8B0000;margin-bottom:1rem;'>This request cannot be approved because no matching student record was found in the database.</div>
            <form method='POST' action='/registrar/reject/${data.request_id}'>
                <input type='hidden' name='_token' value='${window.csrfToken || document.querySelector('meta[name="csrf-token"]').content}'>
                <textarea name='registrar_notes' placeholder='Rejection reason (optional)' style='width:100%;min-height:80px;margin-top:.5rem;'></textarea>
                <button type='submit' class='action-btn reject-btn' style='width:100%;margin-top:1rem;'>Reject</button>
            </form>`;
    } else {
        body.innerHTML = `<div style='color:#8B0000;'>Error loading student data.</div>`;
    }
}

function closeVerifyModal() {
    const modal = document.getElementById('verifyModal');
    if (modal) modal.style.display = 'none';
}

// Helper to switch to rejection UI from the "found" state
function switchToReject(requestId) {
    const modal = document.getElementById('verifyModal');
    if (!modal) return;
    const body = modal.querySelector('#verifyModalBody');
    if (!body) return;
    // Preserve any notes already typed in the approve view
    const existingNotesField = document.querySelector('#approveForm textarea[name="registrar_notes"]');
    const existingNotes = existingNotesField ? existingNotesField.value : '';
    body.innerHTML = `<div style='color:#8B0000;margin-bottom:1rem;'>Please provide a rejection reason (optional) then confirm.</div>
        <form method='POST' action='/registrar/reject/${requestId}'>
            <input type='hidden' name='_token' value='${window.csrfToken || document.querySelector('meta[name="csrf-token"]').content}'>
            <div style='display:flex;flex-direction:column;gap:.35rem;margin-top:.5rem;'>
                <label for='rejectionNotes' style='font-weight:600;color:#8B0000;'>Description / Rejection Reason</label>
                <small style='color:#555;'>This message appears in the rejection email sent to the requester.</small>
                <textarea id='rejectionNotes' name='registrar_notes' placeholder='Explain why the request is being rejected' required style='width:100%;min-height:100px;border:1px solid #ccc;border-radius:6px;padding:10px;resize:vertical;'>${existingNotes || ''}</textarea>
            </div>
            <div style='display:flex;gap:.5rem;margin-top:1rem;'>
              <button type='submit' class='action-btn reject-btn' style='flex:1;'>Confirm Reject</button>
              <button type='button' class='action-btn approve-btn' style='flex:1;' onclick='closeVerifyModal()'>Cancel</button>
            </div>
        </form>`;
    const rejectionField = document.getElementById('rejectionNotes');
    if (rejectionField) {
        rejectionField.focus();
    }
}
