<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Center_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create / save center with related data (batches, facilities, staff)
     * Kept from your original file
     */
    public function save_center($data)
    {
        $this->db->trans_start();

        // Insert center
        $center = array(
            'name' => $data['center']['name'],
            'timing' => $data['center']['timing'],
            'rent' => $data['center']['rent'],
            'rent_date' => $data['center']['rent_date'],
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('centers', $center);
        $center_id = $this->db->insert_id();

        // Insert batches
        $batch_ids = [];
        foreach ($data['batches'] as $batch) {
            $batch_data = array(
                'center_id' => $center_id,
                'timing' => $batch['timing'],
                'start_date' => $batch['start_date'],
                'category' => $batch['category'],
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('batches', $batch_data);
            $batch_ids[$batch['timing']] = $this->db->insert_id();
        }

        // Insert facilities
        foreach ($data['facilities'] as $facility) {
            $facility_data = array(
                'center_id' => $center_id,
                'type' => $facility['type'],
                'locker_no' => $facility['locker_no'] ?: null,
                'rent' => $facility['rent'],
                'rent_date' => $facility['rent_date'],
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('facilities', $facility_data);
        }

        // Insert staff
        foreach ($data['staff'] as $staff) {
            $staff_data = array(
                'center_id' => $center_id,
                'category' => $staff['category'],
                'name' => $staff['name'],
                'timing' => $staff['timing'],
                'join_date' => $staff['join_date'],
                'batch_id' => isset($batch_ids[$staff['batch_timing']]) ? $batch_ids[$staff['batch_timing']] : null,
                'contact' => $staff['contact'],
                'address' => $staff['address'],
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('staff', $staff_data);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Get full center info (center + batches + facilities + staff)
     * Returns arrays (center as array + lists)
     */
    public function get_center($id)
    {
        $center = $this->db->get_where('center_details', ['id' => $id])->row_array();
        $batches = $this->db->get_where('batches', ['center_id' => $id])->result_array();
        $facilities = $this->db->get_where('facilities', ['center_id' => $id])->result_array();
        $staff = $this->db->get_where('staff', ['center_id' => $id])->result_array();

        // Map batch timing to staff
        foreach ($staff as &$s) {
            $batch = array_filter($batches, function ($b) use ($s) {
                return $b['id'] == $s['batch_id'];
            });
            $s['batch_timing'] = !empty($batch) ? reset($batch)['timing'] : '';
        }

        return [
            'center' => $center,
            'batches' => $batches,
            'facilities' => $facilities,
            'staff' => $staff
        ];
    }

    public function filter_centers($name)
    {
        $this->db->like('name', $name);
        return $this->db->get('centers')->result_array();
    }

    public function update_facility($data)
    {
        $this->db->trans_start();
        $facility_data = array(
            'type' => $data['type'],
            'locker_no' => $data['locker_no'] ?: null,
            'rent' => $data['rent'],
            'rent_date' => $data['rent_date']
        );
        $this->db->where('id', $data['id']);
        $this->db->update('facilities', $facility_data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update_staff($data)
    {
        $this->db->trans_start();
        $batch = $this->db->get_where('batches', ['timing' => $data['batch_timing']])->row_array();
        $staff_data = array(
            'category' => $data['category'],
            'name' => $data['name'],
            'timing' => $data['timing'],
            'join_date' => $data['join_date'],
            'batch_id' => $batch ? $batch['id'] : null,
            'contact' => $data['contact'],
            'address' => $data['address']
        );
        $this->db->where('id', $data['id']);
        $this->db->update('staff', $staff_data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // ----------------------- New APIs for center Management -----------------------

    public function saveBatch($input)
    {
        if (empty($input['center_id']) || empty($input['batch_name'])) {
            return ["status" => "error", "message" => "Center ID and Batch Name are required"];
        }

        $data = [
            "center_id" => $input['center_id'],
            "batch_name" => $input['batch_name'],
            "batch_level" => $input['batch_level'] ?? null,
            "start_time" => $input['start_time'] ?? null,
            "end_time" => $input['end_time'] ?? null,
            "start_date" => $input['start_date'] ?? null,
            "end_date" => $input['end_date'] ?? null,
            "duration" => $input['duration'] ?? null,
            "category" => $input['category'] ?? null,
            "created_at" => date("Y-m-d H:i:s")
        ];

        $this->db->insert("batches", $data);
        return ["status" => "success", "message" => "Batch saved"];
    }

    public function saveStaff($input)
    {
        if (empty($input['center_id']) || empty($input['staff_name']) || empty($input['contact_no'])) {
            return ["status" => "error", "message" => "Center ID, Staff Name, and Contact No are required"];
        }

        $data = [
            "center_id" => $input['center_id'],
            "staff_name" => $input['staff_name'],
            "contact_no" => $input['contact_no'],
            "role" => $input['role'] ?? null,
            "joining_date" => $input['joining_date'] ?? null,
            "assigned_batch" => $input['assigned_batch'] ?? null,
            "coach_level" => $input['coach_level'] ?? null,
            "coach_category" => $input['coach_category'] ?? null,
            "coach_duration" => $input['coach_duration'] ?? null,
            "created_at" => date("Y-m-d H:i:s")
        ];

        $this->db->insert("staff", $data);
        return ["status" => "success", "message" => "Staff saved"];
    }

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
                "subtype_name" => $sub['name'],
                "rent_amount" => $sub['rent'] ?? 0,
                "rent_date" => date("Y-m-d"),
                "created_at" => date("Y-m-d H:i:s")
            ];
            $this->db->insert("facilities", $data);
        }

        return ["status" => "success", "message" => "Facility saved"];
    }

    public function insertData($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function getWhere($table, $conditions = [])
    {
        $query = $this->db->get_where($table, $conditions);
        return $query->result_array();
    }

    public function getFacilitiesByCenterId($centerId)
    {
        return $this->db->where("center_id", $centerId)
            ->get("facilities")
            ->result_array();
    }

    // Optional: get single row
    public function getRow($table, $conditions = [])
    {
        $query = $this->db->get_where($table, $conditions);
        return $query->row_array();
    }

    public function insertFacility($data)
    {
        return $this->db->insert("facilities", $data);
    }

    public function insertSubFacility($data)
    {
        return $this->db->insert("facility_subtypes", $data);
    }

    public function getFacilities()
    {
        $this->db->select("f.id, f.facility_name, GROUP_CONCAT(CONCAT(s.subtype, ' (₹', s.rent, ')')) as subtypes");
        $this->db->from("facilities f");
        $this->db->join("facility_subtypes s", "s.facility_id = f.id", "left");
        $this->db->group_by("f.id");
        return $this->db->get()->result();
    }

    // public function get_all_centers()
    // {
    //     $this->db->select('id, name, center_number, address, rent_amount, rent_paid_date, center_timing_from, center_timing_to, created_at');
    //     $query = $this->db->get('center_details');
    //     return $query->result_array();
    // }

    // Fetch center by ID (array)
    public function get_center_by_id($id)
    {
        $this->db->select('id, name, center_number, address, rent_amount, rent_paid_date, center_timing_from, center_timing_to, created_at');
        $this->db->where('id', $id);
        $query = $this->db->get('center_details');
        return $query->row_array();
    }

    // Some alternative names kept for compatibility with various calls in your app:
    public function getCenterDetails($center_id)
    {
        return $this->db->get_where('center_details', ['id' => $center_id])->row_array();
    }

    public function getBatchesByCenter($center_id)
    {
        return $this->db->get_where('batches', ['center_id' => $center_id])->result_array();
    }

    public function getStaffByCenter($center_id)
    {
        return $this->db->get_where('staff', ['center_id' => $center_id])->result_array();
    }

    // Single method name for facilities by center (kept earlier name)
    public function getFacilitiesByCenter($center_id)
    {
        return $this->db->get_where('facilities', ['center_id' => $center_id])->result_array();
    }

    // Update batch by ID
    public function update_batch($id, $data)
    {
        if (empty($id) || empty($data)) {
            return false;
        }
        return $this->db->where('id', $id)
            ->update('batches', $data);
    }

    // Get batch by center ID (note: original name suggested get_batch by center_id)
    public function get_batch($id)
    {
        return $this->db->where('center_id', $id)
            ->get('batches')
            ->row_array();
    }

    public function getCenterById($id)
    {
        return $this->db->get_where('center_details', ['id' => $id])->row_array();
    }

    public function updateCenter($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('center_details', $data);
    }

    // Facility helpers
    public function getFacilityById($id)
    {
        return $this->db->get_where('facilities', ['id' => $id])->row_array();
    }

    public function updateFacilityById($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('facilities', $data);
    }

    public function deleteFacilityById($id)
    {
        return $this->db->delete('facilities', ['id' => $id]);
    }

    public function getStaffById($id)
    {
        return $this->db->get_where("staff", ['id' => $id])->row_array();
    }

    public function updateStaffById($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update("staff", $data);
    }

    public function deleteStaffById($id)
    {
        return $this->db->delete("staff", ['id' => $id]);
    }

    public function delete_center_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('center_details');
    }

    public function delete_batch_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('batches');
    }

    // ----------------------- Methods needed by the get_center_stats endpoint -----------------------

    /**
     * Return center record as object (used by controller get_center_stats)
     */
    public function get_by_id($center_id)
    {
        return $this->db->where('id', $center_id)->get('center_details')->row();
    }

    /**
     * Optional helper: resolve center_id from admins table (if you keep admin->center mapping there)
     * Adjust table/column names if your admin table differs.
     */
    public function get_center_id_by_admin($admin_id)
    {
        $row = $this->db->select('center_id')->where('id', $admin_id)->get('admins')->row();
        return $row ? $row->center_id : null;
    }


// Insert Center
    public function insert_center($data) {
        return $this->db->insert('center_details', $data);
    }

    // Get All Centers
    public function get_all_centers() {
        return $this->db->get('center_details')->result();
    }






















}
