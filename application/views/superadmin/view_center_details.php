<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Center Details</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
  <style>
    :root {
      --primary-color: #007bff;
      --accent-color: #ff4d4f;
      --background-color: #f8fafc;
      --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      --card-hover-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      --button-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s ease;
    }
    body {
      background-color: var(--background-color) !important;
      margin: 0;
      font-family: 'Inter', sans-serif !important;
      overflow-x: hidden;
      min-height: 100vh;
    }
    .content-wrapper {
      margin-left: 250px;
      padding: 80px 20px 20px 20px;
      transition: var(--transition);
    }
    .content-wrapper.minimized {
      margin-left: 60px;
    }
    .inner-layout {
      display: flex;
      gap: 20px;
    }
    .inner-sidebar {
      width: 220px;
      padding: 15px 10px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      border-radius: 8px;
      background: linear-gradient(135deg, #ff4d4f 0%, #470000 100%) !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
      position: sticky;
      top: 90px;
      height: fit-content;
    }
    .inner-sidebar .menu-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px;
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 500;
      transition: var(--transition);
    }
    .inner-sidebar .menu-item i {
      color: #ffecec;
      font-size: 16px;
      transition: color 0.25s ease;
    }
    .inner-sidebar .menu-item:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateX(5px);
    }
    .inner-sidebar .menu-item:hover i {
      color: #fff;
    }
    .inner-sidebar .menu-item.active {
      background: #fff;
      color: #470000;
      font-weight: 600;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    }
    .inner-sidebar .menu-item.active i {
      color: #470000;
    }
    .details-area {
      flex: 1;
      background: #fff;
      padding: 25px;
      border-radius: 8px;
      box-shadow: var(--card-shadow);
    }
    .section-content {
      display: none;
    }
    .section-content.active {
      display: block;
    }
    h4 {
      font-weight: 600;
      font-size: 20px;
      color: #1a202c;
      margin-bottom: 15px;
    }
    .section-title {
      font-weight: 600;
      font-size: 16px;
      color: #2d3748;
      margin-bottom: 10px;
    }
    .center-card, .batch-card, .facility-card, .staff-card, .expense-card {
      background: #ffffff;
      border-radius: 12px;
      padding: 20px;
      border-left: 4px solid var(--accent-color);
      box-shadow: var(--card-shadow);
      margin-bottom: 20px;
      transition: var(--transition);
    }
    .center-card:hover, .batch-card:hover, .facility-card:hover, .staff-card:hover, .expense-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--card-hover-shadow);
    }
    .card-details p {
      margin: 8px 0;
      font-size: 0.9rem;
      color: #2d3748;
      line-height: 1.6;
    }
    .card-details p:first-child {
      font-size: 1.1rem;
      font-weight: 600;
    }
    .card-details p span {
      font-weight: 500;
      color: #1a202c;
    }
    .btn-primary {
      background: linear-gradient(135deg, #ff4040 0%, #470000 100%);
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 500;
      transition: var(--transition);
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #ff4040 0%, #470000 100%);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .btn-secondary {
      background: #e2e8f0;
      color: #4a5568;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 500;
      transition: var(--transition);
    }
    .btn-secondary:hover {
      background: #cbd5e0;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .btn-delete {
      background: linear-gradient(135deg, #ff4d4f, #470000);
      color: white;
      border: none;
      padding: 5px 12px;
      border-radius: 4px;
      font-size: 13px;
    }
    .btn-edit {
      background: linear-gradient(135deg, #ff4d4f, #470000);
      color: white;
      border: none;
      padding: 5px 12px;
      border-radius: 4px;
      font-size: 13px;
    }
    .btn-edit:hover {
      background: #300000;
      color: white;
    }
    .modal-content {
      border-radius: 12px;
      padding: 30px;
      border: 2px solid var(--primary-color);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
      animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
      from {
        transform: translateY(-20px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
    .modal-content h3 {
      text-align: center;
      font-weight: 600;
      margin-bottom: 20px;
      color: #1a202c;
    }
    .modal-close-btn {
      position: absolute;
      top: 15px;
      right: 15px;
      background: none;
      border: none;
      font-size: 1.5rem;
      color: #4a5568;
      cursor: pointer;
      transition: var(--transition);
    }
    .modal-close-btn:hover {
      color: var(--accent-color);
      transform: scale(1.1);
    }
    .form-group {
      margin-bottom: 1.5rem;
    }
    .form-group label {
      font-weight: 500;
      font-size: 0.95rem;
      color: #2d3748;
      margin-bottom: 10px;
      display: block;
    }
    .form-control {
      height: 44px;
      border-radius: 8px;
      font-size: 0.95rem;
      border: 1px solid #e2e8f0;
      transition: var(--transition);
    }
    .form-control:focus {
      border-color: var(--accent-color);
      box-shadow: 0 0 8px rgba(255, 77, 79, 0.2);
    }
    .form-control::placeholder {
      color: #a0aec0;
    }
    .form-group select.form-control {
      appearance: none;
      background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path fill="%234a5568" d="M7 10l5 5 5-5z"/></svg>');
      background-repeat: no-repeat;
      background-position: right 12px center;
      background-size: 14px;
    }
    .batch-entry, .facility-entry, .staff-entry, .expense-entry {
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      background: #f7fafc;
      position: relative;
    }
    .remove-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      padding: 5px 10px;
      font-size: 0.85rem;
      background: #e2e8f0;
      color: #4a5568;
      border-radius: 8px;
      transition: var(--transition);
    }
    .remove-btn:hover {
      background: #cbd5e0;
      transform: translateY(-2px);
    }
    @media (max-width: 768px) {
      .content-wrapper {
        margin-left: 0;
        padding: 60px 12px 12px;
      }
      .inner-layout {
        flex-direction: column;
      }
      .inner-sidebar {
        width: 100%;
        position: static;
      }
      .center-card, .batch-card, .facility-card, .staff-card, .expense-card {
        padding: 15px;
        font-size: 0.85rem;
      }
    }
    @media (min-width: 769px) and (max-width: 991px) {
      .content-wrapper {
        margin-left: 200px;
        padding: 70px 18px 18px;
      }
      .content-wrapper.minimized {
        margin-left: 60px;
      }
      .inner-sidebar {
        width: 200px;
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
    <div class="container">
      <div class="inner-layout">
        <!-- Inner Sidebar -->
        <div class="inner-sidebar">
          <a href="#" class="menu-item active" onclick="showSection(event, 'centerInfo')">
            <i class="fas fa-info-circle"></i> Center Info
          </a>
          <a href="#" class="menu-item" onclick="showSection(event, 'batchDetails')">
            <i class="fas fa-users"></i> Batch Details
          </a>
          <a href="#" class="menu-item" onclick="showSection(event, 'facilityDetails')">
            <i class="fas fa-couch"></i> Facility Details
          </a>
          <a href="#" class="menu-item" onclick="showSection(event, 'staffDetails')">
            <i class="fas fa-user-tie"></i> Staff Details
          </a>
          <a href="#" class="menu-item" onclick="showSection(event, 'expenseDetails')">
            <i class="fas fa-money-bill-wave"></i> Expense Details
          </a>
        </div>
        
        <!-- Details Area -->
        <div class="details-area">
          <!-- Section: Center Info -->
          <div class="section-content active" id="centerInfo">
            <h4>Center Information</h4>
            <div id="centerInfoCard"></div>
            <div class="text-right mt-4">
              <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Back to Centers
              </button>
            </div>
          </div>
          
          <!-- Section: Batch Details -->
          <div class="section-content" id="batchDetails">
            <h4>Batch Details</h4>
            <div id="batchCards"></div>
            <div class="text-right mt-4">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#batchModal">
                <i class="fas fa-plus"></i> Add Batch
              </button>
            </div>
          </div>
          
          <!-- Section: Facility Details -->
          <div class="section-content" id="facilityDetails">
            <h4>Facility Details</h4>
            <div id="facilityCards"></div>
            <div class="text-right mt-4">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#facilityModal">
                <i class="fas fa-plus"></i> Add Facility
              </button>
            </div>
          </div>
          
          <!-- Section: Staff Details -->
          <div class="section-content" id="staffDetails">
            <h4>Staff Details</h4>
            <div id="staffCards"></div>
            <div class="text-right mt-4">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staffModal">
                <i class="fas fa-plus"></i> Add Staff
              </button>
            </div>
          </div>
          
          <!-- Section: Expense Details -->
          <div class="section-content" id="expenseDetails">
            <h4>Expense Details</h4>
            <div id="expenseCards"></div>
            <div class="text-right mt-4">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#expenseModal">
                <i class="fas fa-plus"></i> Add Expense
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Center Modal -->
  <div class="modal fade" id="editCenterModal" tabindex="-1" role="dialog" aria-labelledby="editCenterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editCenterModalLabel">Edit Center</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editCenterForm">
            <input type="hidden" id="editCenterId">
            <div class="form-group">
              <label for="editName">Center Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editName" required>
            </div>
            <div class="form-group">
              <label for="editCenterNumber">Center Number <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editCenterNumber" required pattern="[a-zA-Z0-9@.]+">
            </div>
            <div class="form-group">
              <label for="editAddress">Address <span class="text-danger">*</span></label>
              <textarea class="form-control" id="editAddress" required></textarea>
            </div>
            <div class="form-group">
              <label for="editRentAmount">Rent Amount <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="editRentAmount" min="0" step="0.01" required>
            </div>
            <div class="form-group">
              <label for="editRentDate">Rent Paid Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="editRentDate" required>
            </div>
            <div class="form-group">
              <label for="editTimingFrom">Timing From <span class="text-danger">*</span></label>
              <input type="time" class="form-control" id="editTimingFrom" required>
            </div>
            <div class="form-group">
              <label for="editTimingTo">Timing To <span class="text-danger">*</span></label>
              <input type="time" class="form-control" id="editTimingTo" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="saveEditBtn" class="btn btn-primary" disabled>Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Batch Modal -->
  <div class="modal fade" id="batchModal" tabindex="-1" aria-labelledby="batchLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
        <h3 id="batchLabel">Add Batch</h3>
        <form id="batchForm">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="batch_name">Batch Name <span class="text-danger">*</span></label>
              <input type="text" id="batch_name" name="batch_name" class="form-control" required />
            </div>
            <div class="form-group col-md-6">
              <label for="batch_timing">Start Time <span class="text-danger">*</span></label>
              <input type="time" id="batch_timing" name="start_time" class="form-control" required />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="end_time">End Time <span class="text-danger">*</span></label>
              <input type="time" id="end_time" name="end_time" class="form-control" required />
            </div>
            <div class="form-group col-md-6">
              <label for="start_date">Start Date <span class="text-danger">*</span></label>
              <input type="date" id="start_date" name="start_date" class="form-control" required />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="end_date">End Date <span class="text-danger">*</span></label>
              <input type="date" id="end_date" name="end_date" class="form-control" required />
            </div>
            <div class="form-group col-md-6">
              <label for="batch_category">Category <span class="text-danger">*</span></label>
              <select id="batch_category" name="category" class="form-control" required>
                <option value="">Select Category</option>
                <option value="individual">Individual</option>
                <option value="group">Group</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="batch_level">Level <span class="text-danger">*</span></label>
              <select id="batch_level" name="batch_level" class="form-control" required>
                <option value="">Select Level</option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
              </select>
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="batchSubmitBtn" disabled>Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Batch Modal -->
  <div class="modal fade" id="editBatchModal" tabindex="-1" aria-labelledby="editBatchLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
        <h3 id="editBatchLabel">Edit Batch</h3>
        <form id="editBatchForm">
          <input type="hidden" id="editBatchId">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editBatchName">Batch Name <span class="text-danger">*</span></label>
              <input type="text" id="editBatchName" name="batch_name" class="form-control" required />
            </div>
            <div class="form-group col-md-6">
              <label for="editBatchTiming">Start Time <span class="text-danger">*</span></label>
              <input type="time" id="editBatchTiming" name="start_time" class="form-control" required />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editEndTime">End Time <span class="text-danger">*</span></label>
              <input type="time" id="editEndTime" name="end_time" class="form-control" required />
            </div>
            <div class="form-group col-md-6">
              <label for="editStartDate">Start Date <span class="text-danger">*</span></label>
              <input type="date" id="editStartDate" name="start_date" class="form-control" required />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editEndDate">End Date <span class="text-danger">*</span></label>
              <input type="date" id="editEndDate" name="end_date" class="form-control" required />
            </div>
            <div class="form-group col-md-6">
              <label for="editBatchCategory">Category <span class="text-danger">*</span></label>
              <select id="editBatchCategory" name="batch_category" class="form-control" required>
                <option value="">Select Category</option>
                <option value="individual">Individual</option>
                <option value="group">Group</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editBatchLevel">Level <span class="text-danger">*</span></label>
              <select id="editBatchLevel" name="batch_level" class="form-control" required>
                <option value="">Select Level</option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
              </select>
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="editBatchSubmitBtn" disabled>Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- Add Facility Modal -->
  <div class="modal fade" id="facilityModal" tabindex="-1" aria-labelledby="facilityLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
        <h3 id="facilityLabel">Add Facility</h3>
        <form id="facilityForm">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="facility_name">Facility Name <span class="text-danger">*</span></label>
              <select id="facility_name" name="facility_name" class="form-control" required>
                <option value="">Select Facility</option>
                <option value="Locker">Locker</option>
                <option value="Shoe">Shoe</option>
                <option value="Racket">Racket</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="subtype_name">Subtype Name <span class="text-danger">*</span></label>
              <input type="text" id="subtype_name" name="subtype_name" class="form-control" required placeholder="Enter Subtype Name" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="facility_rent">Rent <span class="text-danger">*</span></label>
              <input type="number" id="facility_rent" name="rent_amount" class="form-control" min="0" step="0.01" required placeholder="Enter Rent Amount" />
            </div>
            <div class="form-group col-md-6">
              <label for="facility_rent_date">Rent Date <span class="text-danger">*</span></label>
              <input type="date" id="facility_rent_date" name="rent_date" class="form-control" required />
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="facilitySubmitBtn" disabled>Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Facility Modal -->
  <div class="modal fade" id="editFacilityModal" tabindex="-1" aria-labelledby="editFacilityLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
        <h3 id="editFacilityLabel">Edit Facility</h3>
        <form id="editFacilityForm">
          <input type="hidden" id="editFacilityId">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editFacilityName">Facility Name <span class="text-danger">*</span></label>
              <select id="editFacilityName" name="facility_name" class="form-control" required>
                <option value="">Select Facility</option>
                <option value="Locker">Locker</option>
                <option value="Shoe">Shoe</option>
                <option value="Racket">Racket</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="editSubtypeName">Subtype Name <span class="text-danger">*</span></label>
              <input type="text" id="editSubtypeName" name="subtype_name" class="form-control" required placeholder="Enter Subtype Name" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editFacilityRent">Rent <span class="text-danger">*</span></label>
              <input type="number" id="editFacilityRent" name="facility_rent" class="form-control" min="0" step="0.01" required placeholder="Enter Rent Amount" />
            </div>
            <div class="form-group col-md-6">
              <label for="editFacilityRentDate">Rent Date <span class="text-danger">*</span></label>
              <input type="date" id="editFacilityRentDate" name="facility_rent_date" class="form-control" required />
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="editFacilitySubmitBtn" disabled>Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add Staff Modal -->
  <div class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="staffLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
        <h3 id="staffLabel">Add Staff</h3>
        <form id="staffForm">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="staff_name">Staff Name</label>
              <input type="text" id="staff_name" name="staff_name" class="form-control" placeholder="Enter Staff Name" />
            </div>
            <div class="form-group col-md-6">
              <label for="contact_no">Contact Number</label>
              <input type="text" id="contact_no" name="contact_no" class="form-control" placeholder="Enter Contact Number" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="staff_role">Role <span class="text-danger">*</span></label>
              <select id="staff_role" name="staff_role" class="form-control" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="coach">Coach</option>
                <option value="co-ordinator">Co-ordinator</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="joining_date">Joining Date <span class="text-danger">*</span></label>
              <input type="date" id="joining_date" name="joining_date" class="form-control" required />
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="staffSubmitBtn" disabled>Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Staff Modal -->
  <div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
        <h3 id="editStaffLabel">Edit Staff</h3>
        <form id="editStaffForm">
          <input type="hidden" id="editStaffId">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editStaffName">Staff Name</label>
              <input type="text" id="editStaffName" name="staff_name" class="form-control" placeholder="Enter Staff Name" />
            </div>
            <div class="form-group col-md-6">
              <label for="editContactNo">Contact Number</label>
              <input type="text" id="editContactNo" name="contact_no" class="form-control" placeholder="Enter Contact Number" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editStaffRole">Role <span class="text-danger">*</span></label>
              <select id="editStaffRole" name="staff_role" class="form-control" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="coach">Coach</option>
                <option value="co-ordinator">Co-ordinator</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="editJoiningDate">Joining Date <span class="text-danger">*</span></label>
              <input type="date" id="editJoiningDate" name="joining_date" class="form-control" required />
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="editStaffSubmitBtn" disabled>Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add Expense Modal -->
  <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
        <h3 id="expenseLabel">Add Expense</h3>
        <form id="expenseForm"  method="post">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="expense_category">Category <span class="text-danger">*</span></label>
              <select id="expense_category" name="category" class="form-control" required>
                <option value="">Select Category</option>
                <option value="Electricity">Electricity</option>
                <option value="Water">Water</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="expense_amount">Amount <span class="text-danger">*</span></label>
              <input type="number" id="expense_amount" name="amount" class="form-control" min="0" step="0.01" required placeholder="Enter Amount" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="expense_date">Date <span class="text-danger">*</span></label>
              <input type="date" id="expense_date" name="date" class="form-control" required />
            </div>
            <div class="form-group col-md-6">
              <label for="expense_description">Description</label>
              <input type="text" id="expense_description" name="description" class="form-control" placeholder="Enter Description" />
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="expenseSubmitBtn" disabled>Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Expense Modal -->
  <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
        <h3 id="editExpenseLabel">Edit Expense</h3>
        <form id="editExpenseForm">
          <input type="hidden" id="editExpenseId">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editExpenseCategory">Category <span class="text-danger">*</span></label>
              <select id="editExpenseCategory" name="expense_category" class="form-control" required>
                <option value="">Select Category</option>
                <option value="Electricity">Electricity</option>
                <option value="Water">Water</option>
                <option value="Maintenance">Maintenance</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="editExpenseAmount">Amount <span class="text-danger">*</span></label>
              <input type="number" id="editExpenseAmount" name="expense_amount" class="form-control" min="0" step="0.01" required placeholder="Enter Amount" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="editExpenseDate">Date <span class="text-danger">*</span></label>
              <input type="date" id="editExpenseDate" name="expense_date" class="form-control" required />
            </div>
            <div class="form-group col-md-6">
              <label for="editExpenseDescription">Description</label>
              <input type="text" id="editExpenseDescription" name="expense_description" class="form-control" placeholder="Enter Description" />
            </div>
          </div>
          <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="editExpenseSubmitBtn" disabled>Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
$(document).ready(function() {
  const baseUrl = "<?php echo base_url(); ?>";
  const urlParams = new URLSearchParams(window.location.search);
  const centerId = urlParams.get('center_id');
  let centerData = null;

  // Validation function for forms
  function validateForm(formId, submitBtnId) {
    let isValid = true;
    const form = $(`#${formId}`);
    
    // Check required fields
    form.find('input[required], select[required], textarea[required]').each(function() {
      if ($(this).val().trim() === '') {
        isValid = false;
      }
    });

    // Check number inputs for negative values
    form.find('input[type="number"][min]').each(function() {
      if (parseFloat($(this).val()) < parseFloat($(this).attr('min'))) {
        isValid = false;
        Swal.fire({
          icon: 'error',
          title: 'Invalid Input',
          text: 'Value cannot be negative or below minimum.'
        });
      }
    });

    // Check time ranges for center and batch
    if (formId === 'editCenterForm' || formId === 'editBatchForm' || formId === 'batchForm') {
      const timeFrom = form.find('input[name="start_time"]').val();
      const timeTo = form.find('input[name="end_time"]').val();
      if (timeFrom && timeTo && timeFrom >= timeTo) {
        isValid = false;
        Swal.fire({
          icon: 'error',
          title: 'Invalid Time',
          text: 'Start time must be earlier than end time.'
        });
      }
    }

    // Check date ranges for batch
    if (formId === 'batchForm' || formId === 'editBatchForm') {
      const startDate = form.find('input[name="start_date"]').val();
      const endDate = form.find('input[name="end_date"]').val();
      if (startDate && endDate && startDate > endDate) {
        isValid = false;
        Swal.fire({
          icon: 'error',
          title: 'Invalid Date',
          text: 'Start date must be earlier than end date.'
        });
      }
    }

    $(`#${submitBtnId}`).prop('disabled', !isValid);
  }

  // Attach validation to all forms
  $('form input, form select, form textarea').on('input change', function() {
    const formId = $(this).closest('form').attr('id');
    let submitBtnId;
    switch (formId) {
      case 'editCenterForm': submitBtnId = 'saveEditBtn'; break;
      case 'batchForm': submitBtnId = 'batchSubmitBtn'; break;
      case 'editBatchForm': submitBtnId = 'editBatchSubmitBtn'; break;
      case 'facilityForm': submitBtnId = 'facilitySubmitBtn'; break;
      case 'editFacilityForm': submitBtnId = 'editFacilitySubmitBtn'; break;
      case 'staffForm': submitBtnId = 'staffSubmitBtn'; break;
      case 'editStaffForm': submitBtnId = 'editStaffSubmitBtn'; break;
      case 'expenseForm': submitBtnId = 'expenseSubmitBtn'; break;
      case 'editExpenseForm': submitBtnId = 'editExpenseSubmitBtn'; break;
    }
    if (submitBtnId) validateForm(formId, submitBtnId);
  });

  // Clear modal fields on close
  $('#editBatchModal').on('hidden.bs.modal', function () {
    $('#editBatchForm')[0].reset();
    $('#editBatchSubmitBtn').prop('disabled', true);
  });

  // Fetch Center Data
  function fetchCenterData() {
    if (!centerId) {
      $('#centerInfoCard').html('<p class="text-danger">Center ID missing in URL.</p>');
      return;
    }

    $.ajax({
      url: baseUrl + "Center/getCenterById/" + centerId,
      method: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status === "success") {
          centerData = response;
          loadCenterInfo();
          loadBatchDetails();
          loadFacilityDetails();
          loadStaffDetails();
          loadExpenseDetails();
        } else {
          $('#centerInfoCard').html('<p class="text-danger">Center not found.</p>');
        }
      },
      error: function(xhr, status, error) {
        console.error("API Error:", error);
        $('#centerInfoCard').html('<p class="text-danger">Error loading center data.</p>');
      }
    });
  }

  // Load Center Information
  function loadCenterInfo() {
    const center = centerData.center;
    const centerInfo = `
      <div class="center-card">
        <div class="card-details">
          <p>${center.name}</p>
          <p><span>Address:</span> ${center.address}</p>
          <p><span>Center Number:</span> ${center.center_number}</p>
          <p><span>Timing:</span> ${center.center_timing_from} - ${center.center_timing_to}</p>
          <p><span>Rent:</span> ₹${parseFloat(center.rent_amount).toFixed(2)}</p>
          <p><span>Rent Date:</span> ${center.rent_paid_date}</p>
          <button class="btn btn-edit" data-center-id="${center.id}">
            <i class="fas fa-edit"></i> Edit
          </button>
        </div>
      </div>`;
    $('#centerInfoCard').html(centerInfo);
  }

  // Load Batch Details
  function loadBatchDetails() {
    $('#batchCards').empty();
    $.ajax({
      url: baseUrl + "Center/getBatchesByCenter/" + centerId,
      method: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status === "success" && response.data && response.data.length > 0) {
          response.data.forEach(batch => {
            const batchCard = `
              <div class="batch-card">
                <div class="card-details">
                  <p>Batch ${batch.batch_name}</p>
                  <p><span>Timing:</span> ${batch.start_time} - ${batch.end_time}</p>
                  <p><span>Start Date:</span> ${batch.start_date}</p>
                  <p><span>End Date:</span> ${batch.end_date}</p>
                  <p><span>Level:</span> ${batch.batch_level}</p>
                  <p><span>Category:</span> ${batch.category}</p>
                  <button class="btn btn-edit" data-batch-id="${batch.id}">
                    <i class="fas fa-edit"></i> Edit
                  </button>
                  <button class="btn btn-delete" data-delete-batch-id="${batch.id}">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </div>
              </div>`;
            $('#batchCards').append(batchCard);
          });
        } else {
          $('#batchCards').html('<p class="text-muted">No batches found for this center.</p>');
        }
      },
      error: function(xhr, status, error) {
        console.error("API Error:", error);
        $('#batchCards').html('<p class="text-danger">Error loading batch data.</p>');
      }
    });
  }

  //  Batch Deleted
  $(document).on("click", ".btn-delete[data-delete-batch-id]", function () {
    const batchId = $(this).data("delete-batch-id");
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: baseUrl + "Center/deleteBatch/" + batchId,
          method: "POST",
          dataType: "json",
          success: function (response) {
            if (response.status === "success") {
              Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Batch deleted successfully.'
              });
              loadBatchDetails();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Failed to delete batch.'
              });
            }
          },
          error: function (xhr, status, error) {
            console.error("Delete Error:", error);
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Error deleting batch. Please try again.'
            });
          }
        });
      }
    });
  });

  // Load Facility Details
  function loadFacilityDetails() {
    const facilities = centerData.facilities;
    $('#facilityCards').empty();
    if (!facilities || facilities.length === 0) {
      $('#facilityCards').html('<p class="text-muted">No facilities found for this center.</p>');
      return;
    }
    facilities.forEach(facility => {
      const facilityCard = `
        <div class="facility-card">
          <div class="card-details">
            <p>${facility.facility_name}</p>
            <p><span>Subtype:</span> ${facility.subtype_name}</p>
            <p><span>Rent:</span> ₹${parseFloat(facility.rent_amount).toFixed(2)}</p>
            <p><span>Rent Date:</span> ${facility.rent_date}</p>
            <button class="btn btn-edit" data-facility-id="${facility.id}">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button class="btn btn-delete" data-delete-facility-id="${facility.id}">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </div>`;
      $('#facilityCards').append(facilityCard);
    });
  }
