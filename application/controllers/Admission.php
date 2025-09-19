<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admission extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admission_model');
        $this->load->helper('url');
    }

    public function index()
    {
        $this->load->view('superadmin/new_admission');
    }

    public function receipt()
    {
        $student_id = $this->input->get('student_id');
        if (!$student_id) {
            show_error('Invalid student ID', 400);
        }
        $data['student_id'] = $student_id;
        $this->load->view('superadmin/receipt', $data);
    }

    public function get_centers()
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'get_centers method called');
        $centers = $this->Admission_model->get_all_centers();
        echo json_encode($centers);
    }

    public function get_batches($center_id)
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'get_batches method called for center ID: ' . $center_id);
        $batches = $this->Admission_model->get_batches_by_center($center_id);
        echo json_encode($batches);
    }

    public function get_categories()
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'get_categories method called');
        $categories = ['Beginner', 'Intermediate', 'Advanced'];
        echo json_encode($categories);
    }

    public function get_lockers($center_id)
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'get_lockers method called for center ID: ' . $center_id);
        $lockers = $this->Admission_model->get_lockers_by_center($center_id);
        echo json_encode($lockers);
    }

    public function get_student($student_id)
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'get_student method called for student ID: ' . $student_id);
        if (!is_numeric($student_id) || $student_id <= 0) {
            log_message('error', 'Invalid student ID: ' . $student_id);
            echo json_encode(['success' => false, 'message' => 'Invalid student ID']);
            return;
        }
        $student = $this->Admission_model->get_student_by_id($student_id);
        if ($student) {
            echo json_encode($student);
        } else {
            log_message('error', 'Student not found for ID: ' . $student_id);
            echo json_encode(['success' => false, 'message' => 'Student not found']);
        }
    }

    public function get_students()
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'get_students method called');
        $students = $this->Admission_model->get_students();
        foreach ($students as &$student) {
            // Calculate status based on joining_date + duration and last_attendance
            $expiry_date = date('Y-m-d', strtotime($student['joining_date'] . ' + ' . $student['duration'] . ' months'));
            $today = date('Y-m-d');
            $is_expired = $expiry_date < $today;
            $is_absent = $student['last_attendance'] && (strtotime($today) - strtotime($student['last_attendance'])) / (60 * 60 * 24) >= 5;

            if ($is_expired || $is_absent) {
                $student['status'] = 'Deactive';
                // Update status in database if needed
                $this->db->where('id', $student['id']);
                $this->db->update('students', ['status' => 'Deactive']);
            } else {
                $student['status'] = $student['status'] ?? 'Active';
            }
        }
        echo json_encode($students);
    }

    public function get_deactivated_students()
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'get_deactivated_students method called');
        $students = $this->Admission_model->get_deactivated_students();
        foreach ($students as &$student) {
            $student['status'] = 'Deactive'; // Already filtered by expiration
        }
        echo json_encode($students);
    }

    public function update_student()
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'update_student method called with data: ' . json_encode($this->input->post()));
        $data = [
            'name' => $this->input->post('name'),
            'contact' => $this->input->post('contact'),
            'center_id' => $this->input->post('center_id'),
            'batch_id' => $this->input->post('batch_id'),
            'category' => $this->input->post('category')
        ];
        $student_id = $this->input->post('id');
        $this->db->where('id', $student_id);
        $result = $this->db->update('students', $data);
        echo json_encode(['success' => $result, 'message' => $result ? 'Student updated successfully' : 'Failed to update student']);
    }

    public function delete_student($student_id)
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'delete_student method called for student ID: ' . $student_id);
        $this->db->trans_start();
        $this->db->where('student_id', $student_id);
        $this->db->delete('student_facilities');
        $this->db->where('id', $student_id);
        $result = $this->db->delete('students');
        $this->db->trans_complete();
        echo json_encode(['success' => $result, 'message' => $result ? 'Student deleted successfully' : 'Failed to delete student']);
    }

    public function toggle_status($student_id)
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'toggle_status method called for student ID: ' . $student_id);
        $this->db->select('status');
        $this->db->where('id', $student_id);
        $student = $this->db->get('students')->row_array();

        if ($student) {
            $new_status = $student['status'] === 'Active' ? 'Deactive' : 'Active';
            $this->db->where('id', $student_id);
            $this->db->update('students', ['status' => $new_status]);
            if ($this->db->affected_rows() > 0) {
                echo json_encode(['success' => true, 'status' => $new_status, 'message' => 'Student status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update student status']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
        }
    }

    public function renew_student()
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'renew_student method called with data: ' . json_encode($this->input->post()));

        $input = json_decode(file_get_contents('php://input'), true);
        $student_id = $input['student_id'] ?? null;

        if (!$student_id || !is_numeric($student_id) || $student_id <= 0) {
            log_message('error', 'Missing or invalid student_id in renew_student request: ' . $student_id);
            echo json_encode(['success' => false, 'message' => 'Invalid or missing student ID']);
            return;
        }

        $this->db->trans_start();

        $data = [
            'name' => $input['name'] ?? null,
            'contact' => $input['contact'] ?? null,
            'center_id' => $input['center_id'] ?? null,
            'batch_id' => $input['batch_id'] ?? null,
            'category' => $input['category'] ?? null,
            'total_fees' => $input['total_fees'] ?? 0,
            'paid_amount' => $input['paid_amount'] ?? 0,
            'remaining_amount' => ($input['total_fees'] ?? 0) - ($input['paid_amount'] ?? 0),
            'payment_method' => $input['payment_method'] ?? null,
            'joining_date' => date('Y-m-d'),
            'duration' => $input['duration'] ?? null,
            'admission_date' => date('Y-m-d'),
            'status' => 'Active'
        ];

        // Validate required fields
        $required_fields = ['name', 'contact', 'center_id', 'batch_id', 'category', 'total_fees', 'payment_method', 'duration'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                log_message('error', "Missing or empty field: $field");
                $this->db->trans_rollback();
                echo json_encode(['success' => false, 'message' => "Missing or invalid $field"]);
                return;
            }
        }

        // Validate numeric fields
        if (!is_numeric($data['total_fees']) || $data['total_fees'] <= 0 || !is_numeric($data['paid_amount']) || $data['paid_amount'] < 0) {
            log_message('error', 'Invalid numeric values for total_fees or paid_amount');
            $this->db->trans_rollback();
            echo json_encode(['success' => false, 'message' => 'Invalid fees or paid amount']);
            return;
        }
        if ($data['paid_amount'] > $data['total_fees']) {
            log_message('error', 'Paid amount exceeds total fees');
            $this->db->trans_rollback();
            echo json_encode(['success' => false, 'message' => 'Paid amount cannot exceed total fees']);
            return;
        }

        $this->db->where('id', $student_id);
        $result = $this->db->update('students', $data);

        if (!$result) {
            log_message('error', 'Failed to update students table: ' . $this->db->error()['message']);
            $this->db->trans_rollback();
            echo json_encode(['success' => false, 'message' => 'Failed to update student record: ' . $this->db->error()['message']]);
            return;
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Transaction failed: ' . $this->db->error()['message']);
            echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $this->db->error()['message']]);
        } else {
            $student = $this->Admission_model->get_student_by_id($student_id);
            echo json_encode(['success' => true, 'student' => $student, 'message' => 'Admission renewed successfully']);
        }
    }

    public function export_students()
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'export_students method called');
        $students = $this->Admission_model->get_students();
        echo json_encode($students);
    }

    public function saveold()
    {
        $this->output->set_content_type('application/json');
        log_message('debug', 'Save admission called with data: ' . $this->input->raw_input_stream);
        $data = json_decode($this->input->raw_input_stream, true);

        if (!$data) {
            $this->output->set_status_header(400);
            echo json_encode(['success' => false, 'message' => 'Invalid input data']);
            return;
        }

        $student_id = $this->Admission_model->save_admission($data);
        if ($student_id) {
            log_message('debug', 'Admission saved successfully with student ID: ' . $student_id);
            echo json_encode(['success' => true, 'student_id' => $student_id]);
        } else {
            $this->output->set_status_header(500);
            log_message('error', 'Failed to save admission');
            echo json_encode(['success' => false, 'message' => 'Failed to save admission']);
        }
    }
    public function save()
    {
        $this->output->set_content_type('application/json');

        // Grab form-data (POST)
        $data = $this->input->post();

        log_message('debug', 'Save admission called with form-data: ' . print_r($data, true));

        if (empty($data)) {
            $this->output->set_status_header(400);
            echo json_encode(['success' => false, 'message' => 'No form data received']);
            return;
        }

        // Save admission
        $student_id = $this->Admission_model->save_admission($data);

        if ($student_id) {
            log_message('debug', 'Admission saved successfully with student ID: ' . $student_id);
            echo json_encode(['success' => true, 'student_id' => $student_id]);
        } else {
            $this->output->set_status_header(500);
            log_message('error', 'Failed to save admission');
            echo json_encode(['success' => false, 'message' => 'Failed to save admission']);
        }
    }

    public function get_deactive_students()
    {
        header('Content-Type: application/json');

        $students = $this->Admission_model->get_deactive_students();

        if ($students) {
            echo json_encode([
                'status' => 'success',
                'count' => count($students),
                'data' => $students
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No deactive students found'
            ]);
        }
    }
    public function update_joining_date()
    {
        $student_id = $this->input->post('student_id');
        $joining_date = $this->input->post('joining_date');

        if ($student_id && $joining_date) {
            $this->db->where('id', $student_id);
            $this->db->update('students', ['joining_date' => $joining_date]);

            $this->session->set_flashdata('success', 'Joining date updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Invalid data.');
        }
        redirect('superadmin/re_admission');
    }
    public function expiring_students()
    {
        $students = $this->Admission_model->get_students_expiring_soon();

        if (!empty($students)) {
            $response = [
                "status" => "success",
                "data" => $students
            ];
        } else {
            $response = [
                "status" => "success",
                "data" => [],
                "message" => "No students expiring within 10 days"
            ];
        }

        echo json_encode($response);
    }

    public function get_facility_by_student_id($student_id = null)
    {
        if (!$student_id || !is_numeric($student_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid or missing student_id'
            ]);
            return;
        }

        $data = $this->Admission_model->get_facility_by_student_id($student_id);

        if ($data) {
            echo json_encode([
                'status' => 'success',
                'student_id' => $student_id,
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'student_id' => $student_id,
                'data' => []
            ]);
        }
    }




    public function renewaddmission()
    {



        $student_id = $this->input->post('student_id');

        if (empty($student_id)) {
            echo json_encode(["status" => "error", "message" => "Invalid Student ID"]);
            return;
        }


        $studentData = $this->db->get_where("students", ["id" => $student_id])->row_array();

        if ($studentData) {

            $historyData = $studentData;
            unset($historyData['id']);
            $historyData['student_id'] = $student_id;
            $historyData['purpose'] = 'renew';
            $historyData['updated_at'] = date("Y-m-d H:i:s");


            $this->db->insert("student_addmission_history", $historyData);
        }


        $updateData = [];



        if ($this->input->post('studentName'))
            $updateData["name"] = $this->input->post('studentName');
        if ($this->input->post('parentName'))
            $updateData["parent_name"] = $this->input->post('parentName');
        if ($this->input->post('email'))
            $updateData["email"] = $this->input->post('email');
        if ($this->input->post('address'))
            $updateData["address"] = $this->input->post('address');
        if ($this->input->post('contact'))
            $updateData["contact"] = $this->input->post('contact');
        if ($this->input->post('center_id'))
            $updateData["center_id"] = $this->input->post('center_id');
        // if ($this->input->post('branch_id'))
        //     $updateData["branch_id"] = $this->input->post('branch_id');


        if ($this->input->post('batch_id'))
            $updateData["batch_id"] = $this->input->post('batch_id');

        if ($this->input->post('course_duration'))
            $updateData["course_duration"] = $this->input->post('course_duration');

        $updateData["remaining_amount"] = $this->input->post('newRemaining');


        if ($this->input->post('level'))
            $updateData["student_progress_category"] = $this->input->post('level');

        if ($this->input->post('baseFees'))
            $updateData["course_fees"] = $this->input->post('baseFees');


        if ($this->input->post('facilities_amount'))
            $updateData["additional_fees"] = $this->input->post('facilities_amount');


        if ($this->input->post('total_amount'))
            $updateData["total_fees"] = $this->input->post('total_amount');

        if ($this->input->post('paid_fees'))
            $updateData["paid_amount"] = $this->input->post('paid_fees');






        if ($this->input->post('payment_mode'))
            $updateData["payment_method"] = $this->input->post('payment_mode');
        if ($this->input->post('join_date'))
            $updateData["joining_date"] = $this->input->post('join_date');


        $updateData["status"] = "Deactive";


        // if ($this->input->post('expiry_date'))
        //     $updateData["admission_date"] = $this->input->post('expiry_date');



        $this->db->where("id", $student_id);
        $success = $this->db->update("students", $updateData);

        if ($success) {

            $facilities = $this->input->post('facilities');


            $existingFacilities = $this->db->get_where("student_facilities", ["student_id" => $student_id])->result_array();

            if (!empty($existingFacilities)) {
                foreach ($existingFacilities as $facility) {
                    // Prepare history entry
                    $historyFacility = [
                        "student_id" => $student_id,
                        "facility_name" => $facility['facility_name'],
                        "details" => $facility['details'],
                        "amount" => $facility['amount'],
                        "created_at" => $facility['created_at'],
                        "purpose" => 'renew',
                        "history_created_at" => date("Y-m-d H:i:s")
                    ];

                    $this->db->insert("student_facilities_history", $historyFacility);
                }

                // Delete old facilities
                $this->db->where("student_id", $student_id)->delete("student_facilities");
            }

            // Insert new facilities
            if (!empty($facilities) && is_array($facilities)) {
                foreach ($facilities as $facility) {
                    if (!empty($facility['name']) && !empty($facility['amount'])) {
                        $facilityData = [
                            "student_id" => $student_id,
                            "facility_name" => $facility['name'],
                            "details" => $facility['subtype'] ?? "N/A",
                            "amount" => $facility['amount'],
                            "created_at" => date("Y-m-d H:i:s")
                        ];
                        $this->db->insert("student_facilities", $facilityData);
                    }
                }
            }

            echo json_encode([
                "status" => "success",
                "message" => "Student details updated and facilities history recorded successfully."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to update student."
            ]);
        }
    }



    // public function mark($token)
    // {
    //     $student = $this->db->get_where('students', ['unique_token' => $token])->row();

    //     $today = date('Y-m-d');
    //     $message = "";
    //     $type = "danger";

    //     if (!$student) {
    //         $message = "❌ Invalid or expired attendance link!";
    //     } else {

    //         $existing = $this->db->get_where('attendance', [
    //             'student_id' => $student->id,
    //             'date' => $today
    //         ])->row();

    //         if ($existing) {
    //             $message = "⚠️ Attendance already marked today for <b>{$student->name}</b>";
    //         } else {

    //             $attendance_data = [
    //                 'student_id' => $student->id,
    //                 'date' => $today,
    //                 'time' => date('H:i:s'),
    //                 'status' => 'present'
    //             ];

    //             $this->db->insert('attendance', $attendance_data);

    //             $message = "✅ Attendance marked successfully for <b>{$student->name}</b>";
    //             $type = "success";
    //         }
    //     }

    //     $data = [
    //         'student' => $student,
    //         'message' => $message,
    //         'type' => $type
    //     ];

    //     $this->load->view('superadmin/attendance_result', $data);
    // }


    public function mark($token)
    {
        $data['token'] = $token;
        $this->load->view('superadmin/attendance_location', $data);
    }


    public function mark_process($token)
    {

        $student = $this->db->get_where('students', ['unique_token' => $token])->row();
        $today = date('Y-m-d');
        $message = "";
        $type = "danger";



        if (!$student) {
            $message = "❌ Invalid or expired attendance link!";
        } else {
            $user_lat = $this->input->post('latitude');
            $user_lng = $this->input->post('longitude');


            if (!$user_lat || !$user_lng) {
                $message = "⚠️ Location access required.";
            } else {

                // $center = $this->db->get_where('centers', ['id' => $student->center_id])->row();

                // if ($center) {
                $distance = $this->calculate_distance($user_lat, $user_lng, 18.6060, 73.7527);



                if ($distance > 0.4) {
                    $message = "⚠️ Too far from center (" . round($distance * 1000) . "m).";
                } else {

                    $existing = $this->db->get_where('attendance', [
                        'student_id' => $student->id,
                        'date' => $today
                    ])->row();

                    if ($existing) {
                        $message = "⚠️ Already marked today for <b>{$student->name}</b>";
                    } else {
                        $this->db->insert('attendance', [
                            'student_id' => $student->id,
                            'date' => $today,
                            'time' => date('H:i:s'),
                            'status' => 'present'
                        ]);
                        $message = "✅ Attendance marked successfully for <b>{$student->name}</b>  <b>(" . round($distance * 1000) . "m)</b> ";
                        $type = "success";
                    }
                }
                // }
            }
        }

        $data = [
            'student' => $student,
            'message' => $message,
            'type' => $type
        ];

        $this->load->view('superadmin/attendance_result', $data);
    }

    private function calculate_distance($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth_radius * $c; // distance in KM
    }




}
?>