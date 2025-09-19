<?php
class Admission_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_centers()
    {
        return $this->db->get('center_details')->result_array();
    }

    public function get_batches_by_center($center_id)
    {
        $this->db->select('id,batch_name, start_time,end_time, category, start_date');
        $this->db->where('center_id', $center_id);
        $batches = $this->db->get('batches')->result_array();



        return $batches;
    }

    public function get_lockers_by_center($center_id)
    {
        $this->db->select('locker_no, rent as price, IFNULL((SELECT COUNT(*) FROM student_facilities sf WHERE sf.details LIKE CONCAT("%Locker No: ", f.locker_no, "%") AND sf.facility_name = "Locker"), 0) as is_booked');
        $this->db->from('facilities f');
        $this->db->where('f.center_id', $center_id);
        $this->db->where('f.type', 'locker');
        return $this->db->get()->result_array();
    }

    public function get_students()
    {
        $this->db->select('s.*, c.name as center_name, b.timing as batch_timing');
        $this->db->from('students s');
        $this->db->join('centers c', 's.center_id = c.id', 'left');
        $this->db->join('batches b', 's.batch_id = b.id', 'left');
        return $this->db->get()->result_array();
    }

    public function get_deactivated_students()
    {
        $this->db->select('s.*, c.name as center_name, b.timing as batch_timing, b.category as batch_category');
        $this->db->select('(SELECT GROUP_CONCAT(facility_name) FROM student_facilities sf WHERE sf.student_id = s.id) as facilities');
        $this->db->from('students s');
        $this->db->join('centers c', 's.center_id = c.id', 'left');
        $this->db->join('batches b', 's.batch_id = b.id', 'left');
        $this->db->where('DATE_ADD(s.joining_date, INTERVAL s.duration MONTH) < NOW()');
        return $this->db->get()->result_array();
    }

    public function get_student_by_idold($student_id)
    {
        if (!is_numeric($student_id) || $student_id <= 0) {
            log_message('error', 'Invalid student ID provided: ' . $student_id);
            return null;
        }
        $this->db->select('s.*, c.name as center_name, b.start_time as batch_timing, b.category as batch_category');
        $this->db->from('students s');
        $this->db->join('center_details c', 's.center_id = c.id', 'left');
        $this->db->join('batches b', 's.batch_id = b.id', 'left');
        $this->db->where('s.id', $student_id);

        $student = $this->db->get()->row_array();

        if ($student) {
            $this->db->select('facility_name as name, details, amount');
            $this->db->from('student_facilities');
            $this->db->where('student_id', $student_id);
            $student['facilities'] = $this->db->get()->result_array();
        } else {
            log_message('error', 'No student found for ID: ' . $student_id);
        }

        return $student;
    }

    public function get_student_by_id($student_id)
    {
        if (!is_numeric($student_id) || $student_id <= 0) {
            log_message('error', 'Invalid student ID provided: ' . $student_id);
            return null;
        }

        // Step 1: Get student details with center and batch
        $this->db->select('
        s.*, 
        c.name as center_name, 
        b.start_time as batch_start_time, 
        b.end_time as batch_end_time, 
        b.category as batch_category,
        b.batch_name as batch_name,
        b.duration as duration
    ');
        $this->db->from('students s');
        $this->db->join('center_details c', 's.center_id = c.id', 'left');
        $this->db->join('batches b', 's.batch_id = b.id', 'left');
        $this->db->where('s.id', $student_id);

        $student = $this->db->get()->row_array();

        if ($student) {
            // Step 2: Get student facilities
            $this->db->select('facility_name as name, details, amount');
            $this->db->from('student_facilities');
            $this->db->where('student_id', $student_id);
            $student['facilities'] = $this->db->get()->result_array();

            // Step 3: Get only the coach for this student's center
            $this->db->select('staff_name');
            $this->db->from('staff');
            $this->db->where('center_id', $student['center_id']);
            $this->db->where('role', 'coach');
            $coach = $this->db->get()->row_array();
            $student['coach'] = $coach['staff_name'] ?? null;

            // Step 4: Get coordinator details
            $this->db->select('name, mobile, email');
            $this->db->from('coordinator');
            $coordinator = $this->db->get()->row_array();
            $student['coordinator_name'] = $coordinator['name'] ?? null;
            $student['coordinator_phone'] = $coordinator['mobile'] ?? null;
            $student['coordinator_email'] = $coordinator['email'] ?? null;

            // Step 5: Get attendance link using contact (not id)
            if (!empty($student['contact'])) {
                $this->db->select('attendace_link');
                $this->db->from('student_attendencelink');
                $this->db->where('contact', $student['contact']);
                $attendance = $this->db->get()->row_array();
                $student['attendance_link'] = $attendance['attendace_link'] ?? null;
            } else {
                $student['attendance_link'] = null;
            }

        } else {
            log_message('error', 'No student found for ID: ' . $student_id);
        }

        return $student;
    }






    public function save_admission($data)
    {
        $this->db->trans_start();

        // Insert student data
        $student_data = array(
            'name' => $data['studentName'],
            'contact' => $data['contact'],
            'parent_name' => $data['parentName'],
            'emergency_contact' => $data['emergencyContact'],
            'email' => $data['email'],
            'dob' => $data['dob'],
            'address' => $data['address'],
            'center_id' => $data['center'],
            'batch_id' => $data['batch'],
            'course_fees' => $data['courseFees'],
            'student_progress_category' => $data['category'],
            'additional_fees' => $data['additionalFees'],
            'total_fees' => $data['totalFees'],
            'paid_amount' => $data['paidAmount'],
            'remaining_amount' => $data['remainingAmount'],
            'payment_method' => $data['paymentMethod'],
            'admission_date' => $data['admissionDate'],
            'joining_date' => $data['joiningDate'],
            'course_duration' => $data['course_duration'] ?? null, // NEW

            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('students', $student_data);
        $student_id = $this->db->insert_id();


        // Generate unique token
        $unique_token = bin2hex(random_bytes(16)); // 32-char random token

        // Build attendance link
        $attendance_link = base_url("Admission/mark/" . $unique_token);

        // Update student record with token & link
        $this->db->where('id', $student_id);
        $this->db->update('students', [
            'unique_token' => $unique_token,
            'attendace_link' => $attendance_link
        ]);


        // Insert selected facilities
        if (!empty($data['facilities'])) {
            foreach ($data['facilities'] as $facility) {
                $facility_data = array(
                    'student_id' => $student_id,
                    'facility_name' => $facility['name'],
                    'details' => $facility['details'],
                    'amount' => $facility['amount'],
                    'created_at' => date('Y-m-d H:i:s')
                );
                $this->db->insert('student_facilities', $facility_data);
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return $student_id;
    }


    public function get_deactive_students()
    {
        $this->db->select('id, name, contact, center_id, batch_id, total_fees, paid_amount, remaining_amount, joining_date, status');
        $this->db->from('students');
        $this->db->where('status', 'Deactive');
        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_students_expiring_soon()
    {
        $sql = "
        SELECT 
            s.*,
            DATE_ADD(s.joining_date, INTERVAL s.course_duration * 30 DAY) AS expiry_date
        FROM students s
        WHERE DATE_ADD(s.joining_date, INTERVAL s.course_duration * 30 DAY) <= DATE_ADD(CURDATE(), INTERVAL 20 DAY)
    ";

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function get_facility_by_student_id($student_id)
    {
        return $this->db
            ->where('student_id', $student_id)
            ->order_by('id', 'ASC')
            ->get('student_facilities')
            ->result_array();
    }
}
?>