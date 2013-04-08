<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_RESTApi_Model_RetrieveUpdateDestroy extends Controller_RESTApi_Model
{
    use Mixin_RetrieveModel;
    use Mixin_UpdateModel;
    use Mixin_DestroyModel;

    public function action_get()
    {
        $this->response->body(json_encode($this->retrieve()));
    }

    public function action_put()
    {
        $this->response->body(json_encode($this->update()));
    }

    public function action_delete()
    {
        $this->response->body(json_encode($this->destroy()));
    }

}