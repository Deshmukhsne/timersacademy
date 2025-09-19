<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    // Show login page
    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            if ($role === 'superadmin') {
                redirect('superadmin/dashboard');
            } else {
                redirect('admin/dashboard');
            }
        }
        $this->load->view('admin/adminlogin');
    }

    // Handle login form submission
    public function handle_login()
    {
        $username = $this->input->post('username'); // for superadmin
        $password = $this->input->post('password');

        // First, check superadmin
        $user = $this->Auth_model->get_superadmin($username);

        if ($user) {
            // Superadmin password verification
            if (password_verify($password, $user->password)) {
                $this->session->set_userdata([
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                    'logged_in' => TRUE
                ]);
                redirect('superadmin/dashboard');
            } else {
                $this->show_error('Invalid Username or Password!');
            }
        } else {
            // If not superadmin, check admin login using center_number
            // $admin = $this->Auth_model->get_admin($username); // username is center_number here
            $admin = $this->Auth_model->get_admin_by_name($username); // username is now center name

            if ($admin && password_verify($password, $admin->password)) {
                // ✅ get permissions from DB
                $this->load->model('Permission_model');
                $permissions = $this->Permission_model->get_by_center($admin->id);

                $this->session->set_userdata([
                    'id'        => $admin->id,
                    'username'  => $admin->name,
                    'role'      => 'admin',
                    'center_id' => $admin->id,
                    'permissions' => $permissions,  // <-- store here
                    'logged_in' => TRUE
                ]);

                redirect('admin/dashboard');
            } else {
                $this->show_error('Invalid Username or Password!');
            }
        }
    }


    // Logout
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    // Helper to show error using SweetAlert
    private function show_error($message)
    {
        $data['error'] = $message;
        $this->load->view('admin/adminlogin', $data);
    }
}
