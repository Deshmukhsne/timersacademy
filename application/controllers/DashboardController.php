<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DashboardModel');
        $this->load->model('Student_model'); // if used elsewhere
        $this->load->database();
    }

    public function dashboard()
    {
        $data['activeStudents']  = $this->DashboardModel->getActiveStudentsCount();
        $data['totalStudents']   = $this->DashboardModel->getTotalStudentsCount();
        $data['totalIncome']     = $this->DashboardModel->getTotalIncome();
        $data['totalDue']        = $this->DashboardModel->getTotalDueAmount();
        $data['studentDistribution'] = $this->DashboardModel->getStudentDistribution();
        $data['monthlyRevenue']  = $this->DashboardModel->getMonthlyRevenue();

        // fetch centers for right sidebar (same as Superadmin controller did)
        $query = $this->db->get('center_details');
        $data['centers'] = $query->result();

        $this->load->view('superadmin/dashboard', $data);
    }

    /**
     * AJAX: center_stats
     * Returns aggregated numbers used on top stat cards.
     * GET param: center_id (optional). If not provided, returns global stats.
     * Response: { status: 'success', data: { active_students, total_students, total_paid, total_due, attendance_rate } }
     */
    public function center_stats()
    {
        $center_id = $this->input->get('center_id', true);

        try {
            // base where
            $where = [];
            if (!empty($center_id) && is_numeric($center_id)) {
                $where['center_id'] = (int)$center_id;
            }

            // total students
            if (!empty($where)) {
                $this->db->where($where);
            }
            $total_students = (int) $this->db->count_all_results('students');

            // active students
            if (!empty($where)) $this->db->where($where);
            $this->db->where('status', 'Active');
            $active_students = (int) $this->db->count_all_results('students');

            // total paid
            if (!empty($where)) $this->db->where($where);
            $this->db->select('COALESCE(SUM(paid_amount),0) AS total_paid', false);
            $paid_row = $this->db->get('students')->row();
            $total_paid = floatval($paid_row->total_paid ?? 0);

            // total due
            if (!empty($where)) $this->db->where($where);
            $this->db->select('COALESCE(SUM(remaining_amount),0) AS total_due', false);
            $due_row = $this->db->get('students')->row();
            $total_due = floatval($due_row->total_due ?? 0);

            // attendance rate: percentage of distinct students who were present at least once in last 7 days
            // vs total_students (if total_students == 0 => 0)
            $seven_days_ago = date('Y-m-d', strtotime('-6 days')); // include today -> 7-day window
            // build attendance query - use attendance.date OR attendance.created_at if present
            $this->db->distinct();
            $this->db->select('attendance.student_id');
            $this->db->where("DATE(COALESCE(attendance.date, attendance.created_at)) >= ", $seven_days_ago);
            $this->db->where("attendance.status", "present");
            if (!empty($center_id) && is_numeric($center_id)) {
                // join to students to filter by center
                $this->db->join('students', 'students.id = attendance.student_id', 'inner');
                $this->db->where('students.center_id', (int)$center_id);
            }
            $present_query = $this->db->get('attendance');
            $present_count = $present_query->num_rows();

            $attendance_rate = 0;
            if ($total_students > 0) {
                $attendance_rate = round(($present_count / $total_students) * 100, 2);
            }

            $data = [
                'total_students' => $total_students,
                'active_students' => $active_students,
                'total_paid' => $total_paid,
                'total_due' => $total_due,
                'attendance_rate' => $attendance_rate
            ];

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'data' => $data]));
        } catch (Exception $e) {
            log_message('error', 'DashboardController::center_stats error: ' . $e->getMessage());
            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Server error']));
        }
    }

    /**
     * AJAX: students_list
     * Params:
     *  - filter: 'active'|'attendance'|'due'|'paid'  (required)
     *  - center_id: optional numeric center id
     *
     * Response: { status: 'success', data: [ {id, name, contact, parent_name, batch_id, student_progress_category, paid_amount, remaining_amount, status, last_attendance, attendance_count_last_7_days } ] }
     */
    public function students_list()
    {
        $filter = $this->input->get('filter', true) ?? $this->input->post('filter', true);
        $center_id = $this->input->get('center_id', true) ?? $this->input->post('center_id', true);

        if (empty($filter)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'filter is required']));
        }

        try {
            // base students query
            $this->db->select('students.*');
            $this->db->from('students');

            if (!empty($center_id) && is_numeric($center_id)) {
                $this->db->where('students.center_id', (int)$center_id);
            }

            // apply filter
            switch ($filter) {
                case 'active':
                    $this->db->where('students.status', 'Active');
                    break;

                case 'attendance':
                    // students who have attendance in last 7 days (distinct student ids)
                    $seven_days_ago = date('Y-m-d', strtotime('-6 days')); // include today
                    $this->db->join('attendance', 'attendance.student_id = students.id', 'inner');
                    $this->db->where("DATE(COALESCE(attendance.date, attendance.created_at)) >= ", $seven_days_ago);
                    $this->db->where('attendance.status', 'present');
                    $this->db->group_by('students.id');
                    break;

                case 'due':
                    $this->db->where('students.remaining_amount >', 0);
                    break;

                case 'paid':
                    $this->db->where('students.paid_amount >', 0);
                    break;

                default:
                    // unknown filter, return empty
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(['status' => 'success', 'data' => []]));
            }

            $students = $this->db->get()->result_array();

            // enrich each student with last_attendance and attendance_count_last_7_days
            $result = [];
            if (!empty($students)) {
                // collect student ids
                $student_ids = array_column($students, 'id');

                // fetch last attendance per student (use created_at fallback)
                $ids_chunk = $student_ids; // small sets expected; adjust chunking if very large
                $this->db->select("a.student_id, MAX(CONCAT(COALESCE(a.date,''),' ',COALESCE(a.time,''))) AS last_attendance, COUNT(CASE WHEN a.status='present' AND DATE(COALESCE(a.date,a.created_at)) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) THEN 1 END) AS attendance_count_last_7_days", false);
                $this->db->from('attendance a');
                $this->db->where_in('a.student_id', $ids_chunk);
                $this->db->group_by('a.student_id');
                $att_q = $this->db->get();
                $att_rows = $att_q->result_array();
                $attendance_map = [];
                foreach ($att_rows as $ar) {
                    $sid = $ar['student_id'];
                    $attendance_map[$sid] = [
                        'last_attendance' => $ar['last_attendance'],
                        'attendance_count_last_7_days' => (int)($ar['attendance_count_last_7_days'] ?? 0)
                    ];
                }

                foreach ($students as $s) {
                    $sid = $s['id'];
                    $last_att = isset($attendance_map[$sid]) ? $attendance_map[$sid]['last_attendance'] : null;
                    $att_count = isset($attendance_map[$sid]) ? (int)$attendance_map[$sid]['attendance_count_last_7_days'] : 0;

                    $result[] = [
                        'id' => $s['id'],
                        'name' => $s['name'] ?? '',
                        'contact' => $s['contact'] ?? '',
                        'parent_name' => $s['parent_name'] ?? '',
                        'batch_id' => $s['batch_id'] ?? '',
                        'student_progress_category' => $s['student_progress_category'] ?? '',
                        'paid_amount' => floatval($s['paid_amount'] ?? 0),
                        'remaining_amount' => floatval($s['remaining_amount'] ?? 0),
                        'status' => $s['status'] ?? '',
                        'last_attendance' => $last_att,
                        'attendance_count_last_7_days' => $att_count
                    ];
                }
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'data' => $result]));
        } catch (Exception $e) {
            log_message('error', 'DashboardController::students_list error: ' . $e->getMessage());
            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Server error']));
        }
    }
}
