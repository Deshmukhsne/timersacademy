<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Batch_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    protected $table = 'batches';

    public function get_batches($filters = []) {
        $this->db->select('*');
        $this->db->from('batches');

        if (!empty($filters['batch'])) {
            $this->db->like('batch', $filters['batch']);
        }
        if (!empty($filters['date'])) {
            $date = date('Y-m-d', strtotime(str_replace('/', '-', $filters['date'])));
            $this->db->where('date', $date);
        }
        if (!empty($filters['time'])) {
            $this->db->like('time', $filters['time']);
        }
        if (!empty($filters['category'])) {
            $this->db->where('category', $filters['category']);
        }
        if (!empty($filters['center_name'])) {
            $this->db->like('center_name', $filters['center_name']);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_batch($data) {
        return $this->db->insert('batches', $data);
    }

    public function update_batch($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('batches', $data);
    }
    

    public function delete_batch($id) {
        $this->db->where('id', $id);
        return $this->db->delete('batches');
    }


 public function saveBatch($input) {
       

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
        // print_r($data); die;

        $this->db->insert("batches", $data);
        return ["status"=>"success","message"=>"Batch saved"];
    }
}

// // Insert batch data
//     public function insert_batch($data) {

//         return $this->db->insert($this->batches, $data);
//     }

    // Fetch all batches
    public function get_batches() {
        return $this->db->get($this->batches)->result();
    }

    // Fetch batch by id (for edit/delete if needed)
    public function get_batch($id) {
        return $this->db->where('id', $id)->get($this->batches)->row();
    }

    // Update batch
    public function update_batch($id, $data) {
        return $this->db->where('id', $id)->update($this->batches, $data);
    }

    // Delete batch
    public function delete_batch($id) {
        return $this->db->where('id', $id)->delete($this->batches;
    }



 // Insert new batch
    public function insert_batch($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id(); // return last inserted ID
    }





?>