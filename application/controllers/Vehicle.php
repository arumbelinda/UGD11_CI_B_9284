<?php
use Restserver \Libraries\REST_Controller ;
Class Vehicle extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        $this->load->model('VehicleModel');
        $this->load->library('form_validation');
        }
        public function index_get(){
        return $this->returnData($this->db->get('vehicles')->result(), false);
        }
        public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->VehicleModel->rules();
        if($id == null){
        array_push($rule,[
        'field' => 'merk',
        'label' => 'merk',
        'rules' => 'required'
        ],
        [
        'field' => 'type',
        'label' => 'type',
        'rules' => 'required'
        ],
        [
        'field' => 'licensePlate',
        'label' => 'licensePlate',
        'rules' => 'required|is_unique[vehicles.licensePlate]'
        ]
        );
        }
        else{
        array_push($rule,
        [
        'field' => 'licensePlate',
        'label' => 'licensePlate',
        'rules' => 'required'
        ],
        [
        'field' => 'created_at',
        'label' => 'created_at',
        'rules' => 'required'
        ],
        );
        }
        $validation->set_rules($rule);
        if (!$validation->run()) {
        return $this->returnData($this->form_validation->error_array(), true);
        }
        date_default_timezone_get();
        $vehicle = new VehicleData();
        $vehicle->merk = $this->post('merk');
        $vehicle->type = $this->post('type');
        $vehicle->licensePlate = $this->post('licensePlate');
        $date = new DateTime();
        $vehicle->created_at = $date;
        if($id == null){
        $response = $this->VehicleModel->store($vehicle);
    }else{
        $response = $this->VehicleModel->update($vehicle,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
        }
        public function index_delete($id = null){
        if($id == null){
        return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->VehicleModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
        }
        public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
        }
       }
       Class VehicleData{
        public $merk;
        public $type;
        public $licensePlate;
        public $created_at;
       }   