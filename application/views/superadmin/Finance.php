<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * application/views/superadmin/Finance.php
 * Centers the card responsively while keeping the sidebar offset in mind.
 * All existing PHP logic is preserved.
 */

function money($n)
{
    return number_format((float)$n, 2);
}

if (!isset($rows) || !is_array($rows)) {
    $rows = [];

    if (isset($this) && is_object($this) && property_exists($this, 'db')) {
        $sql = "
            SELECT
              cd.id AS center_id,
              cd.name AS center_name,
              COALESCE(s.stu_weekly,0)  AS stu_weekly,
              COALESCE(s.stu_monthly,0) AS stu_monthly,
              COALESCE(s.stu_yearly,0)  AS stu_yearly,
              COALESCE(s.stu_total,0)   AS stu_total,
              COALESCE(f.fac_weekly,0)  AS fac_weekly,
              COALESCE(f.fac_monthly,0) AS fac_monthly,
              COALESCE(f.fac_yearly,0)  AS fac_yearly,
              COALESCE(f.fac_total,0)   AS fac_total
            FROM center_details cd
            LEFT JOIN (
              SELECT
                s.center_id,
                SUM(CASE WHEN YEARWEEK(COALESCE(s.created_at,s.admission_date,s.joining_date),1)=YEARWEEK(CURDATE(),1) THEN COALESCE(s.paid_amount,0) ELSE 0 END) AS stu_weekly,
                SUM(CASE WHEN YEAR(COALESCE(s.created_at,s.admission_date,s.joining_date))=YEAR(CURDATE()) AND MONTH(COALESCE(s.created_at,s.admission_date,s.joining_date))=MONTH(CURDATE()) THEN COALESCE(s.paid_amount,0) ELSE 0 END) AS stu_monthly,
                SUM(CASE WHEN YEAR(COALESCE(s.created_at,s.admission_date,s.joining_date))=YEAR(CURDATE()) THEN COALESCE(s.paid_amount,0) ELSE 0 END) AS stu_yearly,
                SUM(COALESCE(s.paid_amount,0)) AS stu_total
              FROM students s
              GROUP BY s.center_id
            ) s ON s.center_id = cd.id
            LEFT JOIN (
              SELECT
                st.center_id,
                SUM(CASE WHEN YEARWEEK(sf.created_at,1)=YEARWEEK(CURDATE(),1) THEN COALESCE(sf.amount,0) ELSE 0 END) AS fac_weekly,
                SUM(CASE WHEN YEAR(sf.created_at)=YEAR(CURDATE()) AND MONTH(sf.created_at)=MONTH(CURDATE()) THEN COALESCE(sf.amount,0) ELSE 0 END) AS fac_monthly,
                SUM(CASE WHEN YEAR(sf.created_at)=YEAR(CURDATE()) THEN COALESCE(sf.amount,0) ELSE 0 END) AS fac_yearly,
                SUM(COALESCE(sf.amount,0)) AS fac_total
              FROM student_facilities sf
              JOIN students st ON st.id = sf.student_id
              GROUP BY st.center_id
            ) f ON f.center_id = cd.id
            ORDER BY (COALESCE(s.stu_total,0) + COALESCE(f.fac_total,0)) DESC, cd.name ASC
        ";

        try {
            $q = $this->db->query($sql);
            $rows = $q->result_array();
        } catch (Exception $e) {
            $rows = [];
            $db_error_message = $e->getMessage();
        }
    }
}

if (!is_array($rows)) $rows = [];

if (!isset($grand) || !is_array($grand)) {
    $grand = [
        'week' => 0.0,
        'month' => 0.0,
        'stu_alltime' => 0.0,
        'fac_alltime' => 0.0,
    ];

    foreach ($rows as &$r) {
        $r['stu_weekly']  = isset($r['stu_weekly']) ? (float)$r['stu_weekly'] : 0.0;
        $r['stu_monthly'] = isset($r['stu_monthly']) ? (float)$r['stu_monthly'] : 0.0;
        $r['stu_yearly']  = isset($r['stu_yearly']) ? (float)$r['stu_yearly'] : 0.0;
        $r['stu_total']   = isset($r['stu_total']) ? (float)$r['stu_total'] : 0.0;
        $r['fac_weekly']  = isset($r['fac_weekly']) ? (float)$r['fac_weekly'] : 0.0;
        $r['fac_monthly'] = isset($r['fac_monthly']) ? (float)$r['fac_monthly'] : 0.0;
        $r['fac_yearly']  = isset($r['fac_yearly']) ? (float)$r['fac_yearly'] : 0.0;
        $r['fac_total']   = isset($r['fac_total']) ? (float)$r['fac_total'] : 0.0;

        $r['week']  = $r['stu_weekly'] + $r['fac_weekly'];
        $r['month'] = $r['stu_monthly'] + $r['fac_monthly'];
        $r['year_window'] = $r['stu_yearly'] + $r['fac_yearly'];

        $r['alltime'] = $r['stu_total'] + $r['fac_total'];

        $grand['week'] += $r['week'];
        $grand['month'] += $r['month'];
        $grand['stu_alltime'] += $r['stu_total'];
        $grand['fac_alltime'] += $r['fac_total'];
    }
    unset($r);
}

