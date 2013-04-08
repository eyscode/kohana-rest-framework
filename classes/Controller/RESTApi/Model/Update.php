<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_RESTApi_Model_Update extends Controller_RESTApi_Model
{
    use Mixin_UpdateModel;

    public function action_put()
    {
        $this->response->body($this->update());
    }
}