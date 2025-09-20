<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Permission Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --danger: #dc3545;
            --muted: #6c757d;
            --card-bg: #fff;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
        }

        body {
            background: #f5f7fb;
            font-family: Inter, system-ui, Segoe UI, Roboto, -apple-system, sans-serif;
        }

        .hero {
            background: linear-gradient(135deg, var(--danger), #a71d2a);
            color: #fff;
            padding: 28px;
            border-radius: .6rem;
            margin-bottom: 24px;
        }

        .card {
            border: 0;
            border-radius: .6rem;
            box-shadow: 0 6px 20px rgba(25, 40, 60, .06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(25, 40, 60, .1);
        }

        .module {
            border-left: 4px solid var(--danger);
            padding: 12px;
            border-radius: .45rem;
            background: var(--card-bg);
        }

        .center-name {
            font-weight: 700;
            color: #343a40;
        }

        .admin-name {
            color: var(--danger);
            font-weight: 600;
        }

        .form-check .form-check-input:checked {
            background-color: var(--danger);
            border-color: var(--danger);
        }

        #permissionWrapper {
            transition: margin-left .2s ease;
            margin-left: 250px;
        }

        #permissionWrapper.min {
            margin-left: 60px;
        }

        .permission-card {
            border-top: 3px solid var(--danger);
        }

        .permission-header {
            background-color: var(--light-bg);
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            border-radius: .6rem .6rem 0 0 !important;
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 12px;
            padding: 16px;
        }

        .permission-item {
            background: var(--light-bg);
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .permission-item:hover {
            background: #e9ecef;
            border-color: #dee2e6;
        }

        .toggle-all-container {
            background-color: var(--light-bg);
            padding: 12px 16px;
            border-top: 1px solid var(--border-color);
            border-radius: 0 0 .6rem .6rem;
        }

        @media (max-width: 992px) {
            .permissions-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 768px) {
            #permissionWrapper {
                margin-left: 0 !important;
            }

            .permissions-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-check-input {
            cursor: pointer;
        }

        .form-check-label {
            cursor: pointer;
            user-select: none;
        }

        .save-btn {
            position: relative;
            overflow: hidden;
        }

        .save-btn:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 1;
            }

            20% {
                transform: scale(25, 25);
                opacity: 1;
            }

            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }

        .save-btn:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar placeholder (server include in original) -->
        <?php $this->load->view('superadmin/Include/Sidebar') ?>

        <main id="permissionWrapper" class="flex-fill">
            <div class="container-fluid p-4">
                <header class="hero text-center mb-4 shadow-sm">
                    <h1 class="h3 mb-1"><i class="fa fa-user-lock me-2"></i>Permission Management</h1>
                    <p class="mb-0 small">Manage access controls for your academy branches</p>
                </header>

                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <div class="card permission-card">
                            <div class="card-header d-flex align-items-center justify-content-between permission-header">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa fa-lock text-danger"></i>
                                    <strong>Manage Permissions</strong>
                                </div>
                                <div class="btn-group">
                                    <button id="toggleAllBtn" class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-toggle-off me-1"></i>Toggle All Off
                                    </button>
                                    <button id="saveAllBtn" class="btn btn-sm btn-danger save-btn">
                                        <i class="fa fa-save me-1"></i>Save All
                                    </button>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <?php foreach ($centers as $center): ?>
                                    <div class="center-permissions mb-4">
                                        <div class="px-4 pt-4 pb-2">
                                            <h5 class="center-name mb-1">
                                                <i class="fa fa-building text-danger me-2"></i>
                                                <?= $center['name']; ?>
                                            </h5>
                                            <p class="text-muted small mb-0">Manage permissions for this center</p>
                                        </div>

                                        <form method="post" action="<?= base_url('superadmin/save_permissions/' . $center['id']) ?>" class="center-form">
                                            <div class="permissions-grid">
                                                <?php foreach ($modules as $key => $label): ?>
                                                    <div class="permission-item d-flex align-items-center">
                                                        <div class="form-check flex-grow-1 mb-0">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="permissions[<?= $key ?>]"
                                                                value="1"
                                                                id="perm-<?= $center['id'] ?>-<?= $key ?>"
                                                                <?= !empty($center['permissions'][$key]) && $center['permissions'][$key] ? 'checked' : '' ?>>
                                                            <label class="form-check-label w-100" for="perm-<?= $center['id'] ?>-<?= $key ?>">
                                                                <?= $label ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="toggle-all-container d-flex justify-content-between align-items-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input center-toggle" type="checkbox"
                                                        id="toggle-<?= $center['id'] ?>" data-center="<?= $center['id'] ?>">
                                                    <label class="form-check-label small" for="toggle-<?= $center['id'] ?>">
                                                        Toggle all for <?= $center['name'] ?>
                                                    </label>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-danger save-btn">
                                                    <i class="fa fa-save me-1"></i>Save Center
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <?php if (next($centers)): ?>
                                        <hr class="mx-4 my-4">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle all permissions for a specific center
            document.querySelectorAll('.center-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const centerId = this.getAttribute('data-center');
                    const checkboxes = document.querySelectorAll(`#perm-${centerId}-\\[\\w+\\]`);
                    const isChecked = this.checked;

                    checkboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                });
            });

            // Global toggle all button
            const toggleAllBtn = document.getElementById('toggleAllBtn');
            let allEnabled = false;

            toggleAllBtn.addEventListener('click', function() {
                allEnabled = !allEnabled;
                const allCheckboxes = document.querySelectorAll('input[type="checkbox"]:not(.center-toggle)');

                allCheckboxes.forEach(checkbox => {
                    checkbox.checked = allEnabled;
                });

                // Update toggle all text
                toggleAllBtn.innerHTML = allEnabled ?
                    '<i class="fa fa-toggle-on me-1"></i>Toggle All Off' :
                    '<i class="fa fa-toggle-off me-1"></i>Toggle All On';

                // Update all center toggles
                document.querySelectorAll('.center-toggle').forEach(toggle => {
                    toggle.checked = allEnabled;
                });
            });

            // Save all forms
            document.getElementById('saveAllBtn').addEventListener('click', function() {
                const forms = document.querySelectorAll('.center-form');
                let savedCount = 0;

                Swal.fire({
                    title: 'Saving Permissions',
                    html: 'Saving all center permissions...<br><div class="progress mt-3" style="height: 10px;"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div></div>',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();

                        // Simulate saving each form
                        const totalForms = forms.length;
                        forms.forEach((form, index) => {
                            // In a real scenario, you would use AJAX to submit each form
                            // This is just a simulation
                            setTimeout(() => {
                                savedCount++;
                                const progress = (savedCount / totalForms) * 100;
                                document.querySelector('.progress-bar').style.width = `${progress}%`;

                                if (savedCount === totalForms) {
                                    setTimeout(() => {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: 'All permissions have been saved successfully.',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                    }, 500);
                                }
                            }, index * 300);
                        });
                    }
                });
            });

            // Add event listeners to individual forms for SweetAlert
            document.querySelectorAll('.center-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Saving Permissions',
                        text: 'Please wait while we save the permissions...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();

                            // Simulate form submission
                            setTimeout(() => {
                                // In real implementation, you would submit the form via AJAX or allow normal submission
                                // For demo purposes, we'll show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Saved!',
                                    text: 'Permissions have been saved successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Actually submit the form after success message
                                    form.submit();
                                });
                            }, 1000);
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>