<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_RESTApi_Generic extends Controller
{

    public function action_index()
    {
        switch ($this->request->method()) {
            case 'GET':
                if (method_exists($this, 'action_get')) {
                    $this->action_get();
                    break;
                }
            case 'POST':
                if (method_exists($this, 'action_post')) {
                    $this->action_post();
                    break;
                }

            case 'DELETE':
                if (method_exists($this, 'action_delete')) {
                    $this->action_delete();
                    break;
                }

            case 'PUT':
                if (method_exists($this, 'action_put')) {
                    $this->action_put();
                    break;
                }
            default:
                $this->method_not_allowed();
        }
    }

    public function after()
    {
        parent::after();
        $body = $this->response->body();
        $body = str_replace('\/', '/', $body);
        $this->response->body($body);
        $this->response->headers('Content-Type', 'application/json');
        //Para hacer AJAX Cross domain
        //$this->response->headers('Access-Control-Allow-Origin', 'http://django-rest-framework.org');
    }

    public function method_not_allowed()
    {
        $method = $this->request->method();
        $this->response->status(HTTP_Status::HTTP_405_METHOD_NOT_ALLOWED);
        $this->response->body(json_encode(array("detail" => "Method '$method' not allowed")));
    }
}