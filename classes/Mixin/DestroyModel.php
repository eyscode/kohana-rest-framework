<?php defined('SYSPATH') OR die('No direct access allowed.');
trait Mixin_DestroyModel
{
    public function destroy()
    {
        $request = Request::current();
        $id = $request->param('id');
        if (is_numeric($id)) {
            $_orm_model = $this->_orm_model->where('id', '=', $id)->find();
            if ($_orm_model->loaded()) {
                $_orm_model->delete();
                $this->response->status(HTTP_Status::HTTP_204_NO_CONTENT);
            } else {
                $this->response->status(HTTP_Status::HTTP_301_MOVED_PERMANENTLY);
            }
        } else {
            $this->response->status(HTTP_Status::HTTP_400_BAD_REQUEST);
        }
    }
}