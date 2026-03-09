<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alagang Baras - Livestock Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #115d33;
            --dark-green: #094a25;
            --light-gray: #f8f9fa;
            --border-gray: #dee2e6;
            --color-white: #ffffff;
            --color-black: #000000;
            --color-primary: #28a745;
            --color-primary-alt: #20c997;
            --color-primary-hover: #218838;
            --color-primary-hover-alt: #1abc9c;
            --color-secondary: #fd7e14;
            --color-secondary-alt: #ffc107;
            --color-tertiary: #6f42c1;
            --color-dark: #2c3e50;
            --color-muted: #6c757d;
            --color-gray-light: #e9ecef;
            --color-gray-dark: #495057;
            --shadow-primary: 0 4px 15px rgba(40, 167, 69, 0.3);
            --shadow-card: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-gray);
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary-green) 0%, var(--dark-green) 100%);
            min-height: 100vh;
            color: var(--color-white);
            position: fixed;
            width: 280px;
            padding: 20px 0;
            box-shadow: var(--shadow-card);
        }

        .sidebar-header {
            padding: 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 0;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-menu li a {
            display: block;
            padding: 15px 30px;
            color: var(--color-white);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 1rem;
        }

        .nav-menu li a:hover,
        .nav-menu li a.active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--color-primary);
            padding-left: 26px;
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
        }

        .page-header {
            background: var(--color-white);
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            font-size: 1.8rem;
            color: var(--color-dark);
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 0;
        }

        .btn-primary-custom {
            background: var(--color-primary);
            color: var(--color-white);
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: var(--shadow-primary);
        }

        .btn-primary-custom:hover {
            background: var(--color-primary-hover);
            transform: translateY(-2px);
        }

        .form-container {
            background: var(--color-white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 500;
            color: var(--color-dark);
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid var(--border-gray);
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .search-box {
            background: var(--color-white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .table-container {
            background: var(--color-white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background: var(--primary-green);
            color: var(--color-white);
        }

        .table thead th {
            border: none;
            padding: 15px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--light-gray);
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
            margin: 0 3px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-edit {
            background: #007bff;
            color: var(--color-white);
        }

        .btn-edit:hover {
            background: #0056b3;
        }

        .btn-delete {
            background: #dc3545;
            color: var(--color-white);
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-download {
            background: var(--color-primary);
            color: var(--color-white);
        }

        .btn-download:hover {
            background: var(--color-primary-hover);
        }

        .qr-code-cell {
            text-align: center;
        }

        .qr-code-cell canvas {
            border: 2px solid var(--border-gray);
            border-radius: 5px;
            padding: 5px;
        }

        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background: var(--primary-green);
            color: var(--color-white);
            border-radius: 15px 15px 0 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }

            .main-content {
                margin-left: 0;
            }

            .page-header {
                flex-direction: column;
                gap: 15px;
            }

            .table-container {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-container">
      <div class="logo">
        <img src="../../assets/images/64c57e19-d2a4-42cf-9a5b-4b35dd14f9f7.png" alt="">
      </div>
      <div class="logo secondary">
        <img src="../../assets/images/9302dfc3-307b-42b1-a9d2-8c31dc0cb9c6.png" alt="">
      </div>
      <div class="logo tertiary">
        <img src="../../assets/images/c567f4c0-7403-4ca0-8556-5bac257c9190.png" alt="">
      </div>
    </div>
        <div class="logo-text">
            <h2>Alagang Baras</h2>
            <p>COORDINATOR PAGE</p>
        </div>
        <ul class="nav-menu">
            <li><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-user"></i> Owner Management</a></li>
            <li><a href="#"><i class="fas fa-heartbeat"></i> Health Monitoring</a></li>
            <li><a href="#" class="active"><i class="fas fa-clipboard-list"></i> Livestock Profiling</a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i> Reports</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Log out</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-clipboard-list"></i> Livestock List</h1>
            <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addLivestockModal">
                <i class="fas fa-plus"></i> Add Livestock
            </button>
        </div>

        <div class="search-box">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search by owner name, species, breed...">
                </div>
            </div>
        </div>

        <div class="table-container">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>OWNER NAME</th>
                        <th>SPECIES</th>
                        <th>BREED</th>
                        <th>AGE</th>
                        <th>SEX</th>
                        <th>QR CODE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="livestockTableBody">
                    <!-- Data will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Livestock Modal -->
    <div class="modal fade" id="addLivestockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Add Livestock</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="livestockForm">
                        <div class="mb-3">
                            <label class="form-label">Owner Name:</label>
                            <input type="text" class="form-control" id="ownerName" placeholder="Search owner..." required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Species:</label>
                                <select class="form-select" id="species" required>
                                    <option value="">-- Please Select --</option>
                                    <option value="Cattle">Cattle</option>
                                    <option value="Pig">Pig</option>
                                    <option value="Goat">Goat</option>
                                    <option value="Chicken">Chicken</option>
                                    <option value="Duck">Duck</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Breed:</label>
                                <input type="text" class="form-control" id="breed" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sex:</label>
                                <select class="form-select" id="sex" required>
                                    <option value="">-- Please Select --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth:</label>
                                <input type="date" class="form-control" id="dob" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-primary-custom" onclick="addLivestock()">Add Livestock</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Livestock Modal -->
    <div class="modal fade" id="editLivestockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Livestock</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editLivestockForm">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label class="form-label">Owner Name:</label>
                            <input type="text" class="form-control" id="editOwnerName" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Species:</label>
                                <select class="form-select" id="editSpecies" required>
                                    <option value="">-- Please Select --</option>
                                    <option value="Cattle">Cattle</option>
                                    <option value="Pig">Pig</option>
                                    <option value="Goat">Goat</option>
                                    <option value="Chicken">Chicken</option>
                                    <option value="Duck">Duck</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Breed:</label>
                                <input type="text" class="form-control" id="editBreed" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sex:</label>
                                <select class="form-select" id="editSex" required>
                                    <option value="">-- Please Select --</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth:</label>
                                <input type="date" class="form-control" id="editDob" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-primary-custom" onclick="updateLivestock()">Update Livestock</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        let livestockData = [];
        let editingId = null;

        function calculateAge(dob) {
            const birthDate = new Date(dob);
            const today = new Date();
            let years = today.getFullYear() - birthDate.getFullYear();
            let months = today.getMonth() - birthDate.getMonth();
            
            if (months < 0) {
                years--;
                months += 12;
            }
            
            if (years > 0) {
                return `${years}y ${months}m`;
            } else {
                return `${months}m`;
            }
        }

        function generateQRCode(data, containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            new QRCode(container, {
                text: data,
                width: 80,
                height: 80
            });
        }

        function addLivestock() {
            const form = document.getElementById('livestockForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const livestock = {
                id: Date.now(),
                ownerName: document.getElementById('ownerName').value,
                species: document.getElementById('species').value,
                breed: document.getElementById('breed').value,
                sex: document.getElementById('sex').value,
                dob: document.getElementById('dob').value
            };

            livestockData.push(livestock);
            renderTable();
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('addLivestockModal')).hide();
        }

        function editLivestock(id) {
            const livestock = livestockData.find(l => l.id === id);
            if (!livestock) return;

            editingId = id;
            document.getElementById('editId').value = id;
            document.getElementById('editOwnerName').value = livestock.ownerName;
            document.getElementById('editSpecies').value = livestock.species;
            document.getElementById('editBreed').value = livestock.breed;
            document.getElementById('editSex').value = livestock.sex;
            document.getElementById('editDob').value = livestock.dob;

            new bootstrap.Modal(document.getElementById('editLivestockModal')).show();
        }

        function updateLivestock() {
            const form = document.getElementById('editLivestockForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const id = parseInt(document.getElementById('editId').value);
            const index = livestockData.findIndex(l => l.id === id);
            
            if (index !== -1) {
                livestockData[index] = {
                    id: id,
                    ownerName: document.getElementById('editOwnerName').value,
                    species: document.getElementById('editSpecies').value,
                    breed: document.getElementById('editBreed').value,
                    sex: document.getElementById('editSex').value,
                    dob: document.getElementById('editDob').value
                };
                
                renderTable();
                bootstrap.Modal.getInstance(document.getElementById('editLivestockModal')).hide();
            }
        }

        function deleteLivestock(id) {
            if (confirm('Are you sure you want to delete this livestock record?')) {
                livestockData = livestockData.filter(l => l.id !== id);
                renderTable();
            }
        }

        function downloadQRCode(id) {
            const canvas = document.querySelector(`#qr-${id} canvas`);
            if (canvas) {
                const url = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                link.download = `livestock-${id}-qr.png`;
                link.href = url;
                link.click();
            }
        }

        function renderTable() {
            const tbody = document.getElementById('livestockTableBody');
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            const filteredData = livestockData.filter(livestock => 
                livestock.ownerName.toLowerCase().includes(searchTerm) ||
                livestock.species.toLowerCase().includes(searchTerm) ||
                livestock.breed.toLowerCase().includes(searchTerm) ||
                livestock.sex.toLowerCase().includes(searchTerm)
            );

            tbody.innerHTML = '';

            filteredData.forEach(livestock => {
                const age = calculateAge(livestock.dob);
                const qrData = JSON.stringify({
                    id: livestock.id,
                    owner: livestock.ownerName,
                    species: livestock.species,
                    breed: livestock.breed,
                    sex: livestock.sex,
                    dob: livestock.dob
                });

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${livestock.ownerName}</td>
                    <td>${livestock.species}</td>
                    <td>${livestock.breed}</td>
                    <td>${age}</td>
                    <td>${livestock.sex}</td>
                    <td class="qr-code-cell">
                        <div id="qr-${livestock.id}"></div>
                    </td>
                    <td>
                        <button class="btn-action btn-edit" onclick="editLivestock(${livestock.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-action btn-download" onclick="downloadQRCode(${livestock.id})" title="Download QR">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteLivestock(${livestock.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);

                setTimeout(() => generateQRCode(qrData, `qr-${livestock.id}`), 0);
            });

            if (filteredData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4">No livestock records found</td></tr>';
            }
        }

        document.getElementById('searchInput').addEventListener('input', renderTable);

        renderTable();
    </script>
</body>
</html>