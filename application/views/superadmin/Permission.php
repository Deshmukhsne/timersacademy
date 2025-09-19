<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Permission Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --danger: #dc3545;
            --muted: #6c757d;
            --card-bg: #fff
        }

        body {
            background: #f5f7fb;
            font-family: Inter, system-ui, Segoe UI, Roboto, -apple-system
        }

        .hero {
            background: linear-gradient(135deg, var(--danger), #a71d2a);
            color: #fff;
            padding: 28px;
            border-radius: .6rem
        }

        .card {
            border: 0;
            border-radius: .6rem;
            box-shadow: 0 6px 20px rgba(25, 40, 60, .06)
        }

        .module {
            border-left: 4px solid var(--danger);
            padding: 12px;
            border-radius: .45rem;
            background: var(--card-bg)
        }

        .center-name {
            font-weight: 700
        }

        .admin-name {
            color: var(--danger);
            font-weight: 600
        }

        .form-check .form-check-input:checked {
            background-color: var(--danger);
            border-color: var(--danger)
        }

        #permissionWrapper {
            transition: margin-left .2s ease;
            margin-left: 250px
        }

        #permissionWrapper.min {
            margin-left: 60px
        }

        @media(max-width:768px) {
            #permissionWrapper {
                margin-left: 0 !important
            }
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
                    <div class="col-lg-10">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between bg-white">
                                <div class="d-flex align-items-center gap-2"><i class="fa fa-lock text-danger"></i><strong>Manage Permissions</strong></div>
                                <div class="btn-group">
                                    <button id="toggleAllBtn" class="btn btn-sm btn-outline-secondary">Toggle All Off</button>
                                    <button id="saveBtn" class="btn btn-sm btn-danger"><i class="fa fa-save me-1"></i>Save</button>
                                </div>
                            </div>
                            <div class="card-body">

                                <?php foreach ($centers as $center): ?>
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <strong><?= $center['name']; ?></strong>
                                        </div>
                                        <div class="card-body">
                                            <form method="post" action="<?= base_url('superadmin/save_permissions/' . $center['id']) ?>">
                                                <?php foreach ($modules as $key => $label): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="permissions[<?= $key ?>]"
                                                            value="1"
                                                            <?= !empty($center['permissions'][$key]) && $center['permissions'][$key] ? 'checked' : '' ?>>
                                                        <label class="form-check-label"><?= $label ?></label>
                                                    </div>
                                                <?php endforeach; ?>

                                                <button type="submit" class="btn btn-primary mt-2">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>