// Facility Delete
$(document).on("click", ".btn-delete[data-delete-facility-id]", function () {
  const facilityId = $(this).data("delete-facility-id");
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: baseUrl + "Facility/deleteFacilityById/" + facilityId,
        method: "POST",
        dataType: "json",
        success: function (response) {
          if (response.status === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Facility deleted successfully.'
            });
            loadFacilityDetails();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Failed to delete facility.'
            });
          }
        },
        error: function (xhr, status, error) {
          console.error("Delete Error:", error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error deleting facility. Please try again.'
          });
        }
      });
    }
  });
});





  // Load Staff Details
  function loadStaffDetails() {
    const staff = centerData.staff;
    $('#staffCards').empty();
    if (!staff || staff.length === 0) {
      $('#staffCards').html('<p class="text-muted">No staff found for this center.</p>');
      return;
    }
    staff.forEach(staffMember => {
      const staffCard = `
        <div class="staff-card">
          <div class="card-details">
            <p>${staffMember.staff_name}</p>
            <p><span>Role:</span> ${staffMember.role}</p>
            <p><span>Contact Number:</span> ${staffMember.contact_no}</p>
            <p><span>Joining Date:</span> ${staffMember.joining_date}</p>
            <button class="btn btn-edit" data-staff-id="${staffMember.id}">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button class="btn btn-delete" data-delete-staff-id="${staffMember.id}">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </div>`;
      $('#staffCards').append(staffCard);
    });
  }

  // Staff Deleted
