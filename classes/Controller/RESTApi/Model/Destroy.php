<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_RESTApi_Model_Destroy extends Controller_RESTApi_Model
{
    use Mixin_DestroyModel;

    public function action_delete()
    {
        $this->response->body($this->destroy());
    }

}