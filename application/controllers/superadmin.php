<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Superadmin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('DashboardModel');
		$this->load->model('Student_model'); // Load the Student_model
		$this->load->database();

		// ✅ Block access if not logged in
		if (!$this->session->userdata('logged_in')) {
			redirect('auth/login');
		}
	}

	public function dashboard()
	{
		$data['activeStudents']  = $this->DashboardModel->getActiveStudentsCount();
		$data['totalStudents']   = $this->DashboardModel->getTotalStudentsCount();
		$data['totalIncome']     = $this->DashboardModel->getTotalIncome();
		$data['totalDue']        = $this->DashboardModel->getTotalDueAmount();
		$data['studentDistribution'] = $this->DashboardModel->getStudentDistribution();
		$data['monthlyRevenue']  = $this->DashboardModel->getMonthlyRevenue();

		// Fetch centers from DB
		$query = $this->db->get('center_details');
		$data['centers'] = $query->result(); // this will be an array of objects

		// print_r($data);
		// die; // Debugging line to check the data before loading the view

		$this->load->view('superadmin/dashboard', $data);
	}
	public function Sidebar()
	{
		$this->load->view('superadmin/Sidebar');
	}
	public function Navbar()
	{
		$this->load->view('superadmin/Navbar');
	}
	public function Login()
	{
		$this->load->view('superadmin/Login');
	}
	public function SignUp()
	{
		$this->load->view('superadmin/SignUp');
	}

	public function Superadmin_profile()
	{
		$this->load->view('superadmin/Superadmin_profile');
	}
	public function change_password()
	{
		header('Content-Type: application/json');

		$userType = $this->input->post('userType');
		$username = $this->input->post('username');
		$currentPassword = $this->input->post('currentPassword');
		$newPassword = $this->input->post('newPassword');

		// Validate input
		if (empty($userType) || empty($username) || empty($currentPassword) || empty($newPassword)) {
			echo json_encode(['success' => false, 'message' => 'All fields are required']);
			return;
		}

		if (strlen($newPassword) < 8) {
			echo json_encode(['success' => false, 'message' => 'New password must be at least 8 characters']);
			return;
		}

		// Check user type and update accordingly
		if ($userType === 'superadmin') {
			// Verify current password for superadmin
			$this->db->where('username', $username);
			$this->db->where('role', 'superadmin');
			$user = $this->db->get('users')->row();

			if (!$user) {
				echo json_encode(['success' => false, 'message' => 'User not found']);
				return;
			}

			// Verify current password (assuming you're using password_hash with PASSWORD_DEFAULT)
			if (!password_verify($currentPassword, $user->password)) {
				echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
				return;
			}

			// Update password
			$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
			$this->db->where('id', $user->id);
			$this->db->update('users', ['password' => $hashedPassword]);
		} elseif ($userType === 'admin') {
			// Verify current password for admin
			$this->db->where('name', $username); // Assuming center_details.name is the admin username
			$admin = $this->db->get('center_details')->row();

			if (!$admin) {
				echo json_encode(['success' => false, 'message' => 'Admin not found']);
				return;
			}

			// Verify current password
			if (!password_verify($currentPassword, $admin->password)) {
				echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
				return;
			}

			// Update password
			$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
			$this->db->where('id', $admin->id);
			$this->db->update('center_details', ['password' => $hashedPassword]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Invalid user type']);
			return;
		}

		echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
	}
	public function CenterManagement()
	{
		$this->load->view('superadmin/CenterManagement');
	}
	public function CenterManagement2()
	{
		$this->load->view('superadmin/CenterManagement2');
	}
	public function New_admission()
	{
		$this->load->view('superadmin/New_admission');
	}
	public function receipt()
	{
		$this->load->view('superadmin/receipt');
	}
	public function Re_admission()
	{
		$this->load->view('superadmin/Re_admission');
	}
	// public function Students()
	// {
	// 	$this->load->view('superadmin/Students');
	// }
	// show students list
	public function Students()
	{
		$data['students'] = $this->Student_model->get_all_students();
		$this->load->view('superadmin/Students', $data);
	}

	// view student details
	// public function student_details($id)
	// {
	// 	$data['student'] = $this->Student_model->get_student_by_id($id);
	// 	$this->load->view('superadmin/StudentDetails', $data);
	// }
	public function Renew_admission()
	{
		$this->load->view('superadmin/Renew_admission');
	}
	public function View_Re_Admission()
	{
		$this->load->view('superadmin/View_Re_Admission');
	}
	public function View_Renew_Students()
	{
		$this->load->view('superadmin/View_Renew_Students');
	}
	public function EvenetList()
	{
		$this->load->view('superadmin/EvenetList');
	}
	public function Report()
	{
		$this->load->view('superadmin/Report');
	}

	public function Finance()
	{
		$this->load->view('superadmin/Finance');
	}
	// API endpoint for AJAX
	public function getRevenue()
	{
		$filters = [
			'center_id'   => $this->input->post("center_id"),
			'start_date'  => $this->input->post("start_date"),
			'end_date'    => $this->input->post("end_date"),
		];

		$data = $this->Finance_model->getCombinedRevenue($filters);

		echo json_encode([
			'status' => true,
			'data'   => $data
		]);
	}

	public function Expenses()
	{
		$this->load->model('Expense_model');
		$this->load->model('Center_model'); // ✅ Make sure this is loaded

		$data['expenses'] = $this->Expense_model->get_all_expenses();
		$data['centers']  = $this->Center_model->get_all_centers(); // ✅ Pass centers to view

		$this->load->view('superadmin/Expenses', $data);
	}


	public function EventAndNotice()
	{
		$this->load->model('Event_model');
		$data['events'] = $this->Event_model->get_all_events();
		$this->load->view('superadmin/EventAndNotice', $data);
	}

	// Save new event (AJAX)
	public function saveEvent()
	{
		$this->load->model('Event_model');
		$data = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'date' => $this->input->post('date'),
			'time' => $this->input->post('time'),
			'fee' => $this->input->post('fee'),
			'max_participants' => $this->input->post('maxParticipants'),
			'venue' => $this->input->post('venue')
		);
		$this->Event_model->insert_event($data);
		echo json_encode(['status' => 'success']);
	}


	public function view_center_details()
	{
		$this->load->view('superadmin/view_center_details');
	}

	public function partcipant_form()
	{

		$this->load->view('superadmin/participant_form');
	}


	public function Permission()
	{

		$this->load->view('superadmin/Permission');
	}
	public function add_new_center()
	{
		$this->load->view('superadmin/add_new_center');
	}

	public function adminlogin()
	{
		$this->load->view('superadmin/adminlogin');
	}
	public function student_details($id = null)
	{
		if (!$id) {
			// if no student id is provided, redirect back to list
			redirect('superadmin/students');
		}

		$data['student'] = $this->Student_model->get_student_by_id($id);

		if (!$data['student']) {
			// if student not found, you can also redirect or show error
			$this->session->set_flashdata('error', 'Student not found.');
			redirect('superadmin/students');
		}

		
		// $data['student_get_current_batch'] = $this->Student_model->get_student_by_id_batch($id);

		$data['student_history'] = $this->Student_model->get_student_by_id_history($id);

	

		$data['student_history_batch'] = $this->Student_model->get_student_by_id_history_batch($id);
		

		

		// Load facilities
		$this->load->model('Facility_model');
		$data['facilities'] = $this->Facility_model->get_facilities_by_student($id);

		

		$this->load->view('superadmin/student_details', $data);
	}



	
}
