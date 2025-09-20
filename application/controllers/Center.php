<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Center extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Center_model');
         $this->load->model('Batch_model');
    }

    public function index() {
        $this->load->view('center_management');
    }

    // public function save() {
    //     $this->output->set_content_type('application/json');
    //     log_message('debug', 'Save method called with data: ' . json_encode($this->input->raw_input_stream));
    //     $data = json_decode($this->input->raw_input_stream, true);

    //     if (!$data) {
    //         $this->output->set_status_header(400);
    //         echo json_encode(['message' => 'Invalid input data']);
    //         return;
    //     }

    //     $result = $this->Center_model->save_center($data);
    //     if ($result) {
    //         log_message('debug', 'Center saved successfully');
    //         echo json_encode(['message' => 'Center added successfully']);
    //     } else {
    //         $this->output->set_status_header(500);
    //         log_message('error', 'Failed to save center');
    //         echo json_encode(['message' => 'Failed to add center']);
    //     }
    // }

    public function get_all() {
        $this->output->set_content_type('application/json');
        log_message('debug', 'get_all method called');
        $centers = $this->Center_model->get_all_centers();
        echo json_encode($centers);
    }

    public function get($id) {
        $this->output->set_content_type('application/json');
        log_message('debug', 'get method called for ID: ' . $id);
        $center = $this->Center_model->get_center($id);
        if ($center) {
            echo json_encode($center);
        } else {
            $this->output->set_status_header(404);
            echo json_encode(['message' => 'Center not found']);
        }
    }

    public function filter() {
        $this->output->set_content_type('application/json');
        log_message('debug', 'filter method called with name: ' . $this->input->post('filterCenterName'));
        $name = $this->input->post('filterCenterName');
        $centers = $this->Center_model->filter_centers($name);
        echo json_encode($centers);
    }


    public function update_facility() {
        $this->output->set_content_type('application/json');
        $data = json_decode($this->input->raw_input_stream, true);
        if (!$data) {
            $this->output->set_status_header(400);
            echo json_encode(['message' => 'Invalid input data']);
            return;
        }
        $result = $this->Center_model->update_facility($data);
        if ($result) {
            echo json_encode(['message' => 'Facility updated successfully']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['message' => 'Failed to update facility']);
        }
    }

    public function update_staff() {
        $this->output->set_content_type('application/json');
        $data = json_decode($this->input->raw_input_stream, true);
        if (!$data) {
            $this->output->set_status_header(400);
            echo json_encode(['message' => 'Invalid input data']);
            return;
        }
        $result = $this->Center_model->update_staff($data);
        if ($result) {
            echo json_encode(['message' => 'Staff updated successfully']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['message' => 'Failed to update staff']);
        }
    }
      // <-----------------------New API for center Managemnet----------------------->

  // ---------- Save Center ----------
    public function saveCenter() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(["status" => "error", "message" => "No data received"]);
            return;
        }

        // ✅ Hash Password
        if (!empty($input['password'])) {
            $input['password'] = password_hash($input['password'], PASSWORD_BCRYPT);
        }

        $id = $this->Center_model->insertData("center_details", $input);

        echo json_encode(["status" => "success", "center_id" => $id]);
    }

    // ---------- Save Batch ----------
    public function saveBatch() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (empty($input['center_id'])) {
            echo json_encode(["status" => "error", "message" => "center_id required"]);
            return;
        }

        $id = $this->Center_model->insertData("batches", $input);

        echo json_encode(["status" => "success", "batch_id" => $id]);
    }

    // ---------- Save Staff ----------
    public function saveStaff() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (empty($input['center_id'])) {
            echo json_encode(["status" => "error", "message" => "center_id required"]);
            return;
        }

        $id = $this->Center_model->insertData("staff", $input);

        echo json_encode(["status" => "success", "staff_id" => $id]);
    }

    // ---------- Save Facility ----------
    // Save Facility + Subtypes into facilities table
