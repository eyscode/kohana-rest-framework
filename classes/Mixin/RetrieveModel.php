<?php defined('SYSPATH') OR die('No direct access allowed.');

trait Mixin_RetrieveModel
{

    public function retrieve()
    {
        $request = Request::current();
        $id = $request->param('id');
        if (is_numeric($id)) {
            $_orm_model = $this->_orm_model->where('id', '=', $id)->find();
            if ($_orm_model->loaded()) {
                return $this->_serializer->get_data($_orm_model);
            } else {
                $this->response->status(HTTP_Status::HTTP_404_NOT_FOUND);
                return array("detail" => "Not found");
            }

        } else {
            $this->response->status(HTTP_Status::HTTP_400_BAD_REQUEST);
            return null;
        }
    }
}