$(document).on("click", ".btn-delete[data-delete-staff-id]", function () {
  const staffId = $(this).data("delete-staff-id");
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: baseUrl + "Staff/deleteStaff/" + staffId,
        method: "POST",
        dataType: "json",
        success: function (response) {
          if (response.status === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Staff deleted successfully.'
            });
            loadStaffDetails();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.message || 'Failed to delete staff.'
            });
          }
        },
        error: function (xhr, status, error) {
          console.error("Delete Error:", error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error deleting staff. Please try again.'
          });
        }
      });
    }
  });
});

  // Load Expense Details
  function loadExpenseDetails() {
    const expenses = centerData.expenses || [];
    $('#expenseCards').empty();
    if (!expenses || expenses.length === 0) {
      $('#expenseCards').html('<p class="text-muted">No expenses found for this center.</p>');
      return;
    }
    expenses.forEach(expense => {
      const expenseCard = `
        <div class="expense-card">
          <div class="card-details">
            <p>${expense.category}</p>
            <p><span>Amount:</span> ₹${parseFloat(expense.amount).toFixed(2)}</p>
            <p><span>Date:</span> ${expense.date}</p>
            ${expense.description ? `<p><span>Description:</span> ${expense.description}</p>` : ''}
            <button class="btn btn-edit" data-expense-id="${expense.id}">
              <i class="fas fa-edit"></i> Edit
            </button>
          </div>
        </div>`;
      $('#expenseCards').append(expenseCard);
    });
  }

  // Show Section Handler
  window.showSection = function(event, sectionId) {
    event.preventDefault();
    $('.section-content').removeClass('active');
    $('#' + sectionId).addClass('active');
    $('.menu-item').removeClass('active');
    $(event.currentTarget).addClass('active');
    if (sectionId === 'batchDetails') loadBatchDetails();
    else if (sectionId === 'facilityDetails') loadFacilityDetails();
    else if (sectionId === 'staffDetails') loadStaffDetails();
    else if (sectionId === 'expenseDetails') loadExpenseDetails();
  };

  // Edit Center Handler
  $(document).on('click', '.btn-edit[data-center-id]', function() {
    const centerId = $(this).data('center-id');
    $.ajax({
      url: baseUrl + "Center/getCenterById/" + centerId,
      method: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status === "success") {
          const c = response.center;
          $("#editCenterId").val(c.id);
          $("#editName").val(c.name);
          $("#editCenterNumber").val(c.center_number);
          $("#editAddress").val(c.address);
          $("#editRentAmount").val(parseFloat(c.rent_amount).toFixed(2));
          $("#editRentDate").val(c.rent_paid_date);
          $("#editTimingFrom").val(c.center_timing_from);
          $("#editTimingTo").val(c.center_timing_to);
          validateForm('editCenterForm', 'saveEditBtn');
          $("#editCenterModal").modal("show");
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load center data'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error fetching center data'
        });
      }
    });
  });

  // Save Center Changes
  $('#saveEditBtn').click(function() {
    const form = $('#editCenterForm');
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }
    const payload = {
      id: $('#editCenterId').val(),
      name: $('#editName').val(),
      center_number: $('#editCenterNumber').val(),
      address: $('#editAddress').val(),
      rent_amount: parseFloat($('#editRentAmount').val()).toFixed(2),
      rent_paid_date: $('#editRentDate').val(),
      center_timing_from: $('#editTimingFrom').val(),
      center_timing_to: $('#editTimingTo').val()
    };
    $.ajax({
      url: baseUrl + "Center/updateCenter",
      method: "POST",
      data: JSON.stringify(payload),
      contentType: "application/json",
      success: function(response) {
        if (response.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Center updated successfully'
          });
          $('#editCenterModal').modal('hide');
          fetchCenterData();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update center'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error updating center'
        });
      }
    });
  });

  // Edit Batch Handler (robust success/data handling)
  $(document).on('click', '.btn-edit[data-batch-id]', function() {
    const batchId = $(this).data('batch-id');
    console.log('Edit button clicked for batch ID:', batchId); // Debug log

    if (!batchId) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Invalid batch ID'
      });
      return;
    }

    $.ajax({
      url: baseUrl + "Center/getBatchById/" + batchId,
      method: "GET",
      dataType: "json",
      success: function(response) {
        console.log('AJAX Response:', response); // Debug log
        // Accept either boolean true or "success" or other truthy statuses
        const ok = (response && (response.status === true || response.status === "success" || response.status === "ok"));
        // Try common places where the batch object may be sent
        const b = (response && (response.data || response.batch || response)) ? (response.data || response.batch || response) : null;

        if (ok && b) {
          // If response.data contains wrapper keys, ensure we get actual object
          const batchObj = (b && b.id) ? b : (b.data || b.batch || b);
          const item = batchObj || b;

          $("#editBatchId").val(item.id || '');
          $("#editBatchName").val(item.batch_name || '');
          $("#editBatchTiming").val(item.start_time || '');
          $("#editEndTime").val(item.end_time || '');
          $("#editStartDate").val(item.start_date || '');
          $("#editEndDate").val(item.end_date || '');
          // Normalize batch_level casing to match select options (First letter uppercase)
          const lvl = item.batch_level || item.level || '';
          $("#editBatchCategory").val(item.category || '');
          $("#editBatchLevel").val(lvl ? (lvl.charAt(0).toUpperCase() + lvl.slice(1)) : '');
          validateForm('editBatchForm', 'editBatchSubmitBtn');
          $("#editBatchModal").modal("show");
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: response.message || 'Failed to load batch data'
          });
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX Error:', status, error, xhr && xhr.responseText); // Detailed error log
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error fetching batch data'
        });
      }
    });
  });

  // Add Batch Handler
  $('#batchSubmitBtn').click(function() {
    const form = $('#batchForm');
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }
    const payload = {
      center_id: centerId,
      batch_name: $('#batch_name').val(),
      batch_level: $('#batch_level').val(),
      start_time: $('#batch_timing').val(),
      end_time: $('#end_time').val(),
      start_date: $('#start_date').val(),
      end_date: $('#end_date').val(),
      category: $('#batch_category').val()
    };
    $.ajax({
      url: baseUrl + "Center/add_batch",
      method: "POST",
      data: JSON.stringify(payload),
      contentType: "application/json",
      success: function(response) {
        if (response.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Batch added successfully'
          });
          $('#batchModal').modal('hide');
          loadBatchDetails();
        } else {
          console.log('Add batch failed:', response.message || 'Unknown error');
          $('#batchModal').modal('hide');
          loadBatchDetails();
        }
      },
      error: function() {
        console.log('Error adding batch');
        $('#batchModal').modal('hide');
        loadBatchDetails();
      }
    });
  });

  // Save Batch Changes
  $('#editBatchSubmitBtn').click(function() {
    const form = $('#editBatchForm');
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }
    const payload = {
      id: $('#editBatchId').val(),
      batch_name: $('#editBatchName').val(),
      batch_level: $('#editBatchLevel').val(),
      start_time: $('#editBatchTiming').val(),
      end_time: $('#editEndTime').val(),
      start_date: $('#editStartDate').val(),
      end_date: $('#editEndDate').val(),
      category: $('#editBatchCategory').val()
    };
    $.ajax({
      url: baseUrl + "Center/update_Batch",
      method: "POST",
      data: JSON.stringify(payload),
      contentType: "application/json",
      success: function(response) {
        if (response.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Batch updated successfully'
          });
          $('#editBatchModal').modal('hide');
          loadBatchDetails();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update batch'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error updating batch'
        });
      }
    });
  });

  // Edit Facility Handler
  $(document).on('click', '.btn-edit[data-facility-id]', function() {
    const facilityId = $(this).data('facility-id');
    $.ajax({
      url: baseUrl + "Facility/getFacilityById/" + facilityId,
      method: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status === "success") {
          const f = response.facility;
          $("#editFacilityId").val(f.id);
          $("#editFacilityName").val(f.facility_name);
          $("#editSubtypeName").val(f.subtype_name);
          $("#editFacilityRent").val(parseFloat(f.rent_amount).toFixed(2));
          $("#editFacilityRentDate").val(f.rent_date);
          validateForm('editFacilityForm', 'editFacilitySubmitBtn');
          $("#editFacilityModal").modal("show");
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load facility data'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error fetching facility data'
        });
      }
    });
  });

  // Add Facility Handler
  $('#facilitySubmitBtn').click(function() {
    const form = $('#facilityForm');
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }
    const payload = {
      center_id: centerId,
      facility_name: $('#facility_name').val(),
      subtype_name: $('#subtype_name').val(),
      rent_amount: parseFloat($('#facility_rent').val()).toFixed(2),
      rent_date: $('#facility_rent_date').val()
    };
    $.ajax({
      url: baseUrl + "Facility/saveFacility",
      method: "POST",
      data: JSON.stringify(payload),
      contentType: "application/json",
      success: function(response) {
        if (response.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Facility added successfully'
          });
          $('#facilityModal').modal('hide');
          fetchCenterData();
        } else {
          console.log('Add facility failed:', response.message || 'Unknown error');
          $('#facilityModal').modal('hide');
          fetchCenterData();
        }
      },
      error: function() {
        console.log('Error adding facility');
        $('#facilityModal').modal('hide');
        fetchCenterData();
      }
    });
  });

  // Save Facility Changes
  $('#editFacilitySubmitBtn').click(function() {
    const form = $('#editFacilityForm');
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }
    const payload = {
      id: $('#editFacilityId').val(),
      facility_name: $('#editFacilityName').val(),
      subtype_name: $('#editSubtypeName').val(),
      rent_amount: parseFloat($('#editFacilityRent').val()).toFixed(2),
      rent_date: $('#editFacilityRentDate').val()
    };
    $.ajax({
      url: baseUrl + "Facility/updateFacility",
      method: "POST",
      data: JSON.stringify(payload),
      contentType: "application/json",
      success: function(response) {
        if (response.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Facility updated successfully'
          });
          $('#editFacilityModal').modal('hide');
          fetchCenterData();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update facility'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error updating facility'
        });
      }
    });
  });

  // Edit Staff Handler
  $(document).on('click', '.btn-edit[data-staff-id]', function() {
    const staffId = $(this).data('staff-id');
    $.ajax({
      url: baseUrl + "Staff/getStaffById/" + staffId,
      method: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status === "success") {
          const s = response.staff;
          $("#editStaffId").val(s.id);
          $("#editStaffName").val(s.staff_name);
          $("#editContactNo").val(s.contact_no);
          $("#editStaffRole").val(s.role);
          $("#editJoiningDate").val(s.joining_date);
          validateForm('editStaffForm', 'editStaffSubmitBtn');
          $("#editStaffModal").modal("show");
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load staff data'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error fetching staff data'
        });
      }
    });
  });

  // Add Staff Handler
  $('#staffSubmitBtn').click(function() {
    const form = $('#staffForm');
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }
    const payload = {
      center_id: centerId,
      staff_name: $('#staff_name').val(),
      contact_no: $('#contact_no').val(),
      role: $('#staff_role').val(),
      joining_date: $('#joining_date').val()
    };
    $.ajax({
      url: baseUrl + "Staff/addStaff",
      method: "POST",
      data: JSON.stringify(payload),
      contentType: "application/json",
      success: function(response) {
        if (response.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Staff added successfully'
          });
          $('#staffModal').modal('hide');
          fetchCenterData();
        } else {
          console.log('Add staff failed:', response.message || 'Unknown error');
          $('#staffModal').modal('hide');
          fetchCenterData();
        }
      },
      error: function() {
        console.log('Error adding staff');
        $('#staffModal').modal('hide');
        fetchCenterData();
      }
    });
  });

  // Save Staff Changes
  $('#editStaffSubmitBtn').click(function() {
    const form = $('#editStaffForm');
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }
    const payload = {
      id: $('#editStaffId').val(),
      staff_name: $('#editStaffName').val(),
      contact_no: $('#editContactNo').val(),
      role: $('#editStaffRole').val(),
      joining_date: $('#editJoiningDate').val()
    };
    $.ajax({
      url: baseUrl + "Staff/updateStaff",
      method: "POST",
      data: JSON.stringify(payload),
      contentType: "application/json",
      success: function(response) {
        if (response.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Staff updated successfully'
          });
          $('#editStaffModal').modal('hide');
          fetchCenterData();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update staff'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error updating staff'
        });
      }
    });
  });

  // Add Expense Handler
 $('#expenseSubmitBtn').click(function() {
    const form = $('#expenseForm');
    if (!form[0].checkValidity()) {
        form[0].reportValidity();
        return;
    }
    const payload = {
        center_id: centerId,
        category: $('#expense_category').val(),
        amount: parseFloat($('#expense_amount').val()).toFixed(2),
        date: $('#expense_date').val(),
        description: $('#expense_description').val()
    };
    $.ajax({
        url: baseUrl + "superadmin/Expenses",
        method: "POST",
        data: JSON.stringify(payload),
        contentType: "application/json",
        success: function(response) {
            if (response.status === "success") {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Expense added successfully'
                });
                $('#expenseModal').modal('hide');
                fetchCenterData();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to add expense'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error, xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error adding expense. Check console for details.'
            });
        }
    });
});

  // Edit Expense Handler
  $(document).on('click', '.btn-edit[data-expense-id]', function() {
    const expenseId = $(this).data('expense-id');
    $.ajax({
      url: baseUrl + "Expense/getExpenseById/" + expenseId,
      method: "GET",
      dataType: "json",
      success: function(response) {
        if (response.status === "success") {
          const e = response.expense;
          $("#editExpenseId").val(e.id);
          $("#editExpenseCategory").val(e.category);
          $("#editExpenseAmount").val(parseFloat(e.amount).toFixed(2));
          $("#editExpenseDate").val(e.date);
          $("#editExpenseDescription").val(e.description);
          validateForm('editExpenseForm', 'editExpenseSubmitBtn');
          $("#editExpenseModal").modal("show");
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load expense data'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error fetching expense data'
        });
      }
    });
  });

  // Save Expense Changes
  $('#editExpenseSubmitBtn').click(function() {
    const form = $('#editExpenseForm');
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }
    const payload = {
      id: $('#editExpenseId').val(),
      category: $('#editExpenseCategory').val(),
      amount: parseFloat($('#editExpenseAmount').val()).toFixed(2),
      date: $('#editExpenseDate').val(),
      description: $('#editExpenseDescription').val()
    };
    $.ajax({
      url: baseUrl + "Expense/updateExpense",
      method: "POST",
      data: JSON.stringify(payload),
      contentType: "application/json",
      success: function(response) {
        if (response.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Expense updated successfully'
          });
          $('#editExpenseModal').modal('hide');
          fetchCenterData();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update expense'
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Error updating expense'
        });
      }
    });
  });

  // Initialize
  fetchCenterData();
});
</script>

</body>
</html>