public function saveFacility()
{
    // Detect if JSON is sent
    $input = json_decode($this->input->raw_input_stream, true);

    if ($input) {
        // JSON payload
        $centerId     = $input['center_id'] ?? null;
        $facilityName = $input['facility_name'] ?? null;
        $subTypes     = $input['subType'] ?? [];
        $subRents     = $input['subRent'] ?? [];
    } else {
        // Form-data / x-www-form-urlencoded
        $centerId     = $this->input->post('center_id');
        $facilityName = $this->input->post('facility_name');
        $subTypes     = $this->input->post('subType');
        $subRents     = $this->input->post('subRent');
    }

    // Handle array issues
    if (is_array($facilityName)) {
        $facilityName = reset($facilityName);
    }
    if (is_array($centerId)) {
        $centerId = reset($centerId);
    }

    if (empty($facilityName) || empty($centerId)) {
        echo json_encode(["status" => "error", "message" => "Center ID and Facility name are required"]);
        return;
    }

    // If there are subtypes, insert multiple rows
    if (!empty($subTypes) && is_array($subTypes)) {
        foreach ($subTypes as $i => $subType) {
            $data = [
                "center_id"     => $centerId,
                "facility_name" => $facilityName,
                "subtype_name"  => !empty($subType) ? $subType : null,
                "rent_amount"   => isset($subRents[$i]) ? $subRents[$i] : 0,
                "rent_date"     => date("Y-m-d"),
                "created_at"    => date("Y-m-d H:i:s")
            ];
            $this->Center_model->insertFacility($data);
        }
    } else {
        // If no subtypes, insert one row
        $data = [
            "center_id"     => $centerId,
            "facility_name" => $facilityName,
            "subtype_name"  => null,
            "rent_amount"   => 0,
            "rent_date"     => null,
            "created_at"    => date("Y-m-d H:i:s")
        ];
        $this->Center_model->insertFacility($data);
    }

    echo json_encode(["status" => "success", "message" => "Facility added successfully"]);
}


