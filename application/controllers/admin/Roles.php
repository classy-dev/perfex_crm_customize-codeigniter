<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Roles extends AdminController
{
    /* List all staff roles */
    public function index()
    {
        if (!has_permission('roles', '', 'view')) {
            access_denied('roles');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('roles');
        }
        $data['title'] = _l('all_roles');
        $this->load->view('admin/roles/manage', $data);
    }

    /* Add new role or edit existing one */
    public function role($id = '')
    {
        if (!has_permission('roles', '', 'view')) {
            access_denied('roles');
        }

        if ($this->input->post()) {

            if(1 === preg_match('~[0-9]~', $_POST['name'])){
                preg_match("/([a-zA-Z]+)(\\d+)/", $_POST['name'] , $matches);
                $data['common_letter'] = $matches[1];
                $data['number'] = $matches[2];
            }
            else{
                $data['common_letter'] = $_POST['name'];
                $data['number'] = Null;
            }

            $data['name'] = $_POST['name'];
            $data['permissions'] = $_POST['permissions'];
            $data['update_staff_permissions'] = $_POST['update_staff_permissions'];
            
            if ($id == '') {
                if (!has_permission('roles', '', 'create')) {
                    access_denied('roles');
                }
                $id = $this->roles_model->add($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('role')));
                    redirect(admin_url('roles/role/' . $id));
                }
            } else {
                if (!has_permission('roles', '', 'edit')) {
                    access_denied('roles');
                }
                // print_r($data); exit();
                $success = $this->roles_model->update($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('role')));
                }
                redirect(admin_url('roles/role/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('role_lowercase'));
        } else {
            $data['role_staff'] = $this->roles_model->get_role_staff($id);
            $role               = $this->roles_model->get($id);
            $data['role']       = $role;
            $title              = _l('edit', _l('role_lowercase')) . ' ' . $role->name;
        }
        $data['title'] = $title;
        $this->load->view('admin/roles/role', $data);
    }

    /* Add new role type or edit existing one */
    public function role_type($id = '')
    {
        if (!has_permission('roles', '', 'view')) {
            access_denied('roles');
        }

        if ($this->input->post()) {
            if ($id == '') {
                if (!has_permission('roles', '', 'create')) {
                    access_denied('roles');
                }
                
                $id = $this->roles_model->add_type($this->input->post());
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('role_type')));
                    redirect(admin_url('roles'));

                }
            } else {
                if (!has_permission('roles', '', 'edit')) {
                    access_denied('roles');
                }

                $success = $this->roles_model->update_type($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('role')));
                }
                redirect(admin_url('roles'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('role_type_lowercase'));
        } else {
            // $data['role_staff'] = $this->roles_model->get_role_staff($id);
            $role_type               = $this->roles_model->get_type($id);
            $data['role_type']       = $role_type;
            $title              = _l('edit', _l('role_type_lowercase'));
        }
        $data['title'] = $title;
        $this->load->view('admin/roles/role_type', $data);
    }

    public function get_role_type(){
        if (!has_permission('roles', '', 'view')) {
            access_denied('roles');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('role_type');
        }
        $data['title'] = _l('all_roles');
        $this->load->view('admin/roles/manage', $data);
    }

    /* Delete role from database */
    public function delete($id)
    {
        if (!has_permission('roles', '', 'delete')) {
            access_denied('roles');
        }
        if (!$id) {
            redirect(admin_url('roles'));
        }
        $response = $this->roles_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('role_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('role')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('role_lowercase')));
        }
        redirect(admin_url('roles'));
    }

    /* Delete role_type from database */
    public function delete_role_type($id)
    {
        if (!has_permission('roles', '', 'delete')) {
            access_denied('roles');
        }
        if (!$id) {
            redirect(admin_url('roles'));
        }
        $response = $this->roles_model->delete_type($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('role_type_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('role_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('role_type_lowercase')));
        }
        redirect(admin_url('roles'));
    }
}
