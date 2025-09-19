<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<div class="sidebar" id="sidebar">
    <div class="logo">
        <img src="<?php echo base_url('assets/Images/timeersbadmintonacademy_logo.png'); ?>" alt="Logo">
    </div>
    <nav class="nav flex-column">
        <a class="nav-link <?php echo ($this->uri->segment(2) == 'Dashboard') ? 'active' : ''; ?>" href="<?php echo base_url() . 'admin/dashboard'; ?>">
            <i class="bi bi-house-door"></i><span>Dashboard</span>
        </a>
        <!-- Center Management -->



        <!-- Admission Management (Parent with Submenu) -->
        <div class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" href="#admissionMenu" role="button"
                aria-expanded="false" aria-controls="admissionMenu">
                <span><i class="bi bi-person-lines-fill"></i> <span>Admission Management</span></span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse" id="admissionMenu">
                <nav class="nav flex-column ms-3">
                    <a class="nav-link <?php echo ($this->uri->segment(2) == 'New_admission') ? 'active' : ''; ?>"
                        href="<?php echo base_url('admin/New_admission'); ?>">
                        <i class="bi bi-plus-circle"></i><span> New Admission</span>
                    </a>
                    <a class="nav-link <?php echo ($this->uri->segment(2) == 'Re_admission') ? 'active' : ''; ?>"
                        href="<?php echo base_url('admin/Re_admission'); ?>">
                        <i class="bi bi-arrow-counterclockwise"></i><span> Re-Admission</span>
                    </a>
                    <a class="nav-link <?php echo ($this->uri->segment(2) == 'Renew_admission') ? 'active' : ''; ?>"
                        href="<?php echo base_url('admin/Renew_admission'); ?>">
                        <i class="bi bi-arrow-repeat"></i><span> Renew Admission</span>
                    </a>

                </nav>
            </div>
        </div>

        <!-- Students -->
        <a class="nav-link <?php echo ($this->uri->segment(2) == 'Students') ? 'active' : ''; ?>"
            href="<?php echo base_url('admin/Students'); ?>">
            <i class="bi bi-mortarboard"></i><span> Students Management</span>
        </a>


        <!-- <a class="nav-link <?php echo ($this->uri->segment(2) == 'Batch') ? 'active' : ''; ?>" href="<?php echo base_url() . 'admin/Batch'; ?>">
            <i class="bi bi-stack"></i><span>Batch</span>
        </a> -->

        <a class="nav-link <?php echo ($this->uri->segment(2) == 'EventAndNotice') ? 'active' : ''; ?>" href="<?php echo base_url() . 'admin/EventAndNotice'; ?>">
            <i class="bi bi-calendar-event"></i><span>EventAndNotice</span>
        </a>

        <a class="nav-link <?php echo ($this->uri->segment(2) == 'Expenses') ? 'active' : ''; ?>" href="<?php echo base_url() . 'admin/Expenses'; ?>">
            <i class="bi bi-cash"></i><span>Add Expenses</span>
        </a>
        <!-- <a class="nav-link <?php echo ($this->uri->segment(2) == 'Attendance') ? 'active' : ''; ?>" href="<?php echo base_url() . 'admin/Attendance'; ?>">
            <i class="bi bi-check-circle"></i><span>Attendance</span>
        </a> -->
        <a class="nav-link <?php echo ($this->uri->segment(2) == 'Leave') ? 'active' : ''; ?>" href="<?php echo base_url() . 'admin/Leave'; ?>">
            <i class="bi bi-calendar-x"></i><span>Leave</span>
        </a>
        <!-- <a class="nav-link <?php echo ($this->uri->uri_string() == 'admin/add-on-facilities' || $this->router->fetch_class() == 'Add_on_facilities') ? 'active' : ''; ?>" href="<?php echo base_url() . 'admin/add-on-facilities'; ?>">
            <i class="bi bi-plus-circle"></i><span>AddOnfacility</span>
        </a> -->
        <!-- <a class="nav-link <?php echo ($this->uri->uri_string() == 'admin/venues' || $this->router->fetch_class() == 'venues') ? 'active' : ''; ?>" href="<?php echo base_url() . 'admin/venues'; ?>">
            <i class="bi bi-geo-alt"></i><span>Venues</span> -->
        </a>
        <a class="nav-link <?php echo ($this->uri->segment(2) == 'Profile') ? 'active' : ''; ?>" href="<?php echo base_url() . 'admin/Profile'; ?>">
            <i class="bi bi-person-circle"></i><span>Profile</span>
        </a>
        <!-- <a class="nav-link <?php echo ($this->uri->segment(2) == 'Report') ? 'active' : ''; ?>" href="<?php echo base_url() . 'admin/Report'; ?>">
            <i class="bi bi-clipboard-data"></i><span>Report</span>
        </a> -->
        <a class="nav-link" href="<?php echo base_url('auth/logout'); ?>"><i class="bi bi-box-arrow-right"></i><span>Logout</span></a>
    </nav>
</div>

<style>
    .sidebar {
        width: 250px;
        background-color: white;
        border-right: 1px solid #dee2e6;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 1000;
        overflow-y: auto;
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* IE and Edge */
        transition: width 0.3s ease-in-out;
        height: 100vh;
        padding-top: 28px;
        font-family: 'Montserrat', serif;
    }

    .sidebar::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, and other Webkit browsers */
    }

    .sidebar.minimized {
        width: 60px;
    }

    .sidebar .nav-link {
        color: #000;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        margin: 5px 10px;
        border-radius: 30px;
        background-color: transparent;
    }

    .sidebar.minimized .nav-link {
        justify-content: center;
        padding: 10px;
        background-color: transparent !important;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: #e9ecef;
        color: #000;
        border-radius: 30px;
        font-weight: bold;
    }

    .sidebar .nav-link i {
        margin-right: 10px;
        font-size: 16px;
        transition: margin 0.3s ease;
    }

    .sidebar.minimized .nav-link i {
        margin-right: 0;
        font-size: 18px;
        /* Slightly larger for better visibility */
    }

    .sidebar .logo {
        text-align: center;
        padding: 37px 0 0 0;
    }

    .sidebar .logo img {
        max-width: 80%;
        height: 155px;
        transition: all 0.3s ease;
    }

    .sidebar.minimized .logo img {
        max-width: 40px;
        height: 40px;
    }

    .sidebar.minimized .nav-link span {
        display: none;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-250px);
        }

        .sidebar.active {
            transform: translateX(0);
        }
    }

    @media (min-width: 769px) {
        .sidebar {
            transform: translateX(0);
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        .sidebar {
            width: 200px;
        }

        .sidebar.minimized {
            width: 60px;
        }
    }
</style>

<script>
    // Save and restore scroll position
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const navLinks = sidebar.querySelectorAll('.nav-link');

        // Save scroll position before navigation
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                if (link.href && link.href !== '#') {
                    const scrollPosition = sidebar.scrollTop;
                    sessionStorage.setItem('sidebarScrollPosition', scrollPosition);
                }
            });
        });

        // Restore scroll position on page load
        const savedScrollPosition = sessionStorage.getItem('sidebarScrollPosition');
        if (savedScrollPosition !== null) {
            sidebar.scrollTop = parseInt(savedScrollPosition, 10);
        }
    });
</script>