<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Center Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-left: 0;
            /* Remove fixed margin */
            padding-top: 56px;
            /* Account for fixed navbar */
        }

        .sidebar {
            position: fixed;
            top: 56px;
            /* Below navbar */
            left: 0;
            height: calc(100% - 56px);
            width: 250px;
            z-index: 1000;
            background-color: #343a40;
            padding-top: 20px;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
        }

        .main-content {
            margin-left: 250px;
            /* Account for sidebar */
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .collapsed-sidebar .main-content {
            margin-left: 0;
        }

        .header {
            background: linear-gradient(135deg, #ff4040 0%, #470000 100%);
            color: white;
            padding: 25px 0;
            margin-bottom: 30px;
        }

        .progress-container {
            margin: 30px 0;
            padding: 0 15px;
        }

        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .section-title {
            color: #ff4040;
            border-bottom: 2px solid #ff4040;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff4040 0%, #470000 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #e03a3a 0%, #3a0000 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .progress-bar {
            background: linear-gradient(135deg, #ff4040 0%, #470000 100%);
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: Center;
            justify-content: Center;
            margin: 0 auto 10px;
            font-weight: bold;
            color: #6c757d;
        }

        .step-active .step-circle {
            background: linear-gradient(135deg, #ff4040 0%, #470000 100%);
            color: white;
        }

        .step-label {
            text-align: Center;
            font-size: 14px;
            color: #6c757d;
        }

        .step-active .step-label {
            color: #ff4040;
            font-weight: 600;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .conditional-field {
            display: none;
        }

        .add-more-btn {
            margin-top: 10px;
        }

        .staff-table,
        .facility-table,
        .batch-table {
            margin-top: 20px;
        }

        .facility-card,
        .batch-card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
        }

        .toggle-sidebar {
            cursor: pointer;
        }

        .modal-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6c757d;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .main-content {
                margin-left: 0;
            }

            .show-sidebar .sidebar {
                width: 250px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php $this->load->view('superadmin/Include/Sidebar') ?>
    <!-- Navbar -->
    <?php $this->load->view('superadmin/Include/Navbar') ?>
    <div class="content-wrapper" id="contentWrapper">


        <!-- Main Content -->
        <div class="main-content">
            <div class="header text-Center">
                <p class="lead">Add and manage your sports Center details</p>
            </div>

            <div class="container-fluid">
                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <div class="step step-active">
                            <div class="step-circle">1</div>
                            <div class="step-label">Center Details</div>
                        </div>
                        <div class="step">
                            <div class="step-circle">2</div>
                            <div class="step-label">Batch Details</div>
                        </div>
                        <div class="step">
                            <div class="step-circle">3</div>
                            <div class="step-label">Staff Details</div>
                        </div>
                        <div class="step">
                            <div class="step-circle">4</div>
                            <div class="step-label">Facility Details</div>
                        </div>
                    </div>
                </div>

                <!-- Center Details Form -->
<div class="form-container form-section active" id="center-details">
    <h3 class="section-title"><i class="fas fa-info-circle me-2"></i>Center Details</h3>
    <form id="centerForm" novalidate>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="centerName" class="form-label required-field">Center Name</label>
                <input type="text" class="form-control" id="centerName" required>
                <div class="invalid-feedback">Center name is required.</div>
            </div>
            <div class="col-md-6">
                <label for="centerNumber" class="form-label required-field">Center Number</label>
                <input type="text" class="form-control" id="centerNumber" readonly required>
                <div class="invalid-feedback">Center number is required.</div>
            </div>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label required-field">Address</label>
            <textarea class="form-control" id="address" rows="3" required></textarea>
            <div class="invalid-feedback">Address is required.</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="openingTime" class="form-label required-field">Opening Time</label>
                <input type="time" class="form-control" id="openingTime" required>
                <div class="invalid-feedback">Opening time is required.</div>
            </div>
            <div class="col-md-6">
                <label for="closingTime" class="form-label required-field">Closing Time</label>
                <input type="time" class="form-control" id="closingTime" required>
                <div class="invalid-feedback">Closing time must be after opening time.</div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="printPaidDate" class="form-label">Paid Date</label>
                <input type="date" class="form-control" id="printPaidDate">
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label required-field">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" required minlength="8">
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                    <div class="invalid-feedback">Password must be at least 8 characters.</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="center_rent" class="form-label required-field">Rent</label>
            <input
                type="number"
                id="center_rent"
                name="center_rent"
                class="form-control"
                placeholder="Enter Rent Amount"
                required
                min="1"
            />
            <div class="invalid-feedback">Please enter a valid rent amount greater than 0.</div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary btn-next" data-next="batch-details">
                Next: Batch Details <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("centerForm");
        const nextBtn = form.querySelector(".btn-next");
        const centerNumber = document.getElementById("centerNumber");
        const openingTime = document.getElementById("openingTime");
        const closingTime = document.getElementById("closingTime");
        const password = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        // Auto-generate Center Number
        function generateCenterNumber() {
            const timestamp = Date.now().toString().slice(-8); // Last 8 digits of timestamp
            const random = Math.floor(1000 + Math.random() * 9000); // 4-digit random number
            centerNumber.value = `CTR-${timestamp}-${random}`;
            validateForm(); // Revalidate after setting center number
        }

        // Toggle Password Visibility
        togglePassword.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.querySelector("i").classList.toggle("fa-eye");
            this.querySelector("i").classList.toggle("fa-eye-slash");
        });

        // Validate form and update button state
        function validateForm() {
            let isValid = form.checkValidity();

            // Custom validation for closing time
            if (openingTime.value && closingTime.value) {
                if (closingTime.value <= openingTime.value) {
                    closingTime.setCustomValidity("Closing time must be after opening time.");
                    isValid = false;
                } else {
                    closingTime.setCustomValidity("");
                }
            }

            // Apply Bootstrap validation styles
            form.classList.add("was-validated");

            // Enable/disable Next button
            nextBtn.disabled = !isValid;
        }

        // Attach event listeners to inputs
        form.querySelectorAll("input, textarea, select").forEach((input) => {
            input.addEventListener("input", validateForm);
            input.addEventListener("change", validateForm);
        });

        // Generate center number on form load
        generateCenterNumber();

        // Initial validation
        validateForm();
    });
</script>


              <!-- Batch Details Form -->
<div class="form-container form-section" id="batch-details">
    <h3 class="section-title"><i class="fas fa-layer-group me-2"></i>Batch Details</h3>
    <form id="batchForm" novalidate>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="batchName" class="form-label required-field">Batch Name</label>
                <input type="text" class="form-control" id="batchName" required>
                <div class="invalid-feedback">Batch name is required.</div>
            </div>
            <div class="col-md-6">
                <label for="batchLevel" class="form-label required-field">Level</label>
                <select class="form-select" id="batchLevel" required>
                    <option value="">Select Level</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
                <div class="invalid-feedback">Please select a level.</div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="batchStartTime" class="form-label required-field">Start Time</label>
                <input type="time" class="form-control" id="batchStartTime" required>
                <div class="invalid-feedback">Start time is required.</div>
            </div>
            <div class="col-md-6">
                <label for="batchEndTime" class="form-label required-field">End Time</label>
                <input type="time" class="form-control" id="batchEndTime" required>
                <div class="invalid-feedback">End time must be after start time.</div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="startDate" class="form-label required-field">Start Date</label>
                <input type="date" class="form-control" id="startDate" required>
                <div class="invalid-feedback">Start date is required.</div>
            </div>
            <div class="col-md-6">
                <label for="endDate" class="form-label required-field">End Date</label>
                <input type="date" class="form-control" id="endDate" required>
                <div class="invalid-feedback">End date must be after start date.</div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="duration" class="form-label required-field">Duration (Months)</label>
                <input type="number" class="form-control" id="duration" min="1" readonly required>
                <div class="invalid-feedback">Duration must be at least 1 month.</div>
            </div>
            <div class="col-md-6">
                <label for="category" class="form-label required-field">Category</label>
                <select class="form-select" id="category" required>
                    <option value="">Select Category</option>
                    <option value="corporate">Corporate</option>
                    <option value="individual">Individual</option>
                    <option value="group">Group</option>
                </select>
                <div class="invalid-feedback">Please select a category.</div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary btn-prev" data-prev="center-details">
                <i class="fas fa-arrow-left me-2"></i> Back to Center Details
            </button>
            <div>
                <button type="button" class="btn btn-info" id="addAnotherBatch">
                    <i class="fas fa-plus me-2"></i> Add Another Batch
                </button>
                <button type="button" class="btn btn-primary btn-next" data-next="staff-details">
                    Next: Staff Details <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="text-right mt-4">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#batchModal">
            <i class="fas fa-plus"></i> Add Batch
        </button>
    </div>

    <!-- Batch List Table -->
    <div class="batch-table mt-4">
        <h5 class="mb-3">Added Batches</h5>
        <div id="batchList">
            <p class="text-center">No batches added yet</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("batchForm");
        const nextBtn = form.querySelector(".btn-next");
        const batchStartTime = document.getElementById("batchStartTime");
        const batchEndTime = document.getElementById("batchEndTime");
        const startDate = document.getElementById("startDate");
        const endDate = document.getElementById("endDate");
        const duration = document.getElementById("duration");

        // Calculate duration based on start and end dates
        function calculateDuration() {
            if (startDate.value && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);
                if (end >= start) {
                    let months = (end.getFullYear() - start.getFullYear()) * 12;
                    months += end.getMonth() - start.getMonth();
                    if (end.getDate() < start.getDate()) {
                        months--;
                    }
                    duration.value = months >= 1 ? months : 1; // Ensure at least 1 month
                    duration.setCustomValidity(""); // Clear any previous invalid state
                } else {
                    duration.value = "";
                    duration.setCustomValidity("End date must be after start date.");
                }
            } else {
                duration.value = "";
                duration.setCustomValidity("Please select start and end dates.");
            }
            validateForm(); // Revalidate form after duration calculation
        }

        // Validate form and update button state
        function validateForm() {
            let isValid = form.checkValidity();

            // Custom validation for time
            if (batchStartTime.value && batchEndTime.value) {
                if (batchEndTime.value <= batchStartTime.value) {
                    batchEndTime.setCustomValidity("End time must be after start time.");
                    isValid = false;
                } else {
                    batchEndTime.setCustomValidity("");
                }
            }

            // Custom validation for dates
            if (startDate.value && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);
                if (end <= start) {
                    endDate.setCustomValidity("End date must be after start date.");
                    isValid = false;
                } else {
                    endDate.setCustomValidity("");
                }
            }

            // Custom validation for duration
            if (duration.value < 1) {
                duration.setCustomValidity("Duration must be at least 1 month.");
                isValid = false;
            } else {
                duration.setCustomValidity("");
            }

            // Apply Bootstrap validation styles
            form.classList.add("was-validated");

            // Enable/disable Next button
            nextBtn.disabled = !isValid;
        }

        // Attach event listeners to inputs
        form.querySelectorAll("input, select").forEach((input) => {
            input.addEventListener("input", validateForm);
            input.addEventListener("change", validateForm);
        });

        // Attach specific listeners for date fields to calculate duration
        startDate.addEventListener("change", calculateDuration);
        endDate.addEventListener("change", calculateDuration);

        // Initial validation
        validateForm();
    });
