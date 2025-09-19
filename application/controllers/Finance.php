<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Finance extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();   // uses application/config/database.php
    $this->load->helper('url');
    // $this->load->library('session'); // enable if you need auth checks
  }

  public function index()
  {
    // Defensive: prepare return variables
    $rows = [];
    $grand = [
      'week' => 0.0,
      'month' => 0.0,
      'year' => 0.0,
      'stu_alltime' => 0.0,
      'fac_alltime' => 0.0,
    ];
    $grand_alltime = 0.0;
    $db_error_message = null;

    // SQL: aggregate students & facilities per center (left join to center_details so every center shows)
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
      $query = $this->db->query($sql);
      $rows = $query->result_array();

      // Normalize & compute derived fields for every row
      foreach ($rows as &$r) {
        // safe casts with default 0.0
        $r['stu_weekly']  = isset($r['stu_weekly'])  ? (float)$r['stu_weekly']  : 0.0;
        $r['stu_monthly'] = isset($r['stu_monthly']) ? (float)$r['stu_monthly'] : 0.0;
        $r['stu_yearly']  = isset($r['stu_yearly'])  ? (float)$r['stu_yearly']  : 0.0;
        $r['stu_total']   = isset($r['stu_total'])   ? (float)$r['stu_total']   : 0.0;
        $r['fac_weekly']  = isset($r['fac_weekly'])  ? (float)$r['fac_weekly']  : 0.0;
        $r['fac_monthly'] = isset($r['fac_monthly']) ? (float)$r['fac_monthly'] : 0.0;
        $r['fac_yearly']  = isset($r['fac_yearly']) ? (float)$r['fac_yearly']  : 0.0;
        $r['fac_total']   = isset($r['fac_total'])   ? (float)$r['fac_total']   : 0.0;

        // overlapping windows (student + facility within that window)
        $r['week']  = $r['stu_weekly'] + $r['fac_weekly'];
        $r['month'] = $r['stu_monthly'] + $r['fac_monthly'];
        $r['year']  = $r['stu_yearly'] + $r['fac_yearly'];

        // ALL-TIME must include both student + facility totals
        $r['alltime'] = $r['stu_total'] + $r['fac_total'];

        // accumulate grand totals
        $grand['week'] += $r['week'];
        $grand['month'] += $r['month'];
        $grand['year'] += $r['year'];
        $grand['stu_alltime'] += $r['stu_total'];
        $grand['fac_alltime'] += $r['fac_total'];
      }
      unset($r);

      $grand_alltime = $grand['stu_alltime'] + $grand['fac_alltime'];
    } catch (Exception $e) {
      // DB error - keep $rows empty and surface error message to view
      $rows = [];
      $db_error_message = $e->getMessage();
      // optional: log error
      log_message('error', 'Finance SQL error: ' . $db_error_message);
    }

    // pass compact data to view
    $data = [
      'rows' => $rows,
      'grand' => $grand,
      'grand_alltime' => $grand_alltime,
      'db_error_message' => $db_error_message,
    ];

    $this->load->view('superadmin/finance', $data);
  }

  // Optional details route (if you want center details)
  public function details($center_id = null)
  {
    if ($center_id === null) show_404();
    $center = $this->db->get_where('center_details', ['id' => (int)$center_id])->row_array();
    $students = $this->db->get_where('students', ['center_id' => (int)$center_id])->result_array();
    $this->db->select('sf.*, st.name as student_name');
    $this->db->from('student_facilities sf');
    $this->db->join('students st', 'st.id=sf.student_id', 'left');
    $this->db->where('st.center_id', (int)$center_id);
    $facilities = $this->db->get()->result_array();

    $this->load->view('superadmin/finance_details', compact('center', 'students', 'facilities'));
  }

  /**
   * AJAX endpoint: returns a JSON summary for a center.
   * Accepts GET or POST center_id.
   * Responds:
   *  { status: 'success', data: { center_id, center_name, total_students, active_students, students_with_due, total_due, total_paid, last_attendance } }
   */
  public function get_center_summary($center_id = null)
  {
    // accept center_id as GET/POST or parameter
    $cid = $this->input->get_post('center_id', true);
    if (empty($cid) && !empty($center_id)) {
      $cid = $center_id;
    }

    if (empty($cid) || !is_numeric($cid)) {
      return $this->output
        ->set_status_header(400)
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid center id']));
    }

    $cid = (int) $cid;

    try {
      // center name
      $center = $this->db->select('id, name')->where('id', $cid)->get('center_details')->row();
      if (!$center) {
        return $this->output
          ->set_status_header(404)
          ->set_content_type('application/json')
          ->set_output(json_encode(['status' => 'error', 'message' => 'Center not found']));
      }

      // total students
      $total_students = (int) $this->db->where('center_id', $cid)->count_all_results('students');

      // active students (status = 'Active')
      $this->db->where('center_id', $cid);
      $this->db->where('status', 'Active');
      $active_students = (int) $this->db->count_all_results('students');

      // students with pending fees and total remaining amount
      $this->db->select('COUNT(*) as cnt, COALESCE(SUM(remaining_amount),0) as total_due', false);
      $this->db->where('center_id', $cid);
      $this->db->where('remaining_amount >', 0);
      $due_row = $this->db->get('students')->row_array();
      $students_with_due = isset($due_row['cnt']) ? (int)$due_row['cnt'] : 0;
      $total_due = isset($due_row['total_due']) ? (float)$due_row['total_due'] : 0.0;

      // total paid amount for this center
      $this->db->select('COALESCE(SUM(paid_amount),0) as total_paid', false);
      $this->db->where('center_id', $cid);
      $paid_row = $this->db->get('students')->row_array();
      $total_paid = isset($paid_row['total_paid']) ? (float)$paid_row['total_paid'] : 0.0;

      // last attendance (max)
      $this->db->select('MAX(last_attendance) as last_attendance', false);
      $this->db->where('center_id', $cid);
      $la_row = $this->db->get('students')->row_array();
      $last_attendance = $la_row['last_attendance'] ?? null;

      $data = [
        'center_id' => $center->id,
        'center_name' => $center->name,
        'total_students' => $total_students,
        'active_students' => $active_students,
        'students_with_due' => $students_with_due,
        'total_due' => (float)$total_due,
        'total_paid' => (float)$total_paid,
        'last_attendance' => $last_attendance
      ];

      return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'success', 'data' => $data]));
    } catch (Exception $e) {
      log_message('error', 'Finance::get_center_summary error: ' . $e->getMessage());
      return $this->output
        ->set_status_header(500)
        ->set_content_type('application/json')
        ->set_output(json_encode(['status' => 'error', 'message' => 'Server error']));
    }
  }
}
