<?php
namespace Propeller\Includes\Controller;

use GraphQL\RawObject;

class CompanyController extends BaseController {
    protected $model;

    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('company');
    }

    public function create($args) {
        $type = 'companyCreate';

        $raw_params = '{
            name: "' . $args['name'] . '",
            taxNumber: "' . $args['taxNumber'] . '",
            parentId: ' . $args['parentId'] . '
        }';

        $gql = $this->model->create(['input' => new RawObject($raw_params)]);

        return $this->query($gql, $type);
    }

    public function get($id) {
        $type = 'company';

        $gql = $this->model->get(['input' => new RawObject('{ companyId: ' . $id . '}')]);

        return $this->query($gql, $type);
    }
}