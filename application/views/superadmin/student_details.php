<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - Super Admin</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #ff4040 0%, #470000 100%);
            --primary-color: #ff4040;
            --dark-bg: #343a40;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Content wrapper positioning */
        .content-wrapper {
            margin-left: 250px;
            transition: margin-left 0.3s ease-in-out;
            padding-top: 70px;
            min-height: 100vh;
            width: calc(100% - 250px);
            position: relative;
            box-sizing: border-box;
            z-index: 1;
        }

        .content-wrapper.minimized {
            margin-left: 60px;
            width: calc(100% - 60px);
        }

        .main-content {
            padding: 20px;
            transition: all 0.3s ease-in-out;
            width: 100%;
            box-sizing: border-box;
        }

        /* Container adjustments */
        .container-fluid {
            padding-left: 20px;
            padding-right: 20px;
        }

        /* Card adjustments for better spacing */
        .card {
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-radius: 15px;
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }

        .card-body {
            padding: 30px;
        }

        /* Inner Layout */
        .inner-layout {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }

        .inner-sidebar {
            width: 280px;
            background: linear-gradient(135deg, #ff4040 0%, #470000 100%);
            border-radius: 15px;
            padding: 25px 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            height: fit-content;
            position: sticky;
            top: 90px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
            color: white;
            text-decoration: none;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .menu-item i {
            margin-right: 15px;
            font-size: 18px;
            width: 20px;
        }

        /* Details Area */
        .details-area {
            flex: 1;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .section-content {
            display: none;
            padding: 30px;
        }

        .section-content.active {
            display: block;
        }

        .section-content h4 {
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 10px;
        }

        .section-content p {
            color: #666;
            margin-bottom: 25px;
            font-size: 16px;
        }

        /* Detail Rows */
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
        }

        .detail-label i {
            margin-right: 10px;
            color: var(--primary-color);
            width: 20px;
        }

        .detail-value {
            color: #666;
            font-weight: 500;
        }

        /* Status Badges */
        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .status-deactive {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }

        /* Facility Cards */
        .facility-card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .facility-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .facility-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .facility-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .facility-amount {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .facility-details {
            color: #666;
            font-size: 14px;
        }

        /* Progress Bar */
        .progress-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .progress {
            height: 10px;
            border-radius: 5px;
            background: #e9ecef;
        }

        .progress-bar {
            background: var(--primary-gradient);
            border-radius: 5px;
        }

        /* Attendance Stats */
        .attendance-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Attendance Table */
        .attendance-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .attendance-table .table {
            margin-bottom: 0;
        }

        .attendance-table th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .attendance-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .attendance-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-present {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .status-absent {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .status-late {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
                padding-top: 60px;
            }

            .content-wrapper.minimized {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .inner-layout {
                flex-direction: column;
            }

            .inner-sidebar {
                width: 100%;
                position: static;
            }

            .details-area {
                margin-top: 20px;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .attendance-stats {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .content-wrapper {
                margin-left: 250px;
                width: calc(100% - 250px);
            }

            .content-wrapper.minimized {
                margin-left: 60px;
                width: calc(100% - 60px);
            }
        }

        @media (min-width: 1025px) {
            .content-wrapper {
                margin-left: 250px;
                width: calc(100% - 250px);
            }

            .content-wrapper.minimized {
                margin-left: 60px;
                width: calc(100% - 60px);
            }
        }

        /* Back Button */
        .back-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 64, 64, 0.3);
            color: white;
            text-decoration: none;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-edit {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.3);
            color: white;
        }

        .btn-renew {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-renew:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
            color: white;
        }

        /* Admission Type Badges */
        .admission-type-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 10px;
        }

        .admission-new {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .admission-renewal {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }

        .admission-re {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }

        /* Section Subtitles */
        .section-subtitle {
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            align-items: center;
        }

        /* Admission History */
        .admission-history {
            margin-top: 30px;
        }

        .admission-history-item {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-color);
        }

        .admission-history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .admission-period {
            color: #666;
            font-weight: 500;
            font-size: 14px;
        }

        /* Batch History */
        .batch-history {
            margin-top: 30px;
        }

        .batch-history-item {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }

        .batch-history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .batch-period {
            color: #666;
            font-weight: 500;
            font-size: 14px;
        }

        /* Facilities History */
        .facilities-history {
            margin-top: 30px;
        }

        .facility-history-item {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        .facility-history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .facility-period {
            color: #666;
            font-weight: 500;
            font-size: 14px;
        }

        /* Status Badge Updates */
        .status-deactive {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {

            .admission-history-header,
            .batch-history-header,
            .facility-history-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .admission-type-badge {
                margin-left: 0;
                margin-top: 5px;
            }
        }

        .modal {
            z-index: 2000;
            /* higher than navbar/sidebar */
        }

        .modal-backdrop {
            z-index: 1999;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php $this->load->view('superadmin/Include/Sidebar') ?>

    <!-- Navbar -->
    <?php $this->load->view('superadmin/Include/Navbar') ?>

    <!-- Main Content -->
    <div class="content-wrapper" id="contentWrapper">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">
                            <i class="fas fa-user-graduate me-2"></i>
                            Student Details
                        </h2>
                        <a href="<?php echo base_url('superadmin/Students'); ?>" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            Back to Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Student Details Layout -->
            <div class="inner-layout">
                <!-- Inner Sidebar -->
                <div class="inner-sidebar">
                    <a href="#" class="menu-item active" onclick="showSection(event, 'personalDetails')">
                        <i class="fas fa-user"></i>
                        Personal Details
                    </a>
                    <a href="#" class="menu-item" onclick="showSection(event, 'admissionDetails')">
                        <i class="fas fa-file-alt"></i>
                        Admission Details
                    </a>
                    <a href="#" class="menu-item" onclick="showSection(event, 'batchDetails')">
                        <i class="fas fa-users"></i>
                        Batch Details
                    </a>
                    <a href="#" class="menu-item" onclick="showSection(event, 'feesDetails')">
                        <i class="fas fa-money-bill-wave"></i>
                        Fees Details
                    </a>
                    <a href="#" class="menu-item" onclick="showSection(event, 'facilities')">
                        <i class="fas fa-building"></i>
                        Facilities
                    </a>
                    <a href="#" class="menu-item" onclick="showSection(event, 'attendance')">
                        <i class="fas fa-calendar-check"></i>
                        Attendance
                    </a>
                </div>

                <!-- Details Area -->
                <div class="details-area">
                    <!-- Personal Details Section -->
                    <div class="section-content active" id="personalDetails">
                        <h4><i class="fas fa-user me-2"></i>Personal Details</h4>
                        <p>Complete personal information and contact details of the student.</p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-user"></i>
                                        Student Name
                                    </div>
                                    <div class="detail-value"><?= $student['name'] ?></div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-user-friends"></i>
                                        Parent Name
                                    </div>
                                    <div class="detail-value"><?= $student['parent_name'] ?></div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-envelope"></i>
                                        Email Address
                                    </div>
                                    <div class="detail-value"><?= $student['email'] ?></div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-calendar"></i>
                                        Date of Birth
                                    </div>
                                    <div class="detail-value"><?= date('d M Y', strtotime($student['dob'])) ?></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-phone"></i>
                                        Contact Number
                                    </div>
                                    <div class="detail-value"><?= $student['contact'] ?></div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-phone-alt"></i>
                                        Emergency Contact
                                    </div>
                                    <div class="detail-value"><?= $student['emergency_contact'] ?></div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-home"></i>
                                        Address
                                    </div>
                                    <div class="detail-value"><?= $student['address'] ?></div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-info-circle"></i>
                                        Status
                                    </div>
                                    <div class="detail-value">
                                        <span
                                            class="status-badge <?= strtolower($student['status']) == 'active' ? 'status-active' : 'status-inactive' ?>">
                                            <?= $student['status'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Navigation Buttons -->
                        <div class="nav-buttons">
                            <button class="nav-btn" onclick="previousSection()" id="prevBtn">
                                <i class="fas fa-chevron-left"></i>
                                Previous
                            </button>
                            <div class="progress-indicator">
                                <span id="stepCounter">Step 1 of 6</span>
                                <div class="progress-dots">
                                    <div class="progress-dot active" data-step="1"></div>
                                    <div class="progress-dot" data-step="2"></div>
                                    <div class="progress-dot" data-step="3"></div>
                                    <div class="progress-dot" data-step="4"></div>
                                    <div class="progress-dot" data-step="5"></div>
                                    <div class="progress-dot" data-step="6"></div>
                                </div>
                                <button class="nav-btn" onclick="nextSection()" id="nextBtn">
                                    Next
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>

                        </div>
                    </div>





                    <!-- Admission Details Section -->
                    <div class="section-content" id="admissionDetails">
                        <h4><i class="fas fa-file-alt me-2"></i>Admission Details</h4>
                        <p>Information about the student's admission process and enrollment details.</p>

                        <!-- Current Admission -->
                        <div class="admission-section">
                            <h5 class="section-subtitle">
                                <i class="fas fa-star me-2"></i>Current Admission
                                <span class="admission-type-badge admission-renewal">
                                    <?= $student['student_progress_category'] == 'Renewal' ? 'Renewal Admission' : 'New Admission'; ?>
                                </span>
                            </h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-calendar-plus"></i>
                                            Admission Date
                                        </div>
                                        <div class="detail-value">
                                            <?= date('d M Y', strtotime($student['admission_date'])) ?>
                                        </div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-calendar-check"></i>
                                            Joining Date
                                        </div>
                                        <div class="detail-value">
                                            <?= date('d M Y', strtotime($student['joining_date'])) ?>
                                        </div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-clock"></i>
                                            Duration
                                        </div>
                                        <div class="detail-value">
                                            <?= $student['duration'] ?> months
                                        </div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-calendar-times"></i>
                                            Expiry Date
                                        </div>
                                        <div class="detail-value">
                                            <?= date('d M Y', strtotime($student['joining_date'] . ' +' . $student['duration'] . ' months')) ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-signal"></i>
                                            Level/Category
                                        </div>
                                        <div class="detail-value">
                                            <?= $student['student_progress_category'] ?>
                                        </div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-credit-card"></i>
                                            Payment Method
                                        </div>
                                        <div class="detail-value">
                                            <?= $student['payment_method'] ?>
                                        </div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-calendar-alt"></i>
                                            Last Attendance
                                        </div>
                                        <div class="detail-value">
                                            <?= $student['last_attendance'] ? date('d M Y', strtotime($student['last_attendance'])) : 'N/A'; ?>
                                        </div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-clock"></i>
                                            Session Duration
                                        </div>
                                        <div class="detail-value">
                                            <?= $student['course_duration'] ?> hours per session
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Previous Admissions -->
                        <div class="admission-history">
                            <h5 class="section-subtitle">
                                <i class="fas fa-history me-2"></i>Previous Admissions
                            </h5>

                            <?php foreach ($student_history as $student_addmission_data): ?>

                                <div class="admission-history-item">
                                    <div class="admission-history-header">
                                        <span
                                            class="admission-type-badge admission-new"><?php print_r($student_addmission_data["purpose"]) ?></span>
                                        <span class="admission-period">
                                            <?php print_r($student_addmission_data["joining_date"]) ?> </span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-calendar-plus"></i>
                                                    Admission Date
                                                </div>
                                                <div class="detail-value">10 July 2023</div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-signal"></i>
                                                    Level/Category
                                                </div>
                                                <div class="detail-value">
                                                    <?php print_r($student_addmission_data["student_progress_category"]) ?>
                                                </div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-rupee-sign"></i>
                                                    Course Fees
                                                </div>
                                                <div class="detail-value">
                                                    <?php print_r($student_addmission_data["course_fees"]) ?> </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-calendar-check"></i>
                                                    Joining Date
                                                </div>
                                                <div class="detail-value">
                                                    <?php print_r($student_addmission_data["joining_date"]) ?> </div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-clock"></i>
                                                    Duration
                                                </div>
                                                <div class="detail-value">
                                                    <?php print_r($student_addmission_data["course_duration"]) ?> months
                                                </div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-check-circle"></i>
                                                    Completion Status
                                                </div>
                                                <div class="detail-value">
                                                    <span class="status-badge status-active">Completed</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>

                            <!-- <div class="admission-history-item">
                                <div class="admission-history-header">
                                    <span class="admission-type-badge admission-re">Re-Admission</span>
                                    <span class="admission-period">January 2023 - June 2023</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-calendar-plus"></i>
                                                Admission Date
                                            </div>
                                            <div class="detail-value">5 January 2023</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-signal"></i>
                                                Level/Category
                                            </div>
                                            <div class="detail-value">Beginner</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-rupee-sign"></i>
                                                Course Fees
                                            </div>
                                            <div class="detail-value">₹5,500</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-calendar-check"></i>
                                                Joining Date
                                            </div>
                                            <div class="detail-value">10 January 2023</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-clock"></i>
                                                Duration
                                            </div>
                                            <div class="detail-value">6 months</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-check-circle"></i>
                                                Completion Status
                                            </div>
                                            <div class="detail-value">
                                                <span class="status-badge status-active">Completed</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="nav-buttons">
                            <button class="nav-btn" onclick="previousSection()" id="prevBtn2">
                                <i class="fas fa-chevron-left"></i>
                                Previous
                            </button>
                            <div class="progress-indicator">
                                <span id="stepCounter2">Step 2 of 6</span>
                                <div class="progress-dots">
                                    <div class="progress-dot" data-step="1"></div>
                                    <div class="progress-dot active" data-step="2"></div>
                                    <div class="progress-dot" data-step="3"></div>
                                    <div class="progress-dot" data-step="4"></div>
                                    <div class="progress-dot" data-step="5"></div>
                                    <div class="progress-dot" data-step="6"></div>
                                </div>
                            </div>
                            <button class="nav-btn" onclick="nextSection()" id="nextBtn2">
                                Next
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Batch Details Section -->
                    <div class="section-content" id="batchDetails">
                        <h4><i class="fas fa-users me-2"></i>Batch Details</h4>
                        <p>Information about the student's current batch and training schedule.</p>

                        <!-- Current Batch -->
                        <div class="batch-section">
                            <h5 class="section-subtitle">
                                <i class="fas fa-star me-2"></i>Current Batch
                            </h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-university"></i>
                                            Center
                                        </div>
                                        <div class="detail-value">Mumbai Central Branch</div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-users"></i>
                                            Batch
                                        </div>
                                        <div class="detail-value">Morning Batch - Group A</div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-clock"></i>
                                            Batch Time
                                        </div>
                                        <div class="detail-value">6:00 AM - 7:30 AM</div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-calendar-week"></i>
                                            Training Days
                                        </div>
                                        <div class="detail-value">Monday, Wednesday, Friday</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                            Coach
                                        </div>
                                        <div class="detail-value">Mr. Vikram Singh</div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-user-tie"></i>
                                            Coordinator
                                        </div>
                                        <div class="detail-value">Mrs. Sunita Patel</div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-phone"></i>
                                            Coordinator Phone
                                        </div>
                                        <div class="detail-value">+91 98765 43212</div>
                                    </div>

                                    <div class="detail-row">
                                        <div class="detail-label">
                                            <i class="fas fa-map-marker-alt"></i>
                                            Training Venue
                                        </div>
                                        <div class="detail-value">Court 1, Ground Floor</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Previous Batches -->
                        <div class="batch-history">
                            <h5 class="section-subtitle">
                                <i class="fas fa-history me-2"></i>Previous Batches
                            </h5>

                            <?php foreach ($student_history_batch as $student_addmission_batch_data): ?>

                                <div class="batch-history-item">
                                    <div class="batch-history-header">
                                        <span
                                            class="batch-period"><?php print_r($student_addmission_batch_data["start_date"]) ?></span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-university"></i>
                                                    Center
                                                </div>
                                                <div class="detail-value">
                                                    <?php print_r($student_addmission_batch_data["center_name"]) ?></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-users"></i>
                                                    Batch
                                                </div>
                                                <div class="detail-value">
                                                    <?php print_r($student_addmission_batch_data["batch_name"]) ?></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-clock"></i>
                                                    Batch Time
                                                </div>
                                                <div class="detail-value">
                                                    <?php print_r($student_addmission_batch_data["start_time"]) ?> -
                                                    <?php print_r($student_addmission_batch_data["end_time"]) ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                    Coach
                                                </div>
                                                <div class="detail-value">
                                                    <?php print_r($student_addmission_batch_data["coach"]) ?></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-calendar-week"></i>
                                                    Training Days
                                                </div>
                                                <div class="detail-value">Tuesday, Thursday, Saturday</div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">
                                                    <i class="fas fa-check-circle"></i>
                                                    Status
                                                </div>
                                                <div class="detail-value">
                                                    <span class="status-badge status-active">Completed</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>

                            <div class="batch-history-item">
                                <div class="batch-history-header">
                                    <span class="batch-period">January 2023 - June 2023</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-university"></i>
                                                Center
                                            </div>
                                            <div class="detail-value">Mumbai West Branch</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-users"></i>
                                                Batch
                                            </div>
                                            <div class="detail-value">Morning Batch - Group C</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-clock"></i>
                                                Batch Time
                                            </div>
                                            <div class="detail-value">7:00 AM - 8:30 AM</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                                Coach
                                            </div>
                                            <div class="detail-value">Ms. Priya Sharma</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-calendar-week"></i>
                                                Training Days
                                            </div>
                                            <div class="detail-value">Monday, Wednesday, Friday</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="fas fa-check-circle"></i>
                                                Status
                                            </div>
                                            <div class="detail-value">
                                                <span class="status-badge status-active">Completed</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="nav-buttons">
                            <button class="nav-btn" onclick="previousSection()" id="prevBtn3">
                                <i class="fas fa-chevron-left"></i>
                                Previous
                            </button>
                            <div class="progress-indicator">
                                <span id="stepCounter3">Step 3 of 6</span>
                                <div class="progress-dots">
                                    <div class="progress-dot" data-step="1"></div>
                                    <div class="progress-dot" data-step="2"></div>
                                    <div class="progress-dot active" data-step="3"></div>
                                    <div class="progress-dot" data-step="4"></div>
                                    <div class="progress-dot" data-step="5"></div>
                                    <div class="progress-dot" data-step="6"></div>
                                </div>
                            </div>
                            <button class="nav-btn" onclick="nextSection()" id="nextBtn3">
                                Next
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Fees Details Section -->
                    <div class="section-content" id="feesDetails">
                        <h4><i class="fas fa-money-bill-wave me-2"></i>Fees Details</h4>
                        <p>Complete breakdown of fees, payments, and outstanding amounts.</p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-rupee-sign"></i>
                                        Course Fees
                                    </div>
                                    <div class="detail-value">
                                        ₹<?= number_format($student['course_fees'], 2) ?>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-plus-circle"></i>
                                        Additional Fees
                                    </div>
                                    <div class="detail-value">
                                        ₹<?= number_format($student['additional_fees'], 2) ?>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-calculator"></i>
                                        Total Fees
                                    </div>
                                    <div class="detail-value">
                                        ₹<?= number_format($student['total_fees'], 2) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-check-circle"></i>
                                        Amount Paid
                                    </div>
                                    <div class="detail-value">
                                        ₹<?= number_format($student['paid_amount'], 2) ?>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Remaining Amount
                                    </div>
                                    <div class="detail-value">
                                        ₹<?= number_format($student['remaining_amount'], 2) ?>
                                    </div>
                                </div>

                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-percentage"></i>
                                        Payment Progress
                                    </div>
                                    <div class="detail-value">
                                        <?php
                                        $progress = 0;
                                        if ($student['total_fees'] > 0) {
                                            $progress = ($student['paid_amount'] / $student['total_fees']) * 100;
                                        }
                                        ?>
                                        <div class="detail-value">
                                            <strong><?= round($progress) ?>%</strong>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Navigation Buttons -->
                        <div class="nav-buttons">
                            <button class="nav-btn" onclick="previousSection()" id="prevBtn4">
                                <i class="fas fa-chevron-left"></i>
                                Previous
                            </button>
                            <div class="progress-indicator">
                                <span id="stepCounter4">Step 4 of 6</span>
                                <div class="progress-dots">
                                    <div class="progress-dot" data-step="1"></div>
                                    <div class="progress-dot" data-step="2"></div>
                                    <div class="progress-dot" data-step="3"></div>
                                    <div class="progress-dot active" data-step="4"></div>
                                    <div class="progress-dot" data-step="5"></div>
                                    <div class="progress-dot" data-step="6"></div>
                                </div>
                            </div>
                            <button class="nav-btn" onclick="nextSection()" id="nextBtn4">
                                Next
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Facilities Section -->
                    <div class="section-content" id="facilities">
                        <h4><i class="fas fa-building me-2"></i>Facilities</h4>
                        <p>Additional facilities and services availed by the student.</p>

                        <div class="section-content" id="facilities">
                            <h4><i class="fas fa-building me-2"></i>Facilities</h4>
                            <p>Additional facilities and services availed by the student.</p>

                            <!-- Current Facilities -->
                            <div class="facilities-section">
                                <h5 class="section-subtitle">
                                    <i class="fas fa-star me-2"></i>Current Facilities
                                </h5>

                                <div class="row">
                                    <?php if (!empty($facilities)): ?>
                                        <?php foreach ($facilities as $facility): ?>
                                            <div class="col-md-6">
                                                <div class="facility-card">
                                                    <div class="facility-header d-flex justify-content-between">
                                                        <div class="facility-name">
                                                            <i class="fas fa-check me-2"></i>
                                                            <?= ucfirst($facility['facility_name']) ?>
                                                            <?= $facility['subtype_name'] ? '(' . $facility['subtype_name'] . ')' : '' ?>
                                                        </div>
                                                        <div class="facility-amount">
                                                            ₹<?= number_format($facility['rent_amount'], 2) ?></div>
                                                    </div>
                                                    <div class="facility-details">
                                                        <strong>Details:</strong>
                                                        <?= $facility['subtype_name'] ?: 'Standard' ?><br>
                                                        <strong>Duration:</strong>
                                                        <?= $facility['rent_date'] ? date('d M Y', strtotime($facility['rent_date'])) : 'N/A' ?><br>
                                                        <strong>Status:</strong> <span
                                                            class="status-badge status-active">Active</span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <p>No facilities availed by this student.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>


                        <!-- Previous Facilities -->
                        <div class="facilities-history">
                            <h5 class="section-subtitle">
                                <i class="fas fa-history me-2"></i>Previous Facilities
                            </h5>

                            <div class="facility-history-item">
                                <div class="facility-history-header">
                                    <span class="facility-period">July 2023 - December 2023</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="facility-card">
                                            <div class="facility-header">
                                                <div class="facility-name">
                                                    <i class="fas fa-lock me-2"></i>Locker Facility
                                                </div>
                                                <div class="facility-amount">₹400</div>
                                            </div>
                                            <div class="facility-details">
                                                <strong>Details:</strong> Small locker (Locker No: B-12)<br>
                                                <strong>Duration:</strong> 6 months<br>
                                                <strong>Status:</strong> <span
                                                    class="status-badge status-deactive">Expired</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="facility-card">
                                            <div class="facility-header">
                                                <div class="facility-name">
                                                    <i class="fas fa-tshirt me-2"></i>Sports Kit
                                                </div>
                                                <div class="facility-amount">₹800</div>
                                            </div>
                                            <div class="facility-details">
                                                <strong>Details:</strong> Complete sports kit<br>
                                                <strong>Duration:</strong> 6 months<br>
                                                <strong>Status:</strong> <span
                                                    class="status-badge status-deactive">Expired</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="facility-history-item">
                                <div class="facility-history-header">
                                    <span class="facility-period">January 2023 - June 2023</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="facility-card">
                                            <div class="facility-header">
                                                <div class="facility-name">
                                                    <i class="fas fa-table-tennis me-2"></i>Racket Rental
                                                </div>
                                                <div class="facility-amount">₹250</div>
                                            </div>
                                            <div class="facility-details">
                                                <strong>Details:</strong> Basic racket<br>
                                                <strong>Duration:</strong> 6 months<br>
                                                <strong>Status:</strong> <span
                                                    class="status-badge status-deactive">Expired</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="facility-card">
                                            <div class="facility-header">
                                                <div class="facility-name">
                                                    <i class="fas fa-shoe-prints me-2"></i>Shoe Rental
                                                </div>
                                                <div class="facility-amount">₹200</div>
                                            </div>
                                            <div class="facility-details">
                                                <strong>Details:</strong> Sports shoes<br>
                                                <strong>Duration:</strong> 3 months<br>
                                                <strong>Status:</strong> <span
                                                    class="status-badge status-deactive">Expired</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="nav-buttons">
                            <button class="nav-btn" onclick="previousSection()" id="prevBtn5">
                                <i class="fas fa-chevron-left"></i>
                                Previous
                            </button>
                            <div class="progress-indicator">
                                <span id="stepCounter5">Step 5 of 6</span>
                                <div class="progress-dots">
                                    <div class="progress-dot" data-step="1"></div>
                                    <div class="progress-dot" data-step="2"></div>
                                    <div class="progress-dot" data-step="3"></div>
                                    <div class="progress-dot" data-step="4"></div>
                                    <div class="progress-dot active" data-step="5"></div>
                                    <div class="progress-dot" data-step="6"></div>
                                </div>
                            </div>
                            <button class="nav-btn" onclick="nextSection()" id="nextBtn5">
                                Next
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Attendance Section -->
                    <div class="section-content" id="attendance">
                        <h4><i class="fas fa-calendar-check me-2"></i>Attendance</h4>
                        <p>Student's attendance records and statistics.</p>

                        <!-- Attendance Statistics -->
                        <div class="attendance-stats">
                            <div class="stat-card">
                                <div class="stat-number">85%</div>
                                <div class="stat-label">Overall Attendance</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">24</div>
                                <div class="stat-label">Sessions Attended</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">4</div>
                                <div class="stat-label">Sessions Missed</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">2</div>
                                <div class="stat-label">Late Arrivals</div>
                            </div>
                        </div>

                        <!-- Attendance Table -->
                        <div class="attendance-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Coach</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>15 March 2024</td>
                                        <td>Friday</td>
                                        <td>6:00 AM - 7:30 AM</td>
                                        <td><span class="attendance-status status-present">Present</span></td>
                                        <td>Mr. Vikram Singh</td>
                                        <td>Good performance</td>
                                    </tr>
                                    <tr>
                                        <td>13 March 2024</td>
                                        <td>Wednesday</td>
                                        <td>6:00 AM - 7:30 AM</td>
                                        <td><span class="attendance-status status-present">Present</span></td>
                                        <td>Mr. Vikram Singh</td>
                                        <td>Improved technique</td>
                                    </tr>
                                    <tr>
                                        <td>11 March 2024</td>
                                        <td>Monday</td>
                                        <td>6:00 AM - 7:30 AM</td>
                                        <td><span class="attendance-status status-late">Late</span></td>
                                        <td>Mr. Vikram Singh</td>
                                        <td>Arrived 15 mins late</td>
                                    </tr>
                                    <tr>
                                        <td>8 March 2024</td>
                                        <td>Friday</td>
                                        <td>6:00 AM - 7:30 AM</td>
                                        <td><span class="attendance-status status-absent">Absent</span></td>
                                        <td>Mr. Vikram Singh</td>
                                        <td>No prior notice</td>
                                    </tr>
                                    <tr>
                                        <td>6 March 2024</td>
                                        <td>Wednesday</td>
                                        <td>6:00 AM - 7:30 AM</td>
                                        <td><span class="attendance-status status-present">Present</span></td>
                                        <td>Mr. Vikram Singh</td>
                                        <td>Excellent progress</td>
                                    </tr>
                                    <tr>
                                        <td>4 March 2024</td>
                                        <td>Monday</td>
                                        <td>6:00 AM - 7:30 AM</td>
                                        <td><span class="attendance-status status-present">Present</span></td>
                                        <td>Mr. Vikram Singh</td>
                                        <td>Regular attendance</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Attendance Link -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-link"></i>
                                        Attendance Link
                                    </div>
                                    <div class="detail-value">
                                        <a href="#"
                                            class="text-primary"><?php print_r($student["attendace_link"]) ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">
                                        <i class="fas fa-qrcode"></i>
                                        QR Code
                                    </div>
                                    <div class="detail-value">
                                        <i class="fas fa-qrcode fa-2x text-muted"></i>
                                        <small class="d-block text-muted">Scan for attendance</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="nav-buttons">
                            <button class="nav-btn" onclick="previousSection()" id="prevBtn6">
                                <i class="fas fa-chevron-left"></i>
                                Previous
                            </button>
                            <div class="progress-indicator">
                                <span id="stepCounter6">Step 6 of 6</span>
                                <div class="progress-dots">
                                    <div class="progress-dot" data-step="1"></div>
                                    <div class="progress-dot" data-step="2"></div>
                                    <div class="progress-dot" data-step="3"></div>
                                    <div class="progress-dot" data-step="4"></div>
                                    <div class="progress-dot" data-step="5"></div>
                                    <div class="progress-dot active" data-step="6"></div>
                                </div>
                            </div>
                            <button class="nav-btn" onclick="nextSection()" id="nextBtn6" disabled>
                                Next
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="action-buttons">
                        <button type="button" class="btn-edit" data-bs-toggle="modal"
                            data-bs-target="#editStudentModal">
                            <i class="fas fa-edit me-2"></i>
                            Edit Student
                        </button>
                        <button type="button" class="btn-renew" onclick="renewAdmission()">
                            <i class="fas fa-sync-alt me-2"></i>
                            Renew Admission
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->

        <!-- Edit Student Modal -->
        <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content shadow-lg rounded-3">

                    <!-- Header -->
                    <div class="modal-header bg-gradient bg-primary text-white">
                        <h5 class="modal-title" id="editStudentModalLabel">
                            <i class="fas fa-user-edit me-2"></i> Edit Student Details
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <form id="editStudentForm" novalidate>
                            <input type="hidden" id="studentId">

                            <!-- Student & Parent -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Student Name</label>
                                    <input type="text" class="form-control" id="studentName"
                                        placeholder="Enter full name" required>
                                    <div class="invalid-feedback">Please enter student name.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Parent Name</label>
                                    <input type="text" class="form-control" id="parentName"
                                        placeholder="Enter parent/guardian name" required>
                                    <div class="invalid-feedback">Please enter parent name.</div>
                                </div>
                            </div>

                            <!-- Email & Contact -->
                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="emailAddress"
                                        placeholder="example@email.com" required>
                                    <div class="invalid-feedback">Enter a valid email.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact Number</label>
                                    <input type="tel" class="form-control" id="contactNumber"
                                        placeholder="10-digit number" pattern="[0-9]{10}" required>
                                    <div class="invalid-feedback">Enter a valid 10-digit number.</div>
                                </div>
                            </div>

                            <!-- Fees -->
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Course Fees</label>
                                    <input type="number" class="form-control" id="courseFees"
                                        placeholder="Enter total fees" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Amount Paid</label>
                                    <input type="number" class="form-control" id="paidAmount"
                                        placeholder="Enter paid amount" min="0" required>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label">Remaining Amount</label>
                                    <input type="text" class="form-control fw-bold" id="remainingAmount" readonly>
                                    <small id="remainingHint" class="text-muted"></small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Payment Method</label>
                                    <select class="form-select" id="paymentMethod" required>
                                        <option value="">Select method</option>
                                        <option value="cash">Cash</option>
                                        <option value="card">Card</option>
                                        <option value="online">Online</option>
                                    </select>
                                    <div class="invalid-feedback">Please select payment method.</div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mt-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" id="address" rows="2" placeholder="Enter student address"
                                    required></textarea>
                                <div class="invalid-feedback">Please enter address.</div>
                            </div>

                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancel
                        </button>
                        <button class="btn btn-success" onclick="saveStudentChanges()">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Bootstrap 5 JS (needs Popper included) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Interactivity Script -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            (() => {
                'use strict';
                const form = document.getElementById('editStudentForm');
                const courseFees = document.getElementById('courseFees');
                const paidAmount = document.getElementById('paidAmount');
                const remainingAmount = document.getElementById('remainingAmount');
                const remainingHint = document.getElementById('remainingHint');

                function calculateRemaining() {
                    const fees = parseFloat(courseFees.value) || 0;
                    const paid = parseFloat(paidAmount.value) || 0;
                    const remaining = fees - paid;
                    remainingAmount.value = remaining >= 0 ? `₹ ${remaining}` : "Overpaid!";
                    remainingAmount.classList.toggle('is-invalid', remaining < 0);
                    remainingAmount.classList.toggle('text-danger', remaining < 0);
                    remainingAmount.classList.toggle('text-success', remaining >= 0);
                    remainingHint.textContent = remaining < 0 ? "⚠ Overpayment detected!" : "";
                }

                courseFees.addEventListener('input', calculateRemaining);
                paidAmount.addEventListener('input', calculateRemaining);

                // Form validation on save
                window.saveStudentChanges = function () {
                    if (!form.checkValidity()) {
                        form.classList.add('was-validated');
                        Swal.fire('Error', 'Please fix the highlighted errors.', 'error');
                        return;
                    }

                    Swal.fire({
                        title: 'Saving...',
                        text: 'Student details are being updated.',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        Swal.fire('Success!', 'Student details updated successfully.', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('editStudentModal')).hide();
                    });
                };
            })();
        </script>



        <!-- Bootstrap & jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
        <!-- SweetAlert2 -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

        <script>
            // Sections array for navigation
            const sections = [
                'personalDetails',
                'admissionDetails',
                'batchDetails',
                'feesDetails',
                'facilities',
                'attendance'
            ];

            let currentSectionIndex = 0;

            // Show section
            function showSection(event, sectionId) {
                event.preventDefault();

                // Find section index
                const sectionIndex = sections.indexOf(sectionId);
                if (sectionIndex !== -1) {
                    currentSectionIndex = sectionIndex;
                }

                // Hide all sections
                $('.section-content').removeClass('active');

                // Show selected section
                $('#' + sectionId).addClass('active');

                // Update menu items
                $('.menu-item').removeClass('active');
                $(event.target).addClass('active');

                // Update navigation buttons
                updateNavigationButtons();
            }

            // Next section
            function nextSection() {
                if (currentSectionIndex < sections.length - 1) {
                    currentSectionIndex++;
                    const nextSectionId = sections[currentSectionIndex];

                    // Hide all sections
                    $('.section-content').removeClass('active');

                    // Show next section
                    $('#' + nextSectionId).addClass('active');

                    // Update menu items
                    $('.menu-item').removeClass('active');
                    $('.menu-item').eq(currentSectionIndex).addClass('active');

                    // Update navigation buttons
                    updateNavigationButtons();
                }
            }

            // Previous section
            function previousSection() {
                if (currentSectionIndex > 0) {
                    currentSectionIndex--;
                    const prevSectionId = sections[currentSectionIndex];

                    // Hide all sections
                    $('.section-content').removeClass('active');

                    // Show previous section
                    $('#' + prevSectionId).addClass('active');

                    // Update menu items
                    $('.menu-item').removeClass('active');
                    $('.menu-item').eq(currentSectionIndex).addClass('active');

                    // Update navigation buttons
                    updateNavigationButtons();
                }
            }

            // Update navigation buttons
            function updateNavigationButtons() {
                // Update prev button
                if (currentSectionIndex === 0) {
                    $('#prevBtn, #prevBtn2, #prevBtn3, #prevBtn4, #prevBtn5, #prevBtn6').prop('disabled', true);
                } else {
                    $('#prevBtn, #prevBtn2, #prevBtn3, #prevBtn4, #prevBtn5, #prevBtn6').prop('disabled', false);
                }

                // Update next button
                if (currentSectionIndex === sections.length - 1) {
                    $('#nextBtn, #nextBtn2, #nextBtn3, #nextBtn4, #nextBtn5, #nextBtn6').prop('disabled', true);
                } else {
                    $('#nextBtn, #nextBtn2, #nextBtn3, #nextBtn4, #nextBtn5, #nextBtn6').prop('disabled', false);
                }

                // Update step counter
                const stepText = `Step ${currentSectionIndex + 1} of ${sections.length}`;
                $('#stepCounter, #stepCounter2, #stepCounter3, #stepCounter4, #stepCounter5, #stepCounter6').text(stepText);

                // Update progress dots
                $('.progress-dot').removeClass('active');
                $(`.progress-dot[data-step="${currentSectionIndex + 1}"]`).addClass('active');
            }

            // Save student changes
            function saveStudentChanges() {
                // Get form data
                const formData = {
                    studentName: $('#studentName').val(),
                    parentName: $('#parentName').val(),
                    emailAddress: $('#emailAddress').val(),
                    contactNumber: $('#contactNumber').val(),
                    dateOfBirth: $('#dateOfBirth').val(),
                    emergencyContact: $('#emergencyContact').val(),
                    address: $('#address').val(),
                    batchCenter: $('#batchCenter').val(),
                    batchTime: $('#batchTime').val(),
                    studentStatus: $('#studentStatus').val(),
                    courseFees: $('#courseFees').val()
                };

                // Validate form
                if (!formData.studentName || !formData.parentName || !formData.emailAddress || !formData.contactNumber) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill in all required fields.',
                        confirmButtonColor: '#ff4040'
                    });
                    return;
                }

                // Simulate API call
                Swal.fire({
                    title: 'Saving Changes...',
                    html: 'Please wait while we update the student details.',
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                }).then(() => {
                    // Success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Student Updated!',
                        text: 'Student details have been successfully updated.',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        // Hide modal
                        $('#editStudentModal').modal('hide');

                        // Update the displayed data (you can implement this based on your needs)
                        updateDisplayedData(formData);
                    });
                });
            }

            // Update displayed data after edit
            function updateDisplayedData(formData) {
                // This function would update the displayed data on the page
                // Implementation depends on your specific requirements
                console.log('Updated data:', formData);
            }

            // Renew admission
            function renewAdmission() {
                Swal.fire({
                    title: 'Renew Admission',
                    text: 'Are you sure you want to renew this student\'s admission?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Renew',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Simulate renewal process
                        Swal.fire({
                            title: 'Processing Renewal...',
                            html: 'Please wait while we process the admission renewal.',
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        }).then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Admission Renewed!',
                                text: 'Student admission has been successfully renewed.',
                                confirmButtonColor: '#28a745'
                            });
                        });
                    }
                });
            }

            // Initialize navigation buttons on page load
            $(document).ready(function () {
                updateNavigationButtons();
            });
        </script>








        <style>
            /* Navigation Buttons */
            .nav-buttons {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 30px;
                padding: 20px 0;
                border-top: 1px solid #e0e0e0;
            }

            .nav-btn {
                background: var(--primary-gradient);
                color: white;
                border: none;
                padding: 12px 25px;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
                text-decoration: none;
            }

            .nav-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(255, 64, 64, 0.3);
                color: white;
                text-decoration: none;
            }

            .nav-btn:disabled {
                background: #6c757d;
                cursor: not-allowed;
                transform: none;
                box-shadow: none;
            }

            .nav-btn:disabled:hover {
                transform: none;
                box-shadow: none;
            }

            /* Progress Indicator */
            .progress-indicator {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #666;
                font-weight: 500;
            }

            .progress-dots {
                display: flex;
                gap: 5px;
            }

            .progress-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: #e0e0e0;
                transition: all 0.3s ease;
            }

            .progress-dot.active {
                background: var(--primary-color);
            }

            /* Modal Styles */
            .modal-content {
                border-radius: 15px;
                border: none;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }

            .modal-header {
                background: var(--primary-gradient);
                color: white;
                border-radius: 15px 15px 0 0;
                border: none;
            }

            .modal-body {
                padding: 30px;
            }

            .form-label {
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
            }

            .form-control {
                border-radius: 8px;
                border: 1px solid #e0e0e0;
                padding: 12px 15px;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(255, 64, 64, 0.25);
            }

            .modal-footer {
                border-top: 1px solid #e0e0e0;
                padding: 20px 30px;
            }

            .btn-save {
                background: var(--primary-gradient);
                color: white;
                border: none;
                padding: 12px 25px;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-save:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(255, 64, 64, 0.3);
                color: white;
            }

            .btn-cancel {
                background: #6c757d;
                color: white;
                border: none;
                padding: 12px 25px;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-cancel:hover {
                background: #5a6268;
                color: white;
            }

            /* Responsive Design for Navigation */
            @media (max-width: 768px) {
                .nav-buttons {
                    flex-direction: column;
                    gap: 15px;
                }

                .progress-indicator {
                    order: -1;
                }
            }
        </style>
</body>

</html>