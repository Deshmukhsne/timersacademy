<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Expense_model extends CI_Model
{
    // Get all expenses (with center info)
    public function get_all_expenses()
    {
        $this->db->select('expenses.*, center_details.name AS center_name');
        $this->db->from('expenses');
        $this->db->join('center_details', 'center_details.id = expenses.center_id', 'left');

        $this->db->order_by('expenses.date', 'DESC');
        return $this->db->get()->result();
    }
    public function insert($data)
    {
        return $this->db->insert('expenses', $data);
    }


    // Insert new expense
    public function insert_expense($data)
    {
        return $this->db->insert('expenses', $data);
    }


    // Filter expenses
    public function filter_expenses($filters = [])
    {
        $this->db->select('expenses.*, center_details.name AS center_name');
        $this->db->from('expenses');
        $this->db->join('center_details', 'center_details.id = expenses.center_id', 'left');



        if (!empty($filters['from_date'])) {
            $this->db->where('expenses.date >=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $this->db->where('expenses.date <=', $filters['to_date']);
        }
        if (!empty($filters['min_amount'])) {
            $this->db->where('expenses.amount >=', $filters['min_amount']);
        }
        if (!empty($filters['max_amount'])) {
            $this->db->where('expenses.amount <=', $filters['max_amount']);
        }
        if (!empty($filters['category'])) {
            $this->db->where('expenses.category', $filters['category']);
        }

        return $this->db->get()->result();
    }

    // Approve / Reject
    public function update_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update('expenses', ['status' => $status]);
    }
}
