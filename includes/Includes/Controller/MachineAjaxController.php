<?php 
namespace Propeller\Includes\Controller;

use Propeller\Includes\Controller\MachineController;
use Propeller\Propeller;

class MachineAjaxController extends BaseAjaxController {
    protected $machine;

    public function __construct() { 
        parent::__construct();

        $this->machine = new MachineController();
    }

    public function do_machine() {
	    $data = $this->sanitize($_POST);

        unset($data['action']);

        $prop = new Propeller();
        $prop->reinit_filters();

        $response = $this->machine->machine_listing($data, true);

        die(json_encode($response));
    }
}