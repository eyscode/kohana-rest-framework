<?php defined('SYSPATH') OR die('No direct access allowed.');

trait Mixin_ListModel
{
    public function all()
    {
        if ($items_per_page = Kohana::$config->load('krf.paginate_by')) {
            $page = Request::current()->query('page');
            $page = $page ? $page : 1;
            $offset = ($page - 1) * $items_per_page;
            $count = (int)$this->query()->count_all();
            $all = $this->query()->limit($items_per_page)->offset($offset)->find_all()->as_array();
            if ($all) {
                $url = URL::site(Request::detect_uri(), true);
                $next = $count > $items_per_page * $page ? $url . '/?page=' . ($page + 1) : null;
                $previous = $page > 1 ? $url . '/?page=' . ($page - 1) : null;
                $resp = array("count" => $count, "next" => $next, "previous" => $previous, "results" => array());
                foreach ($all as $slot) {
                    array_push($resp["results"], $this->_serializer->get_data($slot));
                }
            } else {
                $this->response->status(HTTP_Status::HTTP_404_NOT_FOUND);
                return array("detail" => "Not found");
            }
        } else {
            $all = $this->query()->find_all()->as_array();
            $resp = array();
            foreach ($all as $slot) {
                array_push($resp, $this->_serializer->get_data($slot));
            }

        }
        return $resp;
    }
}