        // Modal helpers (client side)
        function openUploadModal(subject, module = '') {
        // If user is not logged in we still allow upload (guest can upload)
        showModalUpload(subject, module);
        }

        function openPaymentModal(subject, module = '') {
        showModalPayment(subject, module);
        }

        function closeModal() {
        document.getElementById('modalRoot').style.display = 'none';
        }

        function showModalUpload(subject, module) {
        const root = document.getElementById('modalRoot');
        const content = document.getElementById('modalContent');
        content.innerHTML = `
        <h3 style="margin-bottom:8px">Upload Payment Slip — ${subject.toUpperCase()}</h3>
        <div style="font-size:0.95rem;color:#475569;margin-bottom:8px">Choose the module and select payment slip image/PDF.</div>
        <div style="display:grid;gap:8px">
            <label style="font-weight:600">module</label>
            <select id="modalUploadModule" style="padding:8px;border:1px solid #e6eef8;border-radius:8px">
                <option value="">Select module...</option>
                <option value="Paper_Discussion">Paper Discussion</option>
                <option value="Introduction_to_ICT">Introduction to ICT</option>
                <option value="march">March</option>
                <option value="april">April</option>
                <option value="may">May</option>
                <option value="june">June</option>
                <option value="july">July</option>
                <option value="august">August</option>
                <option value="september">September</option>
                <option value="october">October</option>
                <option value="november">November</option>
                <option value="december">December</option>
            </select>
            <label style="font-weight:600">Payment Slip (image or PDF)</label>
            <input id="modalUploadFile" type="file" accept="image/*,.pdf" />
        </div>
        `;
        const primary = document.getElementById('modalPrimaryBtn');
        primary.onclick = function() {
        const m = document.getElementById('modalUploadModule').value;
        const f = document.getElementById('modalUploadFile').files[0];
        if (!m) {
        alert('Select a module');
        return;
        }
        if (!f) {
        alert('Choose a file');
        return;
        }
        // submit via hidden form to server
        const uploadForm = document.getElementById('uploadFormPC');
        document.getElementById('uploadSubject').value = subject;
        document.getElementById('uploadModule').value = m;
        document.getElementById('uploadSlipFile').files = document.getElementById('modalUploadFile').files;
        uploadForm.submit();
        };
        root.style.display = 'flex';
        }

        function showModalPayment(subject, module) {
        const root = document.getElementById('modalRoot');
        const content = document.getElementById('modalContent');
        content.innerHTML = `
        <h3 style="margin-bottom:8px">Online Payment — ${subject.toUpperCase()}</h3>
        <div style="font-size:0.95rem;color:#475569;margin-bottom:8px">This demo simulates an online payment (no real charge).</div>
        <div style="display:grid;gap:8px">
            <label style="font-weight:600">Module</label>
            <select id="modalPayModule" style="padding:8px;border:1px solid #e6eef8;border-radius:8px">
                <option value="">Select module...</option>
                <option value="Paper_Discussion">Paper Discussion</option>
                <option value="february">February</option>
                <option value="march">March</option>
                <option value="april">April</option>
                <option value="may">May</option>
                <option value="june">June</option>
                <option value="july">July</option>
                <option value="august">August</option>
                <option value="september">September</option>
                <option value="october">October</option>
                <option value="november">November</option>
                <option value="december">December</option>
            </select>
            <label style="font-weight:600">Cardholder Name</label>
            <input id="modalCardName" placeholder="Full name on card" style="padding:8px;border:1px solid #e6eef8;border-radius:8px" />
            <label style="font-weight:600">Card Number (demo)</label>
            <input id="modalCardNumber" placeholder="1234 5678 9012 3456" style="padding:8px;border:1px solid #e6eef8;border-radius:8px" />
        </div>
        `;
        const primary = document.getElementById('modalPrimaryBtn');
        primary.onclick = function() {
        const m = document.getElementById('modalPayModule').value;
        const n = document.getElementById('modalCardName').value;
        const c = document.getElementById('modalCardNumber').value;
        if (!m || !n || !c) {
        alert('Please complete payment fields');
        return;
        }
        const payForm = document.getElementById('payFormPC');
        document.getElementById('paySubject').value = subject;
        document.getElementById('payModule').value = m;
        document.getElementById('payCardName').value = n;
        document.getElementById('payCardNumber').value = c;
        payForm.submit();
        };
        root.style.display = 'flex';
        }

        // Close modal when clicking the overlay
        document.getElementById('modalRoot').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
        });

        // small enhancement: expand first unlocked module for logged in user
        window.addEventListener('load', function() {
        const firstUnlocked = document.querySelector('.module-bar.unlocked');
        if (firstUnlocked) firstUnlocked.classList.add('expanded');
        // stop pointer events on locked module headers? (we still want alert)
        });