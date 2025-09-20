<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facility extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Facility_model');
    }

    // Update facility
    public function update_facility() {
        $this->output->set_content_type('application/json');
        $data = json_decode($this->input->raw_input_stream, true);
        if (!$data) {
            $this->output->set_status_header(400);
            echo json_encode(['message' => 'Invalid input data']);
            return;
        }
        $result = $this->Facility_model->updateFacilityById($data['id'], $data);
        if ($result) {
            echo json_encode(['message' => 'Facility updated successfully']);
        } else {
            $this->output->set_status_header(500);
            echo json_encode(['message' => 'Failed to update facility']);
        }
    }

    // Save Facility + Subtypes into facilities table
    public function saveFacility() {
        $input = json_decode($this->input->raw_input_stream, true);

        if ($input) {
            $centerId = $input['center_id'] ?? null;
            $facilityName = $input['facility_name'] ?? null;
            $subTypes = $input['subtype_name'] ?? [];
            $subRents = $input['rent_amount'] ?? [];
             $subRents = $input['rent_date'] ?? [];
        } else {
            $centerId = $this->input->post('center_id');
            $facilityName = $this->input->post('facility_name');
            $subTypes = $this->input->post('subtype_name');
            $subRents = $this->input->post('rent_amount');
             $subRents = $input['rent_date'] ?? [];
        }

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

        if (!empty($subTypes) && is_array($subTypes)) {
            foreach ($subTypes as $i => $subType) {
                $data = [
                    "center_id" => $centerId,
                    "facility_name" => $facilityName,
                    "subtype_name" => !empty($subType) ? $subType : null,
                    "rent_amount" => isset($subRents[$i]) ? $subRents[$i] : 0.00,
                    "rent_date" => date("Y-m-d"),
                    "created_at" => date("Y-m-d H:i:s")
                ];
                $this->Facility_model->saveFacility($data);
            }
        } else {
            $data = [
                "center_id" => $centerId,
                "facility_name" => $facilityName,
                "subtype_name" => null,
                "rent_amount" => 0.00,
                "rent_date" => null,
                "created_at" => date("Y-m-d H:i:s")
            ];
            $this->Facility_model->insertFacility($data);
        }

        echo json_encode(["status" => "success", "message" => "Facility added successfully"]);
    }

    // Insert a single facility
    public function insertFacility() {
        $this->output->set_content_type('application/json');
        $input = json_decode($this->input->raw_input_stream, true);

        if (!$input) {
            echo json_encode(["status" => "error", "message" => "Invalid input data"]);
            return;
        }

        if (empty($input['center_id']) || empty($input['facility_name'])) {
            echo json_encode(["status" => "error", "message" => "Center ID and Facility name are required"]);
            return;
        }

        $data = [
            "center_id" => $input['center_id'],
            "facility_name" => $input['facility_name'],
            "subtype_name" => $input['subtype_name'] ?? null,
            "rent_amount" => $input['rent_amount'] ?? 0.00,
            "rent_date" => $input['rent_date'] ?? date("Y-m-d"),
            "created_at" => date("Y-m-d H:i:s")
        ];

        $insertId = $this->Facility_model->insertFacility($data);

        if ($insertId) {
            echo json_encode(["status" => "success", "message" => "Facility inserted successfully", "id" => $insertId]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to insert facility"]);
        }
    }

    // Get all facilities
    public function getFacilities() {
        $facilities = $this->Facility_model->getFacilities();
        if (!empty($facilities)) {
            foreach ($facilities as $facility) {
                echo "<div class='card p-2 mb-2'>
                        <strong>{$facility['facility_name']}</strong><br>
                        <small>Subtype: {$facility['subtype_name']}</small><br>
                        <small>Rent: {$facility['rent_amount']}</small>
                      </div>";
            }
        } else {
            echo "<p class='text-center'>No facilities added yet</p>";
        }
    }

    // Get facilities by center_id
    public function getFacilitiesByCenterId($centerId = null) {
        if (!$centerId) {
            echo json_encode([
                "status" => "error",
                "message" => "Center ID is required"
            ]);
            return;
        }

        $facilities = $this->Facility_model->getFacilitiesByCenterId($centerId);

        if (!empty($facilities)) {
            echo json_encode([
                "status" => "success",
                "data" => $facilities
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No facilities found for this center"
            ]);
        }
    }

    // Get facility by ID
    public function getFacilityById($id) {
        $facility = $this->Facility_model->getFacilityById($id);

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

        error_log("Raw input: " . $rawInput);
        error_log("Decoded input: " . print_r($input, true));

        if (!$input) {
            echo json_encode(["status" => "error", "message" => "Invalid input"]);
            return;
        }

        $data = [
            "center_id" => $input["center_id"] ?? null,
            "facility_name" => $input["facility_name"] ?? null,
            "subtype_name" => $input["subtype_name"] ?? null,
            "rent_amount" => $input["rent_amount"] ?? 0.00,
            "rent_date" => $input["rent_date"] ?? null,
            "updated_at" => date("Y-m-d H:i:s")
        ];

        $updated = $this->Facility_model->updateFacilityById($id, $data);

        if ($updated) {
            echo json_encode(["status" => "success", "message" => "Facility updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update facility"]);
        }
    }

    // DELETE facility by ID
    public function deleteFacilityById($id) {
        $deleted = $this->Facility_model->deleteFacilityById($id);

        if ($deleted) {
            echo json_encode(["status" => "success", "message" => "Facility deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to delete facility"]);
        }
    }

    // Note: getStaffById seems unrelated to the facilities table, so it remains unchanged
    public function getStaffById($id) {
        $staff = $this->Center_model->getStaffById($id);

        if ($staff) {
            echo json_encode(['status' => 'success', 'data' => $staff]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Staff not found']);
        }
    }


    public function get_facilities_history_by_student($id){

          $facility = $this->Facility_model->getFacilityHistoryById($id);

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
}