if (!isset($grand_alltime)) {
    $grand_alltime = (float)$grand['stu_alltime'] + (float)$grand['fac_alltime'];
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Finance — Center Revenue</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f4f6f8;
            --card: #fff;
            --accent1: #ff5a5a;
            --accent2: #8b0000;
            --muted: #6b7280;
            --radius: 12px;
            --sidebar-width: 250px;
            /* default; JS will overwrite with real value */
            --sidebar-minimized: 60px;
            /* minimized width */
            --card-shadow: 0 14px 40px rgba(12, 12, 14, 0.08);
            --card-shadow-hover: 0 28px 60px rgba(12, 12, 14, 0.12);
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, Segoe UI, Roboto, Arial;
            background: var(--bg);
            color: #111
        }

        .wrap {
            width: 100%;
            padding: 20px;
            padding-left: calc(var(--sidebar-width));
            transition: padding-left 0.23s ease;
            box-sizing: border-box;
        }

        .content {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            padding: 8px;
        }

        .card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 22px;
            width: 100%;
            max-width: 1200px;
            box-shadow: var(--card-shadow);
            transition: transform .18s ease, box-shadow .18s ease;
            border: 1px solid rgba(0, 0, 0, 0.04);
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: var(--card-shadow-hover);
        }

        .meta {
            color: var(--muted);
            font-size: 0.95rem;
            margin-bottom: 8px
        }

        .table-wrap {
            overflow: auto;
            margin-top: 12px
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px
        }

        thead th {
            background: linear-gradient(90deg, var(--accent1), var(--accent2));
            color: #fff;
            padding: 10px;
            text-align: left
        }

        tbody td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            vertical-align: middle
        }

        .right {
            text-align: right;
            font-family: ui-monospace, monospace
        }

        tfoot td {
            padding: 10px;
            background: #fafafa;
            border-top: 2px solid #eee;
            font-weight: 800
        }

        .small {
            color: var(--muted);
            font-size: 0.92rem
        }

        .error {
            background: #fff6f6;
            border: 1px solid #ffd6d6;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 12px;
            color: #8b0000
        }

        @media (max-width:980px) {
            .wrap {
                padding-left: 12px
            }

            .content {
                padding: 6px
            }

            .card {
                padding: 16px
            }

            table {
                min-width: 640px
            }
        }

        @media (max-width:640px) {
            .card {
                padding: 14px
            }

            thead th {
                font-size: 13px
            }

            tbody td {
                font-size: 13px;
                padding: 8px
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php $this->load->view('superadmin/Include/Sidebar') ?>
    <!-- Navbar -->
    <?php $this->load->view('superadmin/Include/Navbar') ?>

    <div class="wrap" id="financeWrap" role="main">
        <div class="content">
            <div class="card" role="region" aria-label="Revenue summary">
                <h1 style="margin:0 0 8px 0;font-size:1.05rem">Revenue — Weekly / Monthly / Yearly</h1>
                <div class="meta">Weekly/Monthly are windowed (overlap). 'Yearly' column now shows student all-time (renamed); All-time column shows student+facility combined.</div>

                <?php if (!empty($db_error_message)): ?>
                    <div class="error" role="alert">
                        <strong>Warning:</strong> Database query failed inside view. Message: <?= htmlspecialchars($db_error_message) ?>
                    </div>
                <?php endif; ?>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Center</th>
                                <th class="right">Weekly (₹)</th>
                                <th class="right">Monthly (₹)</th>
                                <th class="right">Yearly (₹)</th>
                                <th class="right">Facility Total (All-time) (₹)</th>
                                <th class="right">All-time Total (₹)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <td colspan="7" class="small">No centers found.</td>
                                </tr>
                                <?php else: foreach ($rows as $r):
                                    $cid = (int)($r['center_id'] ?? 0);
                                    $cname = htmlspecialchars($r['center_name'] ?? "Center {$cid}");
                                    $week = $r['week'] ?? 0;
                                    $month = $r['month'] ?? 0;
                                    $yearly_display = $r['stu_total'] ?? 0;
                                    $ftot = $r['fac_total'] ?? 0;
                                    $alltime = $r['alltime'] ?? (($r['stu_total'] ?? 0) + $ftot);
                                ?>
                                    <tr data-center-id="<?= $cid ?>">
                                        <td>
                                            <div style="font-weight:700;"><?= $cname ?></div>
                                            <div class="small">ID: <?= $cid ?></div>
                                        </td>
                                        <td class="right">₹ <?= money($week) ?></td>
                                        <td class="right">₹ <?= money($month) ?></td>
                                        <td class="right">₹ <?= money($yearly_display) ?></td>
                                        <td class="right">₹ <?= money($ftot) ?></td>
                                        <td class="right">₹ <?= money($alltime) ?></td>
                                        <td><a href="<?= (function_exists('base_url') ? base_url("finance/details/{$cid}") : '#') ?>" class="center-details-link" data-center-id="<?= $cid ?>">Details</a></td>
                                    </tr>
                            <?php endforeach;
                            endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style="font-weight:800">Grand Totals</td>
                                <td class="right">₹ <?= money($grand['week'] ?? 0) ?></td>
                                <td class="right">₹ <?= money($grand['month'] ?? 0) ?></td>
                                <td class="right">₹ <?= money($grand['stu_alltime'] ?? 0) ?></td>
                                <td class="right">₹ <?= money($grand['fac_alltime'] ?? 0) ?></td>
                                <td class="right">₹ <?= money($grand_alltime ?? 0) ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div style="display:flex;justify-content:space-between;margin-top:12px">
                    <div class="small">Source: students.paid_amount + student_facilities.amount. Dates: students.created_at/admission_date/joining_date &amp; student_facilities.created_at.</div>
                    <div class="small">Rendered: <?= date('Y-m-d H:i:s') ?></div>
                </div>
            </div><!-- .card -->
        </div><!-- .content -->
    </div><!-- .wrap -->

    <!-- Center Details Modal -->
    <div class="modal fade" id="centerDetailsModal" tabindex="-1" aria-labelledby="centerDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;">
                <div class="modal-header" style="border-bottom: none;">
                    <h5 class="modal-title" id="centerDetailsModalLabel">Center Summary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="centerDetailsModalBody">
                    <div style="min-height:80px; display:flex; align-items:center; justify-content:center;">
                        <div class="small text-muted">Loading...</div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const wrap = document.getElementById('financeWrap');

            const DEFAULT_SIDEBAR = 250;
            const MINIMIZED_SIDEBAR = 60;

            function findSidebar() {
                return document.querySelector('.sidebar, #sidebar, .main-sidebar, .sidebar-area, .left-sidebar');
            }

            function updateSidebarWidth() {
                const sidebar = findSidebar();
                let width = DEFAULT_SIDEBAR;

                if (!sidebar) {
                    if (wrap && wrap.classList.contains('minimized')) width = MINIMIZED_SIDEBAR;
                } else {
                    const cls = sidebar.className || '';
                    if (cls.includes('minimized') || cls.includes('collapsed') || cls.includes('sidebar-collapse')) {
                        width = MINIMIZED_SIDEBAR;
                    } else {
                        width = Math.max(sidebar.offsetWidth || DEFAULT_SIDEBAR, 48);
                    }
                }

                document.documentElement.style.setProperty('--sidebar-width', width + 'px');
            }

            updateSidebarWidth();

            let resizeTimer = null;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(updateSidebarWidth, 120);
            });

            const sidebar = findSidebar();
            if (sidebar) {
                const mo = new MutationObserver(function(mutations) {
                    for (const m of mutations) {
                        if (m.type === 'attributes' && m.attributeName === 'class') {
                            updateSidebarWidth();
                        }
                    }
                });
                mo.observe(sidebar, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            }

            document.addEventListener('sidebarToggle', function(e) {
                updateSidebarWidth();
                if (wrap && e.detail && typeof e.detail.minimized !== 'undefined') {
                    wrap.classList.toggle('minimized', !!e.detail.minimized);
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'm' && !e.ctrlKey && !e.metaKey && !e.altKey) {
                    if (wrap) wrap.classList.toggle('minimized');
                    updateSidebarWidth();
                    document.dispatchEvent(new CustomEvent('sidebarToggle', {
                        detail: {
                            minimized: wrap && wrap.classList.contains('minimized')
                        }
                    }));
                }
            });

            // -----------------------
            // Details modal logic
            // -----------------------

            // CSRF fields if CI has csrf protection (safe to include)
            const CSRF = {
                name: '<?= $this->security->get_csrf_token_name() ?>',
                hash: '<?= $this->security->get_csrf_hash() ?>'
            };

            // Helper: format INR
            function inr(n) {
                if (n === null || n === undefined) return '0.00';
                return Number(n).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Build the modal content HTML given the data
            function buildDetailsHtml(data) {
                return `
          <div>
            <div style="margin-bottom:10px;">
              <strong style="font-size:1.05rem;">${escapeHtml(data.center_name)}</strong>
              <div class="small text-muted">Center ID: ${data.center_id}</div>
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;">
              <div style="flex:1; min-width:180px; background:#fff; border-radius:8px; padding:10px; border:1px solid #f0f0f0;">
                <div class="small text-muted">Total Students</div>
                <div style="font-weight:700; margin-top:6px;">${escapeHtml(String(data.total_students || 0))}</div>
              </div>

              <div style="flex:1; min-width:180px; background:#fff; border-radius:8px; padding:10px; border:1px solid #f0f0f0;">
                <div class="small text-muted">Active Students</div>
                <div style="font-weight:700; margin-top:6px;">${escapeHtml(String(data.active_students || 0))}</div>
              </div>

              <div style="flex:1; min-width:200px; background:#fff; border-radius:8px; padding:10px; border:1px solid #f0f0f0;">
                <div class="small text-muted">Students with Pending Fees</div>
                <div style="font-weight:700; margin-top:6px;">${escapeHtml(String(data.students_with_due || 0))}</div>
                <div class="small text-muted">Total Pending: ₹ ${inr(data.total_due)}</div>
              </div>

              <div style="flex:1; min-width:200px; background:#fff; border-radius:8px; padding:10px; border:1px solid #f0f0f0;">
                <div class="small text-muted">Total Paid (Students)</div>
                <div style="font-weight:700; margin-top:6px;">₹ ${inr(data.total_paid)}</div>
              </div>
            </div>

            ${data.last_attendance ? `<div style="margin-top:12px;" class="small text-muted">Last attendance recorded: ${escapeHtml(data.last_attendance)}</div>` : ''}
          </div>
        `;
            }

            // Safe HTML escape
            function escapeHtml(unsafe) {
                if (unsafe === null || unsafe === undefined) return '';
                return String(unsafe)
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Attach click handlers to Details links (delegated)
            document.addEventListener('click', function(ev) {
                const a = ev.target.closest && ev.target.closest('a.center-details-link');
                if (!a) return;
                ev.preventDefault();

                const centerId = a.getAttribute('data-center-id') || a.dataset.centerId || a.href.split('/').pop();
                if (!centerId) return;

                // show modal with loader
                const modalEl = document.getElementById('centerDetailsModal');
                const modalBody = document.getElementById('centerDetailsModalBody');
                modalBody.innerHTML = '<div style="min-height:80px; display:flex; align-items:center; justify-content:center;"><div class="small text-muted">Loading...</div></div>';
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

                // Build URL
                const url = '<?= base_url("finance/get_center_summary/") ?>' + encodeURIComponent(centerId);

                // fetch JSON
                fetch(url, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(resp) {
                        if (!resp.ok) {
                            return resp.text().then(t => {
                                throw new Error('Server error: ' + (t || resp.status));
                            });
                        }
                        return resp.json();
                    })
                    .then(function(json) {
                        if (!json || json.status !== 'success' || !json.data) {
                            modalBody.innerHTML = '<div class="small text-muted">No data available</div>';
                            return;
                        }
                        modalBody.innerHTML = buildDetailsHtml(json.data);
                    })
                    .catch(function(err) {
                        console.error('Failed to fetch center summary', err);
                        modalBody.innerHTML = '<div class="small text-muted text-danger">Failed to load data. Please try again later.</div>';
                    });
            });

        })();
    </script>

    <!-- bootstrap (if not already loaded in your layout) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>