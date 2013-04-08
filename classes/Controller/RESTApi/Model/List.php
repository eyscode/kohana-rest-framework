<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_RESTApi_Model_List extends Controller_RESTApi_Model
{
    use Mixin_ListModel;

    public function action_get()
    {
        $this->response->body(json_encode($this->all()));
    }

}