public function getFacilities()
{
    $facilities = $this->Center_model->getFacilities();
    if (!empty($facilities)) {
        foreach ($facilities as $facility) {
            echo "<div class='card p-2 mb-2'>
                    <strong>{$facility->facility_name}</strong><br>
                    <small>Subtypes: {$facility->subtypes}</small>
                  </div>";
        }
    } else {
        echo "<p class='text-center'>No facilities added yet</p>";
    }
}
    public function getBatchesByCenter($center_id) {
    if (empty($center_id)) {
        echo json_encode(["status" => "error", "message" => "center_id required"]);
        return;
    }

    $batches = $this->Center_model->getWhere("batches", ["center_id" => $center_id]);

    if ($batches) {
        echo json_encode(["status" => "success", "data" => $batches]);
    } else {
        echo json_encode(["status" => "error", "message" => "No batches found"]);
    }
}
public function getFacilitiesByCenterId($centerId = null)
{
    if (!$centerId) {
        echo json_encode([
            "status" => "error",
            "message" => "Center ID is required"
        ]);
        return;
    }

    $facilities = $this->Center_model->getFacilitiesByCenterId($centerId);

    if (!empty($facilities)) {
        echo json_encode([
            "status" => "success",
            "data"   => $facilities
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No facilities found for this center"
        ]);
    }
}
public function add_student_facility($data)
{
    return $this->db->insert('student_facilities', $data);
}

     public function getAllcenters() {
        $centers = $this->Center_model->get_all_centers();
        echo json_encode([
            'status' => true,
            'data' => $centers
        ]);
    }

    // GET /api/centers/{id}
    public function center($id = null) {
        if ($id === null) {
            echo json_encode([
                'status' => false,
                'message' => 'ID is required'
            ]);
            return;
        }

        $center = $this->Center_model->get_center_by_id($id);

        if ($center) {
            echo json_encode([
                'status' => true,
                'data' => $center
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Center not found'
            ]);
        }
    }
  public function getCenterById($center_id = null) {
        if ($center_id === null) {
            echo json_encode([
                "status" => "error",
                "message" => "center_id is required"
            ]);
            return;
        }

        // Fetch data
        $center = $this->Center_model->getCenterDetails($center_id);
        $batches = $this->Center_model->getBatchesByCenter($center_id);
        $staff = $this->Center_model->getStaffByCenter($center_id);
        $facilities = $this->Center_model->getFacilitiesByCenter($center_id);

        if (!$center) {
            echo json_encode([
                "status" => "error",
                "message" => "Center not found"
            ]);
            return;
        }

        echo json_encode([
            "status" => "success",
            "center" => $center,
            "batches" => $batches,
            "staff" => $staff,
            "facilities" => $facilities
        ]);
    }

 public function getBatchById($id = null) {
    // Check if batch ID is provided
    if (empty($id)) {
        echo json_encode([
            "status" => false,
            "message" => "Batch ID is required"
        ]);
        return;
    }

    // Query the database for a single batch by ID
    $batch = $this->db->get_where('batches', ['id' => $id])->row_array();

    if (!empty($batch)) {
        echo json_encode([
            "status" => true,
            "data" => $batch
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "No batch found with this ID"
        ]);
    }
}


   public function updateBatch($id = null) {
    header('Content-Type: application/json');

    if ($this->input->method(TRUE) !== 'PUT') {
        echo json_encode([
            "status" => false,
            "message" => "Invalid request method. Use PUT"
        ]);
        return;
    }

    if (empty($id) || !is_numeric($id)) {
        echo json_encode([
            "status" => false,
            "message" => "Invalid or missing batch ID"
        ]);
        return;
    }

    $input = json_decode(file_get_contents("php://input"), true);
    if (!$input) {
        echo json_encode([
            "status" => false,
            "message" => "Invalid JSON input"
        ]);
        return;
    }

    $updateData = [];
    $allowedFields = [
        'batch_name', 'batch_level', 'start_time', 'end_time',
        'start_date', 'end_date', 'duration', 'category'
    ];

    foreach ($allowedFields as $field) {
        if (isset($input[$field])) {
            $updateData[$field] = $input[$field];
        }
    }

    if (empty($updateData)) {
        echo json_encode([
            "status" => false,
            "message" => "No valid fields provided for update"
        ]);
        return;
    }
    ///


    

    // Auto update timestamp
    $updateData['updated_at'] = date('Y-m-d H:i:s');

    $updated = $this->Batch_model->update_batch($id, $updateData);

    if ($updated) {
        // ✅ fetch latest data from DB
        $batch = $this->Batch_model->get_batch($id);

        echo json_encode([
            "status" => true,
            "message" => "Batch updated successfully",
            "data" => $batch   // return updated record
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Failed to update batch or no changes made"
        ]);
    }
}


    // Get existing batch details (to show in modal)
    public function getBatch($id = null) {
        header('Content-Type: application/json');

        if (empty($id) || !is_numeric($id)) {
            echo json_encode([
                "status" => false,
                "message" => "Invalid or missing batch ID"
            ]);
            return;
        }

        $batch = $this->Batch_model->get_batch($id);

        if ($batch) {
            echo json_encode([
                "status" => true,
                "data" => $batch
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "message" => "Batch not found"
            ]);
        }
    }
    public function updateCenter() {
        // Retrieve JSON input from the request body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true); // Decode JSON to associative array

        // Validate the data
        if (empty($data)) {
            $response = ['status' => 'error', 'message' => 'No data provided'];
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($response));
            return;
        }

        // Extract data fields
        $id = $data['id'] ?? null;
        $name = $data['name'] ?? null;
        $center_number = $data['center_number'] ?? null;
        $address = $data['address'] ?? null;
        $rent_amount = $data['rent_amount'] ?? null;
        $rent_paid_date = $data['rent_paid_date'] ?? null;
        $center_timing_from = $data['center_timing_from'] ?? null;
        $center_timing_to = $data['center_timing_to'] ?? null;

        // Validate required fields
        if (!$id || !$name || !$center_number) {
            $response = ['status' => 'error', 'message' => 'Missing required fields'];
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode($response));
            return;
        }

        // Update logic
        $update_data = [
            'name' => $name,
            'center_number' => $center_number,
            'address' => $address,
            'rent_amount' => $rent_amount,
            'rent_paid_date' => $rent_paid_date,
            'center_timing_from' => $center_timing_from,
            'center_timing_to' => $center_timing_to
        ];

        $result = $this->Center_model->updateCenter($id, $update_data);

        // Send response
        if ($result) {
            $response = ['status' => 'success', 'message' => 'Center updated successfully'];
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to update center'];
        }

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
    }

    public function getFacilityById($id) {
        $facility = $this->Center_model->getFacilityById($id);

        if ($facility) {
            echo json_encode([
                "status" => "success",
                "data" => $facility
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Facility not found"
            ]);
        }
    }

    // UPDATE facility by ID
