<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Facility_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Save a new facility
    public function saveFacility($input)
    {
        if (empty($input['center_id']) || empty($input['facility_name'])) {
            return ["status" => "error", "message" => "Center ID and Facility Name are required"];
        }

        if (!isset($input['subtypes']) || !is_array($input['subtypes'])) {
            return ["status" => "error", "message" => "Subtypes must be an array"];
        }

        foreach ($input['subtypes'] as $sub) {
            $data = [
                "center_id" => $input['center_id'],
                "facility_name" => $input['facility_name'],
                "subtype_name" => $sub['subtype_name'],
                "rent_amount" => $sub['rent_amount'] ?? 0.00,
                "rent_date" => $sub['rent_date'] ?? date("Y-m-d"),
                "created_at" => date("Y-m-d H:i:s")
            ];
            $this->db->insert("facilities", $data);
        }

        return ["status" => "success", "message" => "Facility saved"];
    }

    // Insert a single facility
    public function insertFacility($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('facilities', $data);
        return $this->db->insert_id();
    }

    // Update facility by ID
    public function updateFacilityById($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('facilities', $data);
    }

    // Get all facilities
    public function getFacilities()
    {
        $query = $this->db->get('facilities');
        return $query->result_array();
    }

    // Get facilities by center_id
    public function getFacilitiesByCenterId($centerId)
    {
        return $this->db->where("center_id", $centerId)
            ->get("facilities")
            ->result_array();
    }

    // Get facility by ID
    public function getFacilityById($id)
    {
        return $this->db->get_where('facilities', ['id' => $id])->row_array();
    }

    // Delete facility by ID
    public function deleteFacilityById($id)
    {
        return $this->db->delete('facilities', ['id' => $id]);
    }
    public function get_facilities_by_student($student_id)
    {
        // Adjust column name according to your DB schema linking facilities to students
        $this->db->where('center_id', $student_id);
        $query = $this->db->get('facilities');
        return $query->result_array();
    }


    public function get_facilities_history_by_student($id){
        return $this->db->get_where('student_facilities_history', ['student_id' => $id])->result_array();
    }





}
