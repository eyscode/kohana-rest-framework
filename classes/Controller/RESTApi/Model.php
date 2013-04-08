<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_RESTApi_Model extends Controller_RESTApi_Generic
{

    protected $_orm_model = null;
    protected $_serializer = null;
    protected $_messages_dir = '';
    protected $_paginate_by = null;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        if ($this->_serializer) {
            $this->_serializer = new $this->_serializer;
            $cad = $this->_serializer->get_model();
            $this->_orm_model = new $cad;
        } else {
            $this->_serializer = new ModelSerializer($this->_orm_model);
            $this->_orm_model = ORM::factory($this->_orm_model);
        }
    }

    protected function query()
    {
        return $this->_orm_model;
    }
}