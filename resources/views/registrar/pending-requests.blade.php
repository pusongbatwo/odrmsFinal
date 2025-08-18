<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pending Requests - Registrar Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --dark-red: #8B0000;
            --gold: #FFD700;
            --white: #FFFFFF;
            --light-gray: #F5F5F5;
            --dark-gray: #333333;
            --success-green: #28a745;
            --warning-orange: #fd7e14;
            --danger-red: #dc3545;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: var(--light-gray);
            color: var(--dark-gray);
        }
        
        .header {
            background: var(--dark-red);
            color: var(--white);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header h1 {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--dark-red);
        }
        
        .stat-label {
            color: var(--dark-gray);
            margin-top: 0.5rem;
        }
        
        .requests-section {
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .section-header {
            background: var(--dark-red);
            color: var(--white);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .request-card {
            border: 1px solid #eee;
            border-radius: 8px;
            margin: 1rem;
            padding: 1.5rem;
            transition: var(--transition);
        }
        
        .request-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .request-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--dark-red);
            font-size: 0.9rem;
        }
        
        .info-value {
            color: var(--dark-gray);
            margin-top: 0.25rem;
        }
        
        .documents-list {
            background: var(--light-gray);
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        
        .document-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #ddd;
        }
        
        .document-item:last-child {
            border-bottom: none;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-approve {
            background: var(--success-green);
            color: var(--white);
        }
        
        .btn-approve:hover {
            background: #218838;
        }
        
        .btn-reject {
            background: var(--danger-red);
            color: var(--white);
        }
        
        .btn-reject:hover {
            background: #c82333;
        }
        
        .btn-view {
            background: var(--warning-orange);
            color: var(--white);
        }
        
        .btn-view:hover {
            background: #e68900;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        
        .modal-content {
            background: var(--white);
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            margin: 10% auto;
            padding: 2rem;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--dark-gray);
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
            min-height: 100px;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--dark-gray);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .request-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                width: 100%;
            }
            
            .btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>
            <i class="fas fa-clipboard-check"></i>
            Registrar Dashboard - Pending Requests
        </h1>
    </div>
    
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" id="pendingCount">0</div>
                <div class="stat-label">Pending Approval</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="approvedCount">0</div>
                <div class="stat-label">Approved Today</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="rejectedCount">0</div>
                <div class="stat-label">Rejected Today</div>
            </div>
        </div>
        
        <div class="requests-section">
            <div class="section-header">
                <h2><i class="fas fa-clock"></i> Pending Approval Requests</h2>
                <button class="btn btn-view" onclick="refreshRequests()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
            
            <div id="requestsContainer">
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No Pending Requests</h3>
                    <p>All requests have been processed or there are no new submissions.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Approval Modal -->
    <div id="approvalModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-check-circle"></i> Approve Request</h3>
                <button class="close" onclick="closeModal('approvalModal')">&times;</button>
            </div>
            <form id="approvalForm">
                <input type="hidden" id="approvalRequestId">
                <div class="form-group">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea class="form-textarea" id="approvalNotes" placeholder="Add any notes for the approval..."></textarea>
                </div>
                <div class="action-buttons">
                    <button type="button" class="btn btn-view" onclick="closeModal('approvalModal')">Cancel</button>
                    <button type="submit" class="btn btn-approve">
                        <i class="fas fa-check"></i> Approve Request
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Rejection Modal -->
    <div id="rejectionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-times-circle"></i> Reject Request</h3>
                <button class="close" onclick="closeModal('rejectionModal')">&times;</button>
            </div>
            <form id="rejectionForm">
                <input type="hidden" id="rejectionRequestId">
                <div class="form-group">
                    <label class="form-label">Rejection Reason *</label>
                    <textarea class="form-textarea" id="rejectionReason" placeholder="Please provide a detailed reason for rejection..." required></textarea>
                </div>
                <div class="action-buttons">
                    <button type="button" class="btn btn-view" onclick="closeModal('rejectionModal')">Cancel</button>
                    <button type="submit" class="btn btn-reject">
                        <i class="fas fa-times"></i> Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Load requests on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPendingRequests();
        });
        
        function loadPendingRequests() {
            fetch('/registrar/pending-requests')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayRequests(data.requests);
                        updateStats(data.requests, data.stats);
                    } else {
                        console.error('Failed to load requests:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading requests:', error);
                });
        }
        
        function displayRequests(requests) {
            const container = document.getElementById('requestsContainer');
            
            if (requests.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>No Pending Requests</h3>
                        <p>All requests have been processed or there are no new submissions.</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = requests.map(request => `
                <div class="request-card">
                    <div class="request-header">
                        <div>
                            <h3>${request.first_name} ${request.middle_name || ''} ${request.last_name}</h3>
                            <p style="color: #666; margin-top: 0.25rem;">${request.course}</p>
                        </div>
                        <div class="action-buttons">
                            <button class="btn btn-view" onclick="viewRequest(${request.id})">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-approve" onclick="openApprovalModal(${request.id})">
                                <i class="fas fa-check"></i> Approve
                            </button>
                            <button class="btn btn-reject" onclick="openRejectionModal(${request.id})">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    </div>
                    
                    <div class="request-info">
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">${request.email}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Mobile</span>
                            <span class="info-value">${request.mobile_number}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Purpose</span>
                            <span class="info-value">${request.purpose}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Request Date</span>
                            <span class="info-value">${new Date(request.request_date).toLocaleDateString()}</span>
                        </div>
                    </div>
                    
                    <div class="documents-list">
                        <h4>Requested Documents:</h4>
                        ${request.requested_documents.map(doc => `
                            <div class="document-item">
                                <span>${doc.document_type}</span>
                                <span>Quantity: ${doc.quantity}</span>
                            </div>
                        `).join('')}
                    </div>
                    
                    ${request.special_instructions ? `
                        <div class="info-item">
                            <span class="info-label">Special Instructions</span>
                            <span class="info-value">${request.special_instructions}</span>
                        </div>
                    ` : ''}
                </div>
            `).join('');
        }
        
        function updateStats(requests, stats = null) {
            if (stats) {
                document.getElementById('pendingCount').textContent = stats.pending || requests.length;
                document.getElementById('approvedCount').textContent = stats.approved_today || 0;
                document.getElementById('rejectedCount').textContent = stats.rejected_today || 0;
            } else {
                document.getElementById('pendingCount').textContent = requests.length;
                // Load additional stats if not provided
                loadDashboardStats();
            }
        }

        function loadDashboardStats() {
            fetch('/registrar/dashboard-stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('pendingCount').textContent = data.stats.pending;
                        document.getElementById('approvedCount').textContent = data.stats.approved_today;
                        document.getElementById('rejectedCount').textContent = data.stats.rejected_today;
                    }
                })
                .catch(error => {
                    console.error('Error loading dashboard stats:', error);
                });
        }
        
        function openApprovalModal(requestId) {
            document.getElementById('approvalRequestId').value = requestId;
            document.getElementById('approvalModal').style.display = 'block';
        }
        
        function openRejectionModal(requestId) {
            document.getElementById('rejectionRequestId').value = requestId;
            document.getElementById('rejectionModal').style.display = 'block';
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.getElementById('approvalNotes').value = '';
            document.getElementById('rejectionReason').value = '';
        }
        
        // Handle approval form submission
        document.getElementById('approvalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const requestId = document.getElementById('approvalRequestId').value;
            const notes = document.getElementById('approvalNotes').value;
            
            fetch('/registrar/approve-request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    request_id: requestId,
                    registrar_notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    closeModal('approvalModal');
                    loadPendingRequests();
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while approving the request.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
        
        // Handle rejection form submission
        document.getElementById('rejectionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const requestId = document.getElementById('rejectionRequestId').value;
            const reason = document.getElementById('rejectionReason').value;
            
            if (!reason.trim()) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please provide a rejection reason.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            fetch('/registrar/reject-request', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    request_id: requestId,
                    registrar_notes: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    closeModal('rejectionModal');
                    loadPendingRequests();
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while rejecting the request.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
        
        function refreshRequests() {
            loadPendingRequests();
        }
        
        function viewRequest(requestId) {
            // You can implement a detailed view modal here
            Swal.fire({
                title: 'Request Details',
                text: 'Detailed view functionality can be implemented here.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
