<?php

require_once(FUEL_PATH . '/libraries/Fuel_base_controller.php');

class factory_material extends Fuel_base_controller
{
    private $data;
    private $gdata;
    public $nav_selected = 'factory_material';
    public $view_location = 'factory_material';
    private $rate_details;

    function __construct()
    {
        parent::__construct();

        $this->load->module_model(FACTORY_MATERIAL_FOLDER, 'factory_material_model');
        $this->config->load('factory_material');
        $this->load->language('factory_material');
        $this->factory_material = $this->config->item('factory_material');
        $this->formdata = $this->factory_material_model->form_fields();
        $this->gdata = $this->factory_material_model->CoilTable();
        $this->data = $this->factory_material_model->select_coilname();
        if (isset($this->data)) {
            if (isset($this->data[0])) {
            }
        }
    }


    function export_party($frmdate = '', $todate = '')
    {
        $this->load->model('factory_material_model');
       $containers = $this->factory_material_model->export_partyname();
        if (!empty($containers)) {
            foreach ($containers as $container) {
                $obj = new stdClass();
               $obj->partyname = $container->partyname;
                $obj->inweight = $container->inweight;
                $obj->outweight = $container->outweight;
                $obj->balance = $container->balance;
                //$obj->totalinweight = $container->totalinweight;
                //$obj->totaloutweight = $container->totaloutweight;
               // $obj->totalbalweight = $container->totalbalweight;
                $folders[] = $obj;
            }
            echo json_encode($folders);exit;
        } else {
            $status = array("status" => "No Results!");
            echo json_encode($status);exit;
        }
    }


    function listratelength($description = '')
    {
        if (empty($description)) {
            $description = $_POST['coil'];
        }
        $this->load->module_model(FACTORY_MATERIAL_FOLDER, 'factory_material_model');
        $containers = $this->factory_material_model->list_partyname($description);
        if (!empty($containers)) {
            foreach ($containers as $container) {
                $obj = new stdClass();
                $obj->description = $container->description;
                $obj->coilnumber = $container->coilnumber;
                $obj->receiveddate = $container->receiveddate;
                $obj->partyname = $container->partyname;
                $obj->thickness = $container->thickness;
                $obj->width = $container->width;
                $obj->weight = $container->weight;
                $obj->status = $container->status;
                //$obj->edi = fuel_url('factory_material/editratelength_coil').'/?priceid='.$container->priceid;
                //	$obj->dl = fuel_url('rate_details_length/deleteratelength_coil').'/?priceid='.$container->priceid;


                $folders[] = $obj;
            }
            echo json_encode($folders);
        } else {
            $status = array("status" => "No Results!");
            echo json_encode($status);
        }
    }

    function editratelength_coil()
    {

    }

    function SelectCoilName()
    {
        $this->load->module_model(FACTORY_MATERIAL_FOLDER, 'factory_material_model');
        $data = $this->factory_material_model->select_coilname();
        $datajson = json_encode($data);
        return $data;
    }


    function coil()
    {
        $this->load->module_model(FACTORY_MATERIAL_FOLDER, 'factory_material_model');
        $gdata = $this->factory_material_model->CoilTable();
        $gdatajson = json_encode($gdata);
        return $gdata;
    }

    function index()
    {
        if (!empty($this->data) && isset($this->data)) {
            $vars['gdata'] = $this->coil();
            $vars['formdata'] = $this->formdisplay();
            $vars['data'] = $this->data;
            $this->_render('factory_material', $vars);
        } else {
            redirect(fuel_url('#'));
        }
    }


    function formdisplay()
    {
        $this->load->module_model(FACTORY_MATERIAL_FOLDER, 'factory_material_model');
        $formdata = $this->factory_material_model->form_fields();
        $datajson = json_encode($formdata);
        return $formdata;
    }


    function billing_pdf() {
        $this->load->module_model(FACTORY_MATERIAL_FOLDER, 'factory_material_model');
        $billgenerateb = $this->factory_material_model->billgeneratemodel();
    }

    function totalweight_check() {
		$wei = $this->factory_material_model->totalweight_check();
		$weijson = json_encode($wei);
		echo $weijson; exit;
	}
}
/* End of file */
/* Location: ./fuel/modules/controllers*/
