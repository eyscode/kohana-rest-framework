<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_RESTApi_Model_ListCreate extends Controller_RESTApi_Model
{
    use Mixin_ListModel;
    use Mixin_CreateModel;

    public function action_get()
    {
        $this->response->body(json_encode($this->all()));
    }

    public function action_post()
    {
        $this->response->body(json_encode($this->create()));
    }
}