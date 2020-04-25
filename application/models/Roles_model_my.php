<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Roles_model extends App_Model
{
    /**
     * Add new employee role
     * @param mixed $data
     */
    public function add($data)
    {
        $permissions = [];
        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
        }

        $data['permissions'] = serialize($permissions);
        // print_r($data['permissions']); exit();

        $this->db->insert(db_prefix() . 'roles', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Role Added [ID: ' . $insert_id . '.' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Add new employee role_type
     * @param mixed $data
     */

    public function add_type($data)
    {
        
        $this->db->insert(db_prefix() . 'role_type', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('New Role type Added [ID: ' . $insert_id . '.' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update employee role
     * @param  array $data role data
     * @param  mixed $id   role id
     * @return boolean
     */
    public function update($data, $id)
    {

        $affectedRows = 0;
        $permissions  = [];
        if (isset($data['permissions'])) {
            $permissions = $data['permissions'];
        }

        $data['permissions'] = serialize($permissions);

        $update_staff_permissions = false;
        if (isset($data['update_staff_permissions'])) {
            $update_staff_permissions = true;
            
        }
        unset($data['update_staff_permissions']);
        $this->db->where('roleid', $id);
        // print_r($data); exit();
        $this->db->update(db_prefix() . 'roles', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($update_staff_permissions == true) {
            $this->load->model('staff_model');

            $staff = $this->staff_model->get('', [
                'role' => $id,
            ]);

            foreach ($staff as $member) {
                if ($this->staff_model->update_permissions($permissions, $member['staffid'])) {
                    $affectedRows++;
                }
            }
        }

        if ($affectedRows > 0) {
            log_activity('Role Updated [ID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Update employee role_type
     * @param  array $data role data
     * @param  mixed $id   role id
     * @return boolean
     */
    public function update_type($data, $id)
    {
        $affectedRows = 0;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'role_type', $data);
        // print_r($this->db->affected_rows()); exit();
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($affectedRows > 0) {
            log_activity('Role type Updated [ID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Get employee role by id
     * @param  mixed $id Optional role id
     * @return mixed     array if not id passed else object
     */
    public function get($id = '')
    {
        if (is_numeric($id)) {

            $role = $this->app_object_cache->get('role-' . $id);


            if ($role) {
                return $role;
            }

            $this->db->where('roleid', $id);

            $role              = $this->db->get(db_prefix() . 'roles')->row();
            $role->permissions = !empty($role->permissions) ? unserialize($role->permissions) : [];

            $this->app_object_cache->add('role-' . $id, $role);

            return $role;
        }

        return $this->db->get(db_prefix() . 'roles')->result_array();
    }

    /**
     * Get employee role_type by id
     * @param  mixed $id Optional role id
     * @return mixed     array if not id passed else object
     */
    public function get_type($id = '')
    {
        if (is_numeric($id)) {

            $this->db->where('id', $id);
            $role_type              = $this->db->get(db_prefix() . 'role_type')->row();
            return $role_type;
        }

        return $this->db->get(db_prefix() . 'role_type')->result_array();
    }


    /**
     * Delete employee role
     * @param  mixed $id role id
     * @return mixed
     */
    public function delete($id)
    {
        $current = $this->get($id);

        // Check first if role is used in table
        if (is_reference_in_table('role', db_prefix() . 'staff', $id)) {
            return [
                'referenced' => true,
            ];
        }

        $affectedRows = 0;
        $this->db->where('roleid', $id);
        $this->db->delete(db_prefix() . 'roles');

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            log_activity('Role Deleted [ID: ' . $id);

            return true;
        }

        return false;
    }

    /**
     * Delete employee role_type
     * @param  mixed $id role id
     * @return mixed
     */
    public function delete_type($id)
    {
        $current = $this->get($id);

        // Check first if role is used in table
        // if (is_reference_in_table('role', db_prefix() . 'staff', $id)) {
        //     return [
        //         'referenced' => true,
        //     ];
        // }

        $affectedRows = 0;
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'role_type');

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            log_activity('Role Type Deleted [ID: ' . $id);

            return true;
        }

        return false;
    }

    public function get_contact_permissions($id)
    {
        $this->db->where('userid', $id);

        return $this->db->get(db_prefix() . 'contact_permissions')->result_array();
    }

    public function get_role_staff($role_id)
    {
        $this->db->where('role', $role_id);

        return $this->db->get(db_prefix() . 'staff')->result_array();
    }
}
