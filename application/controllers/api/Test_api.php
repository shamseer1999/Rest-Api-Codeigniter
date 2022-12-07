<?php

    require APPPATH.'libraries/REST_Controller.php';

    class Test_api extends REST_Controller{

        public function __construct()
        {
            
            parent::__construct();
            $this->load->database();
        }

        public function index_get()
        {
            $data=array(
                'name' =>'Shamseer',
                'company' =>'Primalcodes Technologies'
            );

            $this->response($data,REST_Controller::HTTP_OK);

        }

        //Insert
        public function add_user_post()
        {

            $json=file_get_contents("php://input");
            $input=json_decode($json,TRUE);

            $insert=array(
                'name' =>$input['name'],
                'phone' =>$input['phone'],
                'company' =>$input['company']
            );

            $this->db->insert('users',$insert);

            $insert=$this->db->affected_rows();

            if($insert >0)
            {

                $out=array(
                    'status' =>1,
                    'message' =>'User added successfully'
                );

                $this->response($out,REST_Controller::HTTP_OK);

            }
            else
            {

                $out=array(
                    'status'=>0,
                    'message' =>'Something went wrong'
                );

                $this->response($out,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);

            }

        }

        //update
        public function update_user_put()
        {

            $json=file_get_contents("php://input");
            $insert=json_decode($json,TRUE);

            $check_exist=$this->db->select('user_id')
                ->from('users')
                ->where('user_id',$insert['user_id'])
                ->count_all_results();

            $checked_user=$this->db->select('*')
                ->from('users')
                ->where('user_id',$insert['user_id'])
                ->get()
                ->row_array();

            if($check_exist >0)
            {

                $name=!empty($insert['name']) ? $insert['name'] : $checked_user['name'];
                $phone=!empty($insert['phone']) ? $insert['phone'] : $checked_user['phone'];
                $company=!empty($insert['company']) ? $insert['company'] : $checked_user['company'];

                $update=array(
                    'name' =>$name,
                    'phone' =>$phone,
                    'company' =>$company
                );

                $this->db->where('user_id',$insert['user_id'])
                    ->update('users',$update);

                $update=$this->db->affected_rows();

                if($update >0)
                {

                    $out=array(
                        'status' =>1,
                        'message' =>'User updated successfully'
                    );

                    $this->response($out,REST_Controller::HTTP_OK);

                }
                else
                {

                    $out=array(
                        'status' =>0,
                        'message' =>'Nothing to be changed'
                    );

                    $this->response($out,REST_Controller::HTTP_OK);

                }

            }
            else
            {

                $out=array(
                    'status' =>0,
                    'message' =>'User doesnot exist'
                );

                $this->response($out,REST_Controller::HTTP_NOT_FOUND);

            }

        }

    }

?>