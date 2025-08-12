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
        body.innerHTML = `<div style='margin-bottom:1rem;'><b>Student:</b> ${data.full_name}</div>
            <div style='margin-bottom:1rem;'><b>Available Documents:</b><ul>${data.documents.map(doc => `<li>${doc}</li>`).join('')}</ul></div>
            <form method='POST' action='/registrar/approve/${data.request_id}'>
                <input type='hidden' name='_token' value='${window.csrfToken || document.querySelector('meta[name="csrf-token"]').content}'>
                <button type='submit' class='action-btn approve-btn' style='width:100%;margin-top:1rem;'>Approve</button>
            </form>`;
    } else if (state === 'notfound') {
        body.innerHTML = `<div style='color:#8B0000;margin-bottom:1rem;'>No matching student record or documents found.</div>
            <form method='POST' action='/registrar/reject/${data.request_id}'>
                <input type='hidden' name='_token' value='${window.csrfToken || document.querySelector('meta[name="csrf-token"]').content}'>
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