</script>
            <!-- Staff Details Form -->
            <div class="form-container form-section" id="staff-details">
                <h3 class="section-title"><i class="fas fa-users me-2"></i>Staff Details</h3>
                <form id="staffForm" novalidate>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="staffName" class="form-label required-field">Staff Name</label>
                            <input type="text" class="form-control" id="staffName" required>
                            <div class="invalid-feedback">Staff name is required.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="contactNo" class="form-label required-field">Contact Number</label>
                            <input type="tel" class="form-control" id="contactNo" pattern="^[0-9]{10}$" required>
                            <div class="invalid-feedback">Enter a valid 10-digit contact number.</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="role" class="form-label required-field">Role</label>
                            <select class="form-select" id="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Administrator</option>
                                <option value="manager">Manager</option>
                                <option value="coach">Coach</option>
                                <option value="support">Support Staff</option>
                            </select>
                            <div class="invalid-feedback">Please select a role.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="joiningDate" class="form-label required-field">Joining Date</label>
                            <input type="date" class="form-control" id="joiningDate" required>
                            <div class="invalid-feedback">Joining date is required.</div>
                        </div>
                    </div>

                    <!-- Conditional Coach Assignment Fields -->
                    <div id="coachAssignment" class="conditional-field d-none">
                        <h5 class="mt-4 mb-3">Coach Batch Assignment</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="assignedBatch" class="form-label">Assign Batch</label>
                                <select class="form-select" id="assignedBatch">
                                    <option value="">Select Batch</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="coachLevel" class="form-label">Coach Level</label>
                                <select class="form-select" id="coachLevel">
                                    <option value="">Select Level</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="coachCategory" class="form-label">Category Specialization</label>
                                <select class="form-select" id="coachCategory">
                                    <option value="">Select Category</option>
                                    <option value="corporate">Corporate</option>
                                    <option value="individual">Individual</option>
                                    <option value="group">Group</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="coachDuration" class="form-label">Session Duration (hours)</label>
                                <input type="number" class="form-control" id="coachDuration" min="1">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary btn-prev" data-prev="batch-details">
                            <i class="fas fa-arrow-left me-2"></i> Back to Batch Details
                        </button>
                        <div>
                            <button type="button" class="btn btn-info" id="addAnotherStaff">
                                <i class="fas fa-plus me-2"></i> Add Another Staff
                            </button>
                            <button type="button" class="btn btn-primary btn-next" data-next="facility-details" disabled>
                                Next: Facility Details <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const staffForm = document.getElementById("staffForm");
                    const nextBtn = staffForm.querySelector(".btn-next");
                    const role = document.getElementById("role");
                    const coachFields = document.getElementById("coachAssignment");

                    function validateStaffForm() {
                        let isValid = staffForm.checkValidity();

                        staffForm.classList.add("was-validated");
                        nextBtn.disabled = !isValid;
                    }

                    // Role-based display
                    role.addEventListener("change", function() {
                        if (role.value === "coach") {
                            coachFields.classList.remove("d-none");
                        } else {
                            coachFields.classList.add("d-none");
                        }
                        validateStaffForm();
                    });

                    staffForm.querySelectorAll("input, select").forEach(el => {
                        el.addEventListener("input", validateStaffForm);
                        el.addEventListener("change", validateStaffForm);
                    });
                });
            </script>




            <!-- Facility Details Form -->
            <div class="form-container form-section" id="facility-details">
                <h3 class="section-title"><i class="fas fa-dumbbell me-2"></i> Facility Details</h3>
                <form id="facilityForm" novalidate>
                    <input type="hidden" name="center_id" value="<?php echo $this->session->userdata('center_id'); ?>">

                    <div class="mb-3">
                        <label for="facilityName" class="form-label required-field">Facility Name</label>
                        <input type="text" class="form-control" id="facilityName" name="facility_name" required>
                        <div class="invalid-feedback">Facility name is required.</div>
                    </div>

                    <!-- Subtypes & Rent Section -->
                    <div class="mb-3">
                        <label class="form-label">Subtypes & Rent</label>
                        <div id="subTypeContainer">
                            <div class="row mb-2 subTypeRow">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Subtype name" name="subtype_name">
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" placeholder="Rent" name="rent" min="0">
                                    <div class="invalid-feedback">Rent must be 0 or more.</div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm removeSubType">X</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addSubTypeRow">
                            + Add Subtype
                        </button>
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-prev" data-prev="staff-details">
                        <i class="fas fa-arrow-left me-2"></i> Back to Staff Details
                    </button>

                    <!-- Add Facility Button -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-info" id="addFacility">
                            <i class="fas fa-plus me-2"></i> Add Facility
                        </button>
                    </div>
                </form>

            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const facilityForm = document.getElementById("facilityForm");
                    const saveBtn = document.getElementById("saveFacilityBtn");

                    function validateFacilityForm() {
                        let isValid = facilityForm.checkValidity();
                        facilityForm.classList.add("was-validated");
                        saveBtn.disabled = !isValid;
                    }

                    // Dynamic subtype rows
                    document.getElementById("addSubTypeRow").addEventListener("click", function() {
                        const container = document.getElementById("subTypeContainer");
                        const row = document.createElement("div");
                        row.className = "row mb-2 subTypeRow";
                        row.innerHTML = `
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Subtype name" name="subType[]">
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control" placeholder="Rent" name="subRent[]" min="0">
                <div class="invalid-feedback">Rent must be 0 or more.</div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm removeSubType">X</button>
            </div>
        `;
                        container.appendChild(row);
                        row.querySelectorAll("input").forEach(el => {
                            el.addEventListener("input", validateFacilityForm);
                        });
                        row.querySelector(".removeSubType").addEventListener("click", function() {
                            row.remove();
                            validateFacilityForm();
                        });
                    });

                    // Attach listeners
                    facilityForm.querySelectorAll("input").forEach(el => {
                        el.addEventListener("input", validateFacilityForm);
                    });
                });
            </script>






            <!-- Batch Modal -->
            <div class="modal fade" id="batchModal" tabindex="-1" aria-labelledby="batchLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-Centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="batchLabel">Add Batch</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="modalBatchForm">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="batch_timing" class="form-label">Batch Timing</label>
                                        <input type="time" id="batch_timing" name="batch_timing" class="form-control" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="batch_category" class="form-label">Category</label>
                                        <select id="batch_category" name="batch_category" class="form-control">
                                            <option value="">Select Category</option>
                                            <option value="Beginner">Beginner</option>
                                            <option value="Intermediate">Intermediate</option>
                                            <option value="Advanced">Advanced</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="batchSubmitBtn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facility Modal -->
            <div class="modal fade" id="facilityModal" tabindex="-1" aria-labelledby="facilityLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-Centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="facilityLabel">Add Facility</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="modalFacilityForm">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="facility" class="form-label">Facility</label>
                                        <select id="facility" name="facility" class="form-control">
                                            <option value="">Select Facility</option>
                                            <option value="Locker">Locker</option>
                                            <option value="Shoe">Shoe</option>
                                            <option value="Racket">Racket</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="locker_no" class="form-label">Locker No</label>
                                        <input type="text" id="locker_no" name="locker_no" class="form-control" placeholder="Enter Locker No" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="facility_rent" class="form-label">Rent</label>
                                        <input type="number" id="facility_rent" name="facility_rent" class="form-control" placeholder="Enter Rent Amount" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="facility_rent_date" class="form-label">Rent Date</label>
                                        <input type="date" id="facility_rent_date" name="facility_rent_date" class="form-control" />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="facilitySubmitBtn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Modal -->
            <div class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="staffLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-Centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staffLabel">Add Staff</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="modalStaffForm">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="staff_category" class="form-label">Category</label>
                                        <select id="staff_category" name="staff_category" class="form-control">
                                            <option value="">Select Category</option>
                                            <option value="coach">Coach</option>
                                            <option value="co-ordinator">Co-ordinator</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="staff_name" class="form-label">Staff Name</label>
                                        <input type="text" id="staff_name" name="staff_name" class="form-control" placeholder="Enter Staff Name" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="staff_timing" class="form-label">Timing</label>
                                        <input type="time" id="staff_timing" name="staff_timing" class="form-control" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="staff_salary" class="form-label">Salary</label>
                                        <input type="number" id="staff_salary" name="staff_salary" class="form-control" placeholder="Enter Salary" />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="staffSubmitBtn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize modals
                const batchModal = new bootstrap.Modal(document.getElementById('batchModal'));
                const facilityModal = new bootstrap.Modal(document.getElementById('facilityModal'));
                const staffModal = new bootstrap.Modal(document.getElementById('staffModal'));

                // Modal submit buttons
                document.addEventListener("DOMContentLoaded", function() {
                    const batchSubmitBtn = document.getElementById("batchSubmitBtn");
                    if (batchSubmitBtn) {
                        batchSubmitBtn.addEventListener("click", function() {
                            batchModal.hide();
                            alert("Batch added successfully!");
                        });
                    }
                });


                document.getElementById('facilitySubmitBtn').addEventListener('click', function() {
                    // Add your facility submission logic here
                    facilityModal.hide();
                    alert('Facility added successfully!');
                });

                document.getElementById('staffSubmitBtn').addEventListener('click', function() {
                    // Add your staff submission logic here
                    staffModal.hide();
                    alert('Staff added successfully!');
                });

                // Rest of your existing JavaScript code...
                const progressBar = document.querySelector('.progress-bar');
                const steps = document.querySelectorAll('.step');
                const formSections = document.querySelectorAll('.form-section');
                const nextButtons = document.querySelectorAll('.btn-next');
                const prevButtons = document.querySelectorAll('.btn-prev');
                const roleSelect = document.getElementById('role');
                const coachAssignment = document.getElementById('coachAssignment');
                const addAnotherStaffBtn = document.getElementById('addAnotherStaff');
                const staffForm = document.getElementById('staffForm');
                const staffList = document.getElementById('staffList');
                const addAnotherFacilityBtn = document.getElementById('addAnotherFacility');
                const facilityForm = document.getElementById('facilityForm');
                const facilityList = document.getElementById('facilityList');
                const addAnotherBatchBtn = document.getElementById('addAnotherBatch');
                const batchForm = document.getElementById('batchForm');
                const batchList = document.getElementById('batchList');
                const assignedBatchSelect = document.getElementById('assignedBatch');
                const toggleSidebarBtn = document.querySelector('.toggle-sidebar');
                const CenterForm = document.getElementById('CenterForm');

                let currentStep = 1;
                const totalSteps = 4;
                let staffMembers = [];
                let facilities = [];
                let batches = [];

                // Toggle sidebar on small screens
                if (toggleSidebarBtn) {
                    toggleSidebarBtn.addEventListener('click', function() {
                        document.body.classList.toggle('show-sidebar');
                    });
                }

                // Show/hide coach assignment fields based on role selection
                if (roleSelect) {
                    roleSelect.addEventListener('change', function() {
                        if (this.value === 'coach') {
                            coachAssignment.style.display = 'block';
                            updateBatchDropdown();
                        } else {
                            coachAssignment.style.display = 'none';
                        }
                    });
                }

                // Update batch dropdown with added batches
                function updateBatchDropdown() {
                    if (!assignedBatchSelect) return;

                    assignedBatchSelect.innerHTML = '<option value="">Select Batch</option>';
                    batches.forEach((batch, index) => {
                        const option = document.createElement('option');
                        option.value = index;
                        option.textContent = batch.name;
                        assignedBatchSelect.appendChild(option);
                    });
                }

                // Add batch functionality
                if (addAnotherBatchBtn) {
                    addAnotherBatchBtn.addEventListener('click', function() {
                        // Validate form
                        const batchName = document.getElementById('batchName');
                        const batchLevel = document.getElementById('batchLevel');
                        const batchStartTime = document.getElementById('batchStartTime');
                        const batchEndTime = document.getElementById('batchEndTime');
                        const startDate = document.getElementById('startDate');
                        const endDate = document.getElementById('endDate');
                        const duration = document.getElementById('duration');
                        const category = document.getElementById('category');

                        let isValid = true;

                        if (!batchName.value) {
                            batchName.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            batchName.classList.remove('is-invalid');
                        }

                        if (!batchLevel.value) {
                            batchLevel.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            batchLevel.classList.remove('is-invalid');
                        }

                        if (!batchStartTime.value) {
                            batchStartTime.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            batchStartTime.classList.remove('is-invalid');
                        }

                        if (!batchEndTime.value) {
                            batchEndTime.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            batchEndTime.classList.remove('is-invalid');
                        }

                        if (!startDate.value) {
                            startDate.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            startDate.classList.remove('is-invalid');
                        }

                        if (!endDate.value) {
                            endDate.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            endDate.classList.remove('is-invalid');
                        }

                        if (!duration.value) {
                            duration.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            duration.classList.remove('is-invalid');
                        }

                        if (!category.value) {
                            category.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            category.classList.remove('is-invalid');
                        }

                        if (!isValid) {
                            alert('Please fill all required fields before adding batch.');
                            return;
                        }

                        // Collect batch data
                        const batchData = {
                            name: batchName.value,
                            level: batchLevel.value,
                            startTime: batchStartTime.value,
                            endTime: batchEndTime.value,
                            startDate: startDate.value,
                            endDate: endDate.value,
                            duration: duration.value,
                            category: category.value
                        };

                        // Add to batch list
                        batches.push(batchData);
                        updateBatchList();
                        updateBatchDropdown();

                        // Reset form
                        batchForm.reset();
                    });
                }

                // Add staff member functionality
                if (addAnotherStaffBtn) {
                    addAnotherStaffBtn.addEventListener('click', function() {
                        // Validate form
                        const staffName = document.getElementById('staffName');
                        const contactNo = document.getElementById('contactNo');
                        const role = document.getElementById('role');
                        const joiningDate = document.getElementById('joiningDate');

                        let isValid = true;

                        if (!staffName.value) {
                            staffName.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            staffName.classList.remove('is-invalid');
                        }

                        if (!contactNo.value) {
                            contactNo.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            contactNo.classList.remove('is-invalid');
                        }

                        if (!role.value) {
                            role.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            role.classList.remove('is-invalid');
                        }

                        if (!joiningDate.value) {
                            joiningDate.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            joiningDate.classList.remove('is-invalid');
                        }

                        if (!isValid) {
                            alert('Please fill all required fields before adding staff.');
                            return;
                        }

                        // Collect staff data
                        const staffData = {
                            name: staffName.value,
                            contact: contactNo.value,
                            role: role.value,
                            joiningDate: joiningDate.value,
                            assignedBatch: role.value === 'coach' ? document.getElementById('assignedBatch').value : 'N/A',
                            level: role.value === 'coach' ? document.getElementById('coachLevel').value : 'N/A',
                            category: role.value === 'coach' ? document.getElementById('coachCategory').value : 'N/A',
                            duration: role.value === 'coach' ? document.getElementById('coachDuration').value : 'N/A'
                        };

                        // Add to staff list
                        staffMembers.push(staffData);
                        updateStaffList();

                        // Reset form
                        staffForm.reset();
                        if (coachAssignment) coachAssignment.style.display = 'none';
                    });
                }

                // Add facility functionality
                if (addAnotherFacilityBtn) {
                    addAnotherFacilityBtn.addEventListener('click', function() {
                        // Validate form
                        const facilityType = document.getElementById('facilityType');
                        const facilityQuantity = document.getElementById('facilityQuantity');
                        const facilityCondition = document.getElementById('facilityCondition');

                        let isValid = true;

                        if (!facilityType.value) {
                            facilityType.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            facilityType.classList.remove('is-invalid');
                        }

                        if (!facilityQuantity.value) {
                            facilityQuantity.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            facilityQuantity.classList.remove('is-invalid');
                        }

                        if (!facilityCondition.value) {
                            facilityCondition.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            facilityCondition.classList.remove('is-invalid');
                        }

                        if (!isValid) {
                            alert('Please fill all required fields before adding facility.');
                            return;
                        }

                        // Collect facility data
                        const facilityData = {
                            type: facilityType.value,
                            subType: document.getElementById('subType').value,
                            printDetails: document.getElementById('printDetails').value,
                            quantity: facilityQuantity.value,
                            condition: facilityCondition.value
                        };

                        // Add to facility list
                        facilities.push(facilityData);
                        updateFacilityList();

                        // Reset form
                        facilityForm.reset();
                    });
                }

                // Update batch list
                function updateBatchList() {
                    if (!batchList) return;

                    if (batches.length === 0) {
                        batchList.innerHTML = '<p class="text-Center">No batches added yet</p>';
                        return;
                    }

                    batchList.innerHTML = '';
                    batches.forEach((batch, index) => {
                        const card = document.createElement('div');
                        card.className = 'batch-card';
                        card.innerHTML = `
                        <div class="d-flex justify-content-between align-items-Center">
                            <div>
                                <h6>${batch.name} (${batch.level})</h6>
                                <p class="mb-1">Time: ${batch.startTime} - ${batch.endTime}</p>
                                <p class="mb-1">Dates: ${batch.startDate} to ${batch.endDate}</p>
                                <p class="mb-0">Duration: ${batch.duration} hours | Category: ${batch.category}</p>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-1 edit-batch" data-index="${index}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-batch" data-index="${index}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                        batchList.appendChild(card);
                    });

                    // Add event listeners for edit and delete buttons
                    document.querySelectorAll('.edit-batch').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const index = this.getAttribute('data-index');
                            editBatch(index);
                        });
                    });

                    document.querySelectorAll('.delete-batch').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const index = this.getAttribute('data-index');
                            deleteBatch(index);
                        });
                    });
                }

                // Update staff list table
                function updateStaffList() {
                    if (!staffList) return;

                    if (staffMembers.length === 0) {
                        staffList.innerHTML = '<tr><td colspan="6" class="text-Center">No staff members added yet</td></tr>';
                        return;
                    }

                    staffList.innerHTML = '';
                    staffMembers.forEach((staff, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${staff.name}</td>
                        <td>${staff.contact}</td>
                        <td>${staff.role.charAt(0).toUpperCase() + staff.role.slice(1)}</td>
                        <td>${staff.joiningDate}</td>
                        <td>${staff.assignedBatch}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1 edit-staff" data-index="${index}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-staff" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                        staffList.appendChild(row);
                    });

                    // Add event listeners for edit and delete buttons
                    document.querySelectorAll('.edit-staff').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const index = this.getAttribute('data-index');
                            editStaff(index);
                        });
                    });

                    document.querySelectorAll('.delete-staff').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const index = this.getAttribute('data-index');
                            deleteStaff(index);
                        });
                    });
                }

                // Update facility list
                function updateFacilityList() {
                    if (!facilityList) return;

                    if (facilities.length === 0) {
                        facilityList.innerHTML = '<p class="text-Center">No facilities added yet</p>';
                        return;
                    }

                    facilityList.innerHTML = '';
                    facilities.forEach((facility, index) => {
                        const card = document.createElement('div');
                        card.className = 'facility-card';
                        card.innerHTML = `
                        <div class="d-flex justify-content-between align-items-Center">
                            <div>
                                <h6>${facility.type.charAt(0).toUpperCase() + facility.type.slice(1)}${facility.subType ? ' - ' + facility.subType : ''}</h6>
                                <p class="mb-1">Quantity: ${facility.quantity} | Condition: ${facility.condition}</p>
                                ${facility.printDetails ? `<p class="mb-0">Details: ${facility.printDetails}</p>` : ''}
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-1 edit-facility" data-index="${index}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-facility" data-index="${index}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                        facilityList.appendChild(card);
                    });

                    // Add event listeners for edit and delete buttons
                    document.querySelectorAll('.edit-facility').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const index = this.getAttribute('data-index');
                            editFacility(index);
                        });
                    });

                    document.querySelectorAll('.delete-facility').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const index = this.getAttribute('data-index');
                            deleteFacility(index);
                        });
                    });
                }

                // Edit batch function
                function editBatch(index) {
                    const batch = batches[index];

                    // Populate form fields
                    document.getElementById('batchName').value = batch.name;
                    document.getElementById('batchLevel').value = batch.level;
                    document.getElementById('batchStartTime').value = batch.startTime;
                    document.getElementById('batchEndTime').value = batch.endTime;
                    document.getElementById('startDate').value = batch.startDate;
                    document.getElementById('endDate').value = batch.endDate;
                    document.getElementById('duration').value = batch.duration;
                    document.getElementById('category').value = batch.category;

                    // Remove the batch from the list
                    batches.splice(index, 1);
                    updateBatchList();
                    updateBatchDropdown();
                }

                // Delete batch function
                function deleteBatch(index) {
                    if (confirm('Are you sure you want to delete this batch?')) {
                        batches.splice(index, 1);
                        updateBatchList();
                        updateBatchDropdown();
                    }
                }

                // Edit staff function
                function editStaff(index) {
                    const staff = staffMembers[index];

                    // Populate form fields
                    document.getElementById('staffName').value = staff.name;
                    document.getElementById('contactNo').value = staff.contact;
                    document.getElementById('role').value = staff.role;
                    document.getElementById('joiningDate').value = staff.joiningDate;

                    if (staff.role === 'coach') {
                        document.getElementById('assignedBatch').value = staff.assignedBatch;
                        document.getElementById('coachLevel').value = staff.level;
                        document.getElementById('coachCategory').value = staff.category;
                        document.getElementById('coachDuration').value = staff.duration;
                        if (coachAssignment) coachAssignment.style.display = 'block';
                    }

                    // Remove the staff from the list
                    staffMembers.splice(index, 1);
                    updateStaffList();
                }

                // Delete staff function
                function deleteStaff(index) {
                    if (confirm('Are you sure you want to delete this staff member?')) {
                        staffMembers.splice(index, 1);
                        updateStaffList();
                    }
                }

                // Edit facility function
                function editFacility(index) {
                    const facility = facilities[index];

                    // Populate form fields
                    document.getElementById('facilityType').value = facility.type;
                    document.getElementById('subType').value = facility.subType;
                    document.getElementById('printDetails').value = facility.printDetails;
                    document.getElementById('facilityQuantity').value = facility.quantity;
                    document.getElementById('facilityCondition').value = facility.condition;

                    // Remove the facility from the list
                    facilities.splice(index, 1);
                    updateFacilityList();
                }

                // Delete facility function
                function deleteFacility(index) {
                    if (confirm('Are you sure you want to delete this facility?')) {
                        facilities.splice(index, 1);
                        updateFacilityList();
                    }
                }

                // Update progress bar and steps
                function updateProgress() {
                    const progressPercentage = (currentStep / totalSteps) * 100;
                    if (progressBar) progressBar.style.width = progressPercentage + '%';

                    steps.forEach((step, index) => {
                        if (index < currentStep) {
                            step.classList.add('step-active');
                        } else {
                            step.classList.remove('step-active');
                        }
                    });
                }

                // Next button functionality
                nextButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const nextSectionId = this.getAttribute('data-next');

                        // Validate current form before proceeding
                        const currentSection = document.querySelector('.form-section.active');
                        const requiredFields = currentSection.querySelectorAll('[required]');
                        let isValid = true;

                        requiredFields.forEach(field => {
                            if (!field.value) {
                                field.classList.add('is-invalid');
                                isValid = false;
                            } else {
                                field.classList.remove('is-invalid');
                            }
                        });

                        if (!isValid) {
                            alert('Please fill all required fields before proceeding.');
                            return;
                        }

                        // Update progress
                        currentStep++;
                        updateProgress();

                        // Switch to next section
                        formSections.forEach(section => {
                            section.classList.remove('active');
                        });
                        document.getElementById(nextSectionId).classList.add('active');
                    });
                });

                // Previous button functionality
                prevButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const prevSectionId = this.getAttribute('data-prev');

                        // Update progress
                        currentStep--;
                        updateProgress();

                        // Switch to previous section
                        formSections.forEach(section => {
                            section.classList.remove('active');
                        });
                        document.getElementById(prevSectionId).classList.add('active');
                    });
                });

                // Form submission
                if (facilityForm) {
                    facilityForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        // Validate form
                        const requiredFields = this.querySelectorAll('[required]');
                        let isValid = true;

                        requiredFields.forEach(field => {
                            if (!field.value) {
                                field.classList.add('is-invalid');
                                isValid = false;
                            } else {
                                field.classList.remove('is-invalid');
                            }
                        });

                        if (!isValid) {
                            alert('Please fill all required fields before submitting.');
                            return;
                        }

                        // Collect all data
                        const CenterData = {
                            name: document.getElementById('CenterName').value,
                            number: document.getElementById('CenterNumber').value,
                            address: document.getElementById('address').value,
                            openingTime: document.getElementById('openingTime').value,
                            closingTime: document.getElementById('closingTime').value,
                            rentAmount: document.getElementById('center_rent').value || '0',
                            printDate: document.getElementById('printDate').value,
                            printPaidDate: document.getElementById('printPaidDate').value,
                            batches: batches,
                            staff: staffMembers,
                            facilities: facilities
                        };

                        // Here you would typically send the data to the server
                        console.log('Center Data:', CenterData);
                        alert('Center details saved successfully!');

                        // Reset the form and data
                        CenterForm.reset();
                        batchForm.reset();
                        staffForm.reset();
                        facilityForm.reset();

                        batches = [];
                        staffMembers = [];
                        facilities = [];

                        updateBatchList();
                        updateStaffList();
                        updateFacilityList();

                        // Reset to first step
                        currentStep = 1;
                        updateProgress();

                        formSections.forEach(section => {
                            section.classList.remove('active');
                        });
                        document.getElementById('Center-details').classList.add('active');
                    });
                }

                // Initialize the progress
                updateProgress();
            });
            document.addEventListener("DOMContentLoaded", function() {
                const subTypeContainer = document.getElementById("subTypeContainer");
                const addSubTypeBtn = document.getElementById("addSubTypeRow");
                const facilityList = document.getElementById("facilityList");
                const addFacilityBtn = document.getElementById("addFacility");

                // Add new subtype row
                if (addSubTypeBtn) {
                    addSubTypeBtn.addEventListener("click", function() {
                        const newRow = document.createElement("div");
                        newRow.classList.add("row", "mb-2", "subTypeRow");
                        newRow.innerHTML = `
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Subtype name" name="subType[]">
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control" placeholder="Rent" name="subRent[]" min="0">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm removeSubType">X</button>
                </div>
            `;
                        subTypeContainer.appendChild(newRow);
                    });
                }

                // Remove subtype row
                if (subTypeContainer) {
                    subTypeContainer.addEventListener("click", function(e) {
                        if (e.target.classList.contains("removeSubType")) {
                            e.target.closest(".subTypeRow").remove();
                        }
                    });
                }

                // Add facility to list
                if (addFacilityBtn) {
                    addFacilityBtn.addEventListener("click", function() {
                        const name = document.getElementById("facilityName").value;

                        if (!name) {
                            alert("Please enter Facility Name.");
                            return;
                        }

                        // Collect subtypes with rent
                        const subTypes = [];
                        document.querySelectorAll("#subTypeContainer .subTypeRow").forEach(row => {
                            const sub = row.querySelector("input[name='subType[]']").value;
                            const rent = row.querySelector("input[name='subRent[]']").value;
                            if (sub) subTypes.push({
                                sub,
                                rent
                            });
                        });

                        if (subTypes.length === 0) {
                            alert("Please add at least one Subtype with rent.");
                            return;
                        }

                        // Build facility card
                        const facilityHTML = `
                <div class="card mb-2 shadow-sm">
                    <div class="card-body p-2">
                        <h6 class="mb-1"><strong>${name}</strong></h6>
                        <ul class="mt-2">
                            ${subTypes.map(st => `<li>${st.sub} - Rent: ₹${st.rent || 0}</li>`).join("")}
                        </ul>
                    </div>
                </div>
            `;

                        // Append to facility list
                        if (facilityList.querySelector("p")) {
                            facilityList.innerHTML = ""; // remove "No facilities added yet"
                        }
                        facilityList.innerHTML += facilityHTML;

                        // Reset form for new entry
                        document.getElementById("facilityForm").reset();
                        subTypeContainer.innerHTML = `
                <div class="row mb-2 subTypeRow">
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Subtype name" name="subType[]">
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control" placeholder="Rent" name="subRent[]" min="0">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm removeSubType">X</button>
                    </div>
                </div>
            `;
                    });
                }
            });
        </script>

        <script>
            const baseUrl = "<?= base_url(); ?>";
            let savedCenterId = null; // 🔑 Global variable to store center_id

            // ---------------- Save Center ----------------
            $("#CenterForm").on("submit", function(e) {
                e.preventDefault();

                const payload = {
                    name: $("#CenterName").val(),
                    address: $("#address").val(),
                    center_number: $("#CenterNumber").val(),
                    rent_amount: $("#center_rent").val() || "0", // ✅ only this one
                    rent_paid_date: $("#printPaidDate").val(),
                    center_timing_from: $("#openingTime").val(),
                    center_timing_to: $("#closingTime").val(),
                    password: $("#password").val()
                };


                $.ajax({
                    url: baseUrl + "Center/saveCenter",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(payload),
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.status === "success") {
                                savedCenterId = res.center_id; // ✅ Store center_id globally
                                alert("Center saved successfully! ID: " + savedCenterId);

                                // Go to batch section
                                $(".form-section").removeClass("active");
                                $("#batch-details").addClass("active");
                            } else {
                                alert("Error: " + res.message);
                            }
                        } catch (e) {
                            console.error("Invalid JSON response", response);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                        alert("Something went wrong!");
                    }
                });
            });

            $(".btn-next[data-next='batch-details']").on("click", function() {
                $("#CenterForm").trigger("submit");
            });

            // ---------------- Save Batch ----------------
            $("#batchForm").on("submit", function(e) {
                e.preventDefault();

                if (!savedCenterId) {
                    alert("Please save Center details first!");
                    return;
                }

                const payload = {
                    center_id: savedCenterId, // ✅ use stored center_id
                    batch_name: $("#batchName").val(),
                    batch_level: $("#batchLevel").val(),
                    start_time: $("#batchStartTime").val(),
                    end_time: $("#batchEndTime").val(),
                    duration: $("#duration").val(),
                    start_date: $("#startDate").val(),
                    end_date: $("#endDate").val(),
                    category: $("#category").val()
                };

                $.ajax({
                    url: baseUrl + "Center/saveBatch",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(payload),
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.status === "success") {
                                alert("Batch saved successfully! ID: " + res.batch_id);

                                // ✅ Append to batch list table
                                $("#batchList").append(
                                    `<p><strong>${payload.batch_name}</strong> (${payload.batch_level}) 
                             - ${payload.start_date} to ${payload.end_date}</p>`
                                );
                            } else {
                                alert("Error: " + res.message);
                            }
                        } catch (e) {
                            console.error("Invalid JSON response", response);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                        alert("Something went wrong!");
                    }
                });
            });

            // Trigger Save Batch on button click
            $(".btn-next[data-next='staff-details']").on("click", function() {
                $("#batchForm").trigger("submit");
            });

            $("#addAnotherBatch").on("click", function() {
                $("#batchForm").trigger("submit");
            });
        </script>
        <script>
            // ---------------- Fetch batches for staff assignment ----------------
            function loadBatchesForStaff() {
                if (!savedCenterId) return;

                $.ajax({
                    url: baseUrl + "Center/getBatchesByCenter/" + savedCenterId, // ✅ create this API
                    type: "GET",
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.status === "success") {
                                $("#assignedBatch").empty().append('<option value="">Select Batch</option>');
                                res.data.forEach(batch => {
                                    $("#assignedBatch").append(
                                        `<option value="${batch.id}">${batch.batch_name} (${batch.batch_level})</option>`
                                    );
                                });
                            } else {
                                console.warn("No batches found for this center");
                            }
                        } catch (e) {
                            console.error("Invalid JSON response", response);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error fetching batches:", xhr.responseText);
                    }
                });
            }

            // ---------------- Show/hide coach assignment ----------------
            $("#role").on("change", function() {
                if ($(this).val() === "coach") {
                    $("#coachAssignment").show();
                    loadBatchesForStaff(); // Load batches dynamically
                } else {
                    $("#coachAssignment").hide();
                }
            });

            // ---------------- Save Staff ----------------
            $("#staffForm").on("submit", function(e) {
                e.preventDefault();

                if (!savedCenterId) {
                    alert("Please save Center details first!");
                    return;
                }

                const payload = {
                    center_id: savedCenterId,
                    staff_name: $("#staffName").val(),
                    contact_no: $("#contactNo").val(),
                    role: $("#role").val(),
                    joining_date: $("#joiningDate").val()
                };

                // If role is coach → include assignment details
                if (payload.role === "coach") {
                    payload.assigned_batch = $("#assignedBatch").val();
                    payload.coach_level = $("#coachLevel").val();
                    payload.coach_category = $("#coachCategory").val();
                    payload.coach_duration = $("#coachDuration").val();
                }

                $.ajax({
                    url: baseUrl + "Center/saveStaff",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(payload),
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.status === "success") {
                                alert("Staff saved successfully! ID: " + res.staff_id);

                                // ✅ Append staff to the table
                                $("#staffList").append(`
                            <tr>
                                <td>${payload.staff_name}</td>
                                <td>${payload.contact_no}</td>
                                <td>${payload.role}</td>
                                <td>${payload.joining_date}</td>
                                <td>${payload.assigned_batch ? payload.assigned_batch : "-"}</td>
                                <td><button class="btn btn-sm btn-danger">Delete</button></td>
                            </tr>
                        `);
                            } else {
                                alert("Error: " + res.message);
                            }
                        } catch (e) {
                            console.error("Invalid JSON response", response);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                        alert("Something went wrong!");
                    }
                });
            });

            // ---------------- Trigger buttons ----------------
            $("#addAnotherStaff").on("click", function() {
                $("#staffForm").trigger("submit");
            });

            $(".btn-next[data-next='facility-details']").on("click", function() {
                $("#staffForm").trigger("submit");
            });
        </script>
        <script>
            $(document).ready(function() {
                // Add Subtype Row
                $("#addSubTypeRow").click(function() {
                    let newRow = `
        <div class="row mb-2 subTypeRow">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Subtype name" name="subType[]">
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control" placeholder="Rent" name="subRent[]" min="0">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm removeSubType">X</button>
            </div>
        </div>`;
                    $("#subTypeContainer").append(newRow);
                });

                // Remove Subtype Row
                $(document).on("click", ".removeSubType", function() {
                    $(this).closest(".subTypeRow").remove();
                });

                // Save Facility (AJAX)
                $("#facilityForm").submit(function(e) {
                    e.preventDefault();

                    if (!savedCenterId) {
                        alert("Please save Center details first!");
                        return;
                    }

                    // ✅ Collect data using FormData (handles array subType[] & subRent[])
                    let formData = new FormData(this);
                    formData.append("center_id", savedCenterId); // inject global center_id

                    $.ajax({
                        url: baseUrl + "Center/saveFacility",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            try {
                                let res = JSON.parse(response);
                                if (res.status === "success") {
                                    alert("Facility saved successfully!");
                                    loadFacilities(); // Refresh list
                                    $("#facilityForm")[0].reset();
                                    $("#subTypeContainer").html(""); // Reset subtype rows
                                } else {
                                    alert(res.message);
                                }
                            } catch (e) {
                                console.error("Invalid JSON response", response);
                            }
                        },
                        error: function(xhr) {
                            console.error("Error saving facility:", xhr.responseText);
                            alert("Something went wrong while saving facility!");
                        }
                    });
                });

                // Load Facilities
                function loadFacilities() {
                    $.get(baseUrl + "Center/getFacilities/" + savedCenterId, function(data) {
                        $("#facilityList").html(data);
                    });
                }

                // Initial Load
                loadFacilities();
            });
        </script>

        <script>
            $(document).ready(function() {
                // Add Subtype Row
                $("#addSubTypeRow").click(function() {
                    let newRow = `
        <div class="row mb-2 subTypeRow">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Subtype name" name="subType[]">
            </div>
            <div class="col-md-4">
                <input type="number" class="form-control" placeholder="Rent" name="subRent[]" min="0">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm removeSubType">X</button>
            </div>
        </div>`;
                    $("#subTypeContainer").append(newRow);
                });

                // Remove Subtype Row
                $(document).on("click", ".removeSubType", function() {
                    $(this).closest(".subTypeRow").remove();
                });

                // Save Facility on Add Facility Button Click
                $("#addFacility").click(function() {
                    if (!savedCenterId) {
                        alert("Please save Center details first!");
                        return;
                    }

                    // Collect form data
                    const facilityName = $("#facilityName").val();
                    const subTypes = [];
                    $("#subTypeContainer .subTypeRow").each(function() {
                        const subType = $(this).find("input[name='subType[]']").val();
                        const rent = $(this).find("input[name='subRent[]']").val();
                        if (subType) {
                            subTypes.push({
                                subType,
                                rent: rent || 0
                            });
                        }
                    });

                    if (!facilityName) {
                        alert("Please enter Facility Name.");
                        return;
                    }

                    if (subTypes.length === 0) {
                        alert("Please add at least one Subtype with rent.");
                        return;
                    }

                    // Prepare payload
                    const payload = {
                        center_id: savedCenterId,
                        facility_name: facilityName,
                        subTypes: subTypes
                    };

                    $.ajax({
                        url: baseUrl + "Center/saveFacility",
                        type: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(payload),
                        success: function(response) {
                            try {
                                const res = JSON.parse(response);
                                if (res.status === "success") {
                                    alert("Facility saved successfully! ID: " + res.facility_id);
                                    // Refresh facility list
                                    loadFacilities();
                                    // Reset form
                                    $("#facilityForm")[0].reset();
                                    $("#subTypeContainer").html(`
                            <div class="row mb-2 subTypeRow">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Subtype name" name="subType[]">
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" placeholder="Rent" name="subRent[]" min="0">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm removeSubType">X</button>
                                </div>
                            </div>
                        `);
                                } else {
                                    alert("Error: " + res.message);
                                }
                            } catch (e) {
                                console.error("Invalid JSON response", response);
                            }
                        },
                        error: function(xhr) {
                            console.error("Error saving facility:", xhr.responseText);
                            alert("Something went wrong while saving facility!");
                        }
                    });
                });

                // Load Facilities
                function loadFacilities() {
                    $.get(baseUrl + "Center/getFacilities/" + savedCenterId, function(data) {
                        $("#facilityList").html(data);
                    });
                }

                // Initial Load
                loadFacilities();
            });
        </script>

        <script>
            const startDate = document.getElementById("startDate");
            const endDate = document.getElementById("endDate");
            const duration = document.getElementById("duration");

            function calculateDuration() {
                if (startDate.value && endDate.value) {
                    const start = new Date(startDate.value);
                    const end = new Date(endDate.value);

                    if (end >= start) {
                        let months = (end.getFullYear() - start.getFullYear()) * 12;
                        months += end.getMonth() - start.getMonth();

                        // Adjust if end day is before start day
                        if (end.getDate() < start.getDate()) {
                            months--;
                        }

                        duration.value = months >= 0 ? months : 0;
                    } else {
                        duration.value = "";
                    }
                }
            }

            startDate.addEventListener("change", calculateDuration);
            endDate.addEventListener("change", calculateDuration);
        </script>



</body>

</html>