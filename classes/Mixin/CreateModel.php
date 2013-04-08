<?php defined('SYSPATH') OR die('No direct access allowed.');

trait Mixin_CreateModel
{
    public function create()
    {
        $request = Request::current();
        if (!$request->body()) {
            $this->response->status(HTTP_Status::HTTP_400_BAD_REQUEST);
            return json_encode(array("non_field_errors" => array('Not input provided')));
        }
        $data = json_decode($request->body());
        if (!$data) {
            $this->response->status(HTTP_Status::HTTP_400_BAD_REQUEST);
            return json_encode(array("detail" => "JSON parse error - No JSON object could be decoded"));
        }
        $_orm_model = $this->_orm_model;
        try {
            foreach ($data as $key => $value) {
                $_orm_model->{$key} = $value;
            }
        } catch (Exception $ex) {
            //TODO: permitir parametros asi no esten en el modelo y sacarlos del array antes de setearlos
            echo $ex;
            return null;
        }
        try {
            $_orm_model->save();
        } catch (ORM_Validation_Exception $ex) {
            $this->response->status(HTTP_Status::HTTP_400_BAD_REQUEST);
            return $ex->errors($this->_messages_dir);
        }
        $this->response->status(HTTP_Status::HTTP_201_CREATED);
        return $this->_serializer->get_data($_orm_model);
    }
}