public function updateFacilityById($id) {
    $rawInput = file_get_contents("php://input");
    $input = json_decode($rawInput, true);

    // Debug: Log input
    error_log("Raw input: " . $rawInput);
    error_log("Decoded input: " . print_r($input, true));

    if (!$input) {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
        return;
    }

    $data = [
        "center_id"     => isset($input["center_id"]) ? $input["center_id"] : null,
        "facility_name" => $input["facility_name"] ?? null,
        "subtype_name"  => $input["subtype_name"] ?? null,
        "rent_amount"   => $input["rent_amount"] ?? 0,
        "rent_date"     => $input["rent_date"] ?? null,
        "created_at"    => $input["created_at"] ?? date("Y-m-d H:i:s"),
    ];

    $updated = $this->Center_model->updateFacilityById($id, $data);

    if ($updated) {
        echo json_encode(["status" => "success", "message" => "Facility updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update facility"]);
    }
}

    // DELETE facility by ID
    public function deleteFacilityById($id) {
        $deleted = $this->Center_model->deleteFacilityById($id);

        if ($deleted) {
            echo json_encode(["status" => "success", "message" => "Facility deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete facility"]);
        }
    }
     public function getStaffById($id) {
        $staff = $this->Center_model->getStaffById($id);

        if ($staff) {
            echo json_encode(['status' => 'success', 'data' => $staff]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Staff not found']);
        }
    }

    // ✅ Update staff by ID
    public function updateStaffById($id) {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!$input) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        // Validation: if role is coach, assigned_batch is required
        if (isset($input['role']) && strtolower($input['role']) === 'coach') {
            if (empty($input['assigned_batch'])) {
                echo json_encode(['status' => 'error', 'message' => 'Assigned batch is mandatory for coaches']);
                return;
            }
        }

        // Add updated_at manually
        $input['updated_at'] = date('Y-m-d H:i:s');

        $updated = $this->Center_model->updateStaffById($id, $input);

        if ($updated) {
            echo json_encode(['status' => 'success', 'message' => 'Staff updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update staff']);
        }
    }

    // ✅ Delete staff by ID
    public function deleteStaffById($id) {
        $deleted = $this->Center_model->deleteStaffById($id);

        if ($deleted) {
            echo json_encode(['status' => 'success', 'message' => 'Staff deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete staff']);
        }
    }
    public function deleteCenterbyId($id)
{
    if (empty($id)) {
        return $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => false, 'message' => 'Center ID required']));
    }

    $this->load->model('Center_model');
    $deleted = $this->Center_model->delete_center_by_id($id);

    if ($deleted) {
        $response = ['status' => true, 'message' => 'Center deleted successfully'];
    } else {
        $response = ['status' => false, 'message' => 'Failed to delete center'];
    }

    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));
}

// public function deleteBatchbyId($id)
// {
//     if (empty($id)) {
//         return $this->output
//             ->set_status_header(400)
//             ->set_content_type('application/json')
//             ->set_output(json_encode(['status' => false, 'message' => 'Batch ID required']));
//     }

