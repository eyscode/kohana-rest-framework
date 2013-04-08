<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_RESTApi_Model_Retrieve extends Controller_RESTApi_Model
{
    use Mixin_RetrieveModel;

    public function action_get()
    {
        $this->response->body(json_encode($this->retrieve()));
    }
}