<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // fetch all students
    public function get_all_students()
    {
        $query = $this->db->get('students'); // SELECT * FROM students
        return $query->result_array();
    }

    // fetch single student by id
    public function get_student_by_id($id)
    {
        $query = $this->db->get_where('students', ['id' => $id]);
        return $query->row_array();
    }

    public function get_student_by_id_history($id)
    {
        $query = $this->db->get_where('student_addmission_history', ['student_id' => $id]);
        return $query->result_array();
    }
    public function get_students_by_center($center_id)
    {
        if (empty($center_id))
            return [];
        $query = $this->db->get_where('students', ['center_id' => $center_id]);
        return $query->result_array();
    }


    public function get_student_by_id_history_batch($id)
    {
        $this->db->select("
        sah.history_id as admission_history_id,
        sah.student_id,
        sah.center_id,
        sah.batch_id,
        sah.coach,

        c.id as center_id,
        c.name as center_name,
        c.center_number,
        c.address,
        c.rent_amount,
        c.rent_paid_date,
        c.center_timing_from,
        c.center_timing_to,
        c.password,
        c.created_at as center_created_at,
        c.updated_at as center_updated_at,
        
        b.id as batch_id,
        b.batch_name,
        b.batch_level,
        b.start_time,
        b.end_time,
        b.start_date,
        b.end_date,
        b.duration,
        b.category,
        b.created_at as batch_created_at,
        b.updated_at as batch_updated_at
    ");
        $this->db->from("student_addmission_history sah");
        $this->db->join("center_details c", "c.id = sah.center_id", "left");
        $this->db->join("batches b", "b.id = sah.batch_id", "left");
        $this->db->where("sah.student_id", $id);

        $query = $this->db->get();
        return $query->result_array();
    }




    // count students for a given center_id
    public function count_by_center($center_id)
    {
        if (empty($center_id))
            return 0;
        $this->db->where('center_id', $center_id);
        return (int) $this->db->count_all_results('students');
    }

    /**
     * Paginated fetch for students of a center with optional search.
     *
     * @param int $center_id
     * @param int $limit
     * @param int $offset
     * @param string|null $search  // searches name, contact, parent_name
     * @return array
     */
    public function get_students_by_center_paginated($center_id, $limit = 10, $offset = 0, $search = null)
    {
        if (empty($center_id))
            return [];

        $this->db->from('students');
        $this->db->where('center_id', (int) $center_id);

        if (!empty($search)) {
            $s = trim($search);
            $this->db->group_start();
            $this->db->like('name', $s);
            $this->db->or_like('contact', $s);
            $this->db->or_like('parent_name', $s);
            $this->db->group_end();
        }

        $this->db->order_by('created_at', 'DESC'); // latest first
        if ((int) $limit > 0)
            $this->db->limit((int) $limit, (int) $offset);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Count students for a center with optional search filter.
     *
     * @param int $center_id
     * @param string|null $search
     * @return int
     */
    public function count_students_by_center($center_id, $search = null)
    {
        if (empty($center_id))
            return 0;

        $this->db->from('students');
        $this->db->where('center_id', (int) $center_id);

        if (!empty($search)) {
            $s = trim($search);
            $this->db->group_start();
            $this->db->like('name', $s);
            $this->db->or_like('contact', $s);
            $this->db->or_like('parent_name', $s);
            $this->db->group_end();
        }

        return (int) $this->db->count_all_results();
    }
}