//     $this->load->model('Batch_model');
//     $deleted = $this->Center_model->delete_batch_by_id($id);

//     if ($deleted) {
//         $response = ['status' => true, 'message' => 'Batch deleted successfully'];
//     } else {
//         $response = ['status' => false, 'message' => 'Failed to delete batch'];
//     }

//     return $this->output
//         ->set_content_type('application/json')
//         ->set_output(json_encode($response));
// }

// //Batch data save in table
//      public function save_batch() {
//         $batch_timings = $this->input->post('batch_timing');
//         $start_dates   = $this->input->post('start_date');
//         $categories    = $this->input->post('batch_category');

//         if (!empty($batch_timings)) {
//             foreach ($batch_timings as $index => $timing) {
//                 $data = [
//                     'batch_timing'   => $timing,
//                     'start_date'     => $start_dates[$index],
//                     'batch_category' => $categories[$index]
//                 ];
//                 $this->Batch_model->add_batch($data);
//             }
            
//         // Redirect or send success response
//         $this->session->set_flashdata('success', 'Batches saved successfully!');
//         redirect('CenterManagement');  
//     }
//         }
//delete batch
public function deleteBatch($id)
{
    $this->db->where('id', $id);
    if ($this->db->delete('batches')) {  
        echo json_encode(["status" => "success", "message" => "Batch deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete batch"]);
    }
}
public function add_batch() {
    $this->load->model('Batch_model');

    // For JSON requests, read raw input
    $json = $this->input->raw_input_stream;
    $data = json_decode($json, true);

    // Validate if JSON decoded properly
    if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
        return;
    }

    // Optional: Map fields if mismatch (e.g., if model expects 'name' not 'batch_name')
    $mapped_data = [
        'center_id' => $data['center_id'],
        'batch_name' => $data['batch_name'],      
        'batch_level' => $data['batch_level'],  
        'start_time' => $data['start_time'],
        'end_time' => $data['end_time'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'category' => $data['category']
    ];

    // Call model (use add_batch or saveBatch; ensure it returns ['status' => 'success/error'])
    if ($this->Batch_model->add_batch($mapped_data)) {  // Or saveBatch($mapped_data)
        echo json_encode(['status' => 'success', 'message' => 'Batch added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add batch']);
    }
}
   

// Save Center Details data
public function save_center() {
    if ($this->input->post()) {
        $data = [
            'name'               => $this->input->post('name'),
            'center_number'      => $this->input->post('center_number'),
            'address'            => $this->input->post('address'),
            'rent_amount'        => $this->input->post('rent_amount'),
            'rent_paid_date'     => $this->input->post('rent_paid_date'),
            'center_timing_from' => $this->input->post('center_timing_from'),
            'center_timing_to'   => $this->input->post('center_timing_to'),
            'password'           => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
        ];

        if ($this->Center_model->insert_center($data)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}

///

    // Show all batches
    public function list_batches() {
        $data['batches'] = $this->Batch_model->get_batches();
        $this->load->view('add_new_center/', $data);
    }


    // Save Batch
    public function save_batch() {
        if ($this->input->post()) {

            $data = [
                'center_id'   => $this->input->post('center_id'),   // hidden field
                'batch_name'  => $this->input->post('batch_name'),
                'batch_level' => $this->input->post('batch_level'),
                'start_time'  => $this->input->post('start_time'),
                'end_time'    => $this->input->post('end_time'),
                'start_date'  => $this->input->post('start_date'),
                'end_date'    => $this->input->post('end_date'),
                'duration'    => $this->input->post('duration'),
                'category'    => $this->input->post('category'),
                'created_at'  => date('Y-m-d H:i:s')
            ];

            $insert_id = $this->Batch_model->insert_batch($data);

            if ($insert_id) {
                echo json_encode(['status' => 'success', 'id' => $insert_id]);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }
}










 







?>