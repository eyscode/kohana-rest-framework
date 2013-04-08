<?php defined('SYSPATH') OR die('No direct access allowed.');

trait Mixin_UpdateModel
{
    public function update()
    {
        $request = Request::current();
        $data = json_decode($request->body());
        $id = $request->param('id');
        if (is_numeric($id)) {
            $_orm_model = $this->_orm_model->where('id', '=', $id)->find();
            if ($_orm_model->loaded()) {
                try {
                    foreach ($data as $key => $value) {
                        if ($key != 'id') {
                            $_orm_model->{$key} = $value;
                        }
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
                return $this->_serializer->get_data($_orm_model);
            } else {
                $this->response->status(HTTP_Status::HTTP_301_MOVED_PERMANENTLY);
                return null;
            }
        } else {
            $this->response->status(HTTP_Status::HTTP_400_BAD_REQUEST);
        }
    }
}