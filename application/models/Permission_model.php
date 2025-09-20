<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Permission_model extends CI_Model
{

    // Get permissions for a given center
    public function get_by_center($center_id)
    {
        $this->db->select('permission_key, enabled');
        $this->db->from('center_permissions');
        $this->db->where('center_id', $center_id);
        $query = $this->db->get();
        $result = [];
        foreach ($query->result_array() as $row) {
            $result[$row['permission_key']] = (bool)$row['enabled'];
        }
        return $result;
    }

    // Save/update permissions
    public function save_permissions($center_id, $permissions)
    {
        foreach ($permissions as $key => $enabled) {
            $exists = $this->db->get_where('center_permissions', [
                'center_id' => $center_id,
                'permission_key' => $key
            ])->row();

            if ($exists) {
                $this->db->where('id', $exists->id)
                    ->update('center_permissions', ['enabled' => $enabled]);
            } else {
                $this->db->insert('center_permissions', [
                    'center_id' => $center_id,
                    'permission_key' => $key,
                    'enabled' => $enabled
                ]);
            }
        }
    }
}
