<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar with Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding-top: 60px;
            /* Offset for fixed navbar */
            font-family: 'Montserrat', serif;
            /* background-color: #EFE9E9 !important; */
        }

        /* navbar sticky  */
        /* ------------------------------------------------------------------------------------------------------- */

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            position: fixed;
            top: 0;
            z-index: 1030;
            /* Higher than sidebar (1000) but lower than notification (1001) */
            height: 60px;
            width: calc(100% - 250px);
            left: 250px;
            overflow-x: hidden;
            transition: width 0.3s ease-in-out, left 0.3s ease-in-out;
            background-color: white !important;
            /* Force white background */
            border-bottom: 1px solid #dee2e6;
            /* Optional: Add a subtle border */
        }

        /* ------------------------------------------------------------------------------------------------------- */
        .navbar.sidebar-minimized {
            width: calc(100% - 60px);
            /* Adjust for minimized sidebar */
            left: 60px;
        }

        .navbar.sidebar-hidden {
            width: 100%;
            /* Full width when sidebar is hidden */
            left: 0;
        }

        .navbar-toggle {
            background: none;
            border: none;
            color: black !important;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
        }

        .notification-icon {
            font-size: 24px;
            cursor: pointer;
            position: relative;
            margin-left: 15px;
            z-index: 1101;
            /* Ensure visibility */
        }

        .notification-icon i {
            color: black !important;
            transition: color 0.3s ease;
        }

        .notification-icon i:hover {
            color: #0A6DFF;
        }

        .notification-container {
            display: none;
            position: fixed;
            top: 60px;
            right: 20px;
            /* Fixed position for consistency */
            background: #fff;
            width: 320px;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            height: auto;
            /* Fit content to avoid scrollbar */
            overflow-y: hidden;
            /* Prevent vertical scrollbar */
        }

        .notification-container::before {
            content: "";
            position: absolute;
            top: -5px;
            right: 20px;
            width: 10px;
            height: 10px;
            background: #fff;
            transform: rotate(45deg);
            box-shadow: -2px -2px 5px rgba(0, 0, 0, 0.1);
        }

        .notification-list {
            max-height: none;
            /* Remove height restriction */
            overflow-y: hidden;
            /* Ensure no scrollbar */
        }

        .notification-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .notification-item strong {
            font-size: 16px;
        }

        .notification-item p {
            font-size: 14px;
            color: gray;
            margin: 0;
        }

        .notification-item .time {
            font-size: 12px;
            color: gray;
        }

        @media (max-width: 768px) {
            .navbar {
                width: 100%;
                left: 0;
            }

            .navbar.sidebar-minimized,
            .navbar.sidebar-hidden {
                width: 100%;
                left: 0;
            }

            .notification-container {
                right: 10px;
                width: 90%;
                max-width: 320px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .navbar {
                width: calc(100% - 200px);
                /* Adjust for tablet sidebar */

            }

            .navbar.sidebar-minimized {
                width: calc(100% - 60px);
            }
        }

        .profile-icon img {
            border: 2px solid #fff;
            background-color: #000;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .profile-icon img:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <!-- Navbar Component -->
    <nav class="navbar" id="navbar">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="navbar-toggle me-2" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            <div class="d-flex align-items-center position-relative">
                <div class="notification-icon" id="notificationIcon">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div class="profile-icon ms-3">
                    <a href="<?php echo base_url('superadmin/Superadmin_profile'); ?>">
                        <img src="<?php echo base_url('assets/Images/timeersbadmintonacademy_logo.png'); ?>"
                            class="rounded-circle"
                            alt="Profile"
                            width="32"
                            height="32">
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <!-- Notification Dropdown -->
    <div class="notification-container" id="notificationDropdown">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Notifications</h5>
            <button class="btn-close" id="closeNotification"></button>
        </div>
        <hr>
        <div class="notification-list">
            <div class="notification-item">
                <div>
                    <strong>Admin</strong>
                    <p>Your interview has been scheduled</p>
                </div>
                <span class="time">2hr ago</span>
            </div>
            <div class="notification-item">
                <div>
                    <strong>HR</strong>
                    <p>Meeting today at 4 PM</p>
                </div>
                <span class="time">1hr ago</span>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Notification dropdown functionality
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const closeNotification = document.getElementById('closeNotification');

            if (notificationIcon && notificationDropdown && closeNotification) {
                notificationIcon.addEventListener('click', (event) => {
                    event.stopPropagation();
                    notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
                });

                closeNotification.addEventListener('click', () => {
                    notificationDropdown.style.display = 'none';
                });

                window.addEventListener('click', (event) => {
                    if (!notificationIcon.contains(event.target) && !notificationDropdown.contains(event.target)) {
                        notificationDropdown.style.display = 'none';
                    }
                });
            }

            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const navbar = document.querySelector('.navbar');
            const dashboardWrapper = document.getElementById('dashboardWrapper');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        // Mobile behavior
                        if (sidebar) {
                            sidebar.classList.toggle('active');
                            navbar.classList.toggle('sidebar-hidden', !sidebar.classList.contains('active'));
                        }
                    } else {
                        // Desktop behavior - minimize/maximize
                        if (sidebar) {
                            const isMinimized = sidebar.classList.toggle('minimized');
                            navbar.classList.toggle('sidebar-minimized', isMinimized);

                            // Also toggle dashboard wrapper if it exists
                            if (dashboardWrapper) {
                                dashboardWrapper.classList.toggle('sidebar-minimized', isMinimized);
                            }
                        }
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Notification dropdown functionality
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const closeNotification = document.getElementById('closeNotification');

            if (notificationIcon && notificationDropdown && closeNotification) {
                notificationIcon.addEventListener('click', (event) => {
                    event.stopPropagation();
                    notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
                });

                closeNotification.addEventListener('click', () => {
                    notificationDropdown.style.display = 'none';
                });

                window.addEventListener('click', (event) => {
                    if (!notificationIcon.contains(event.target) && !notificationDropdown.contains(event.target)) {
                        notificationDropdown.style.display = 'none';
                    }
                });
            }

            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const navbar = document.querySelector('.navbar');
            const contentWrapper = document.getElementById('contentWrapper'); // Updated to contentWrapper

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        // Mobile behavior
                        if (sidebar) {
                            sidebar.classList.toggle('active');
                            navbar.classList.toggle('sidebar-hidden', !sidebar.classList.contains('active'));
                        }
                    } else {
                        // Desktop behavior - minimize/maximize
                        if (sidebar && contentWrapper) {
                            const isMinimized = sidebar.classList.toggle('minimized');
                            navbar.classList.toggle('sidebar-minimized', isMinimized);
                            contentWrapper.classList.toggle('minimized', isMinimized); // Toggle content wrapper
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>