<?php defined('SYSPATH') OR die('No direct access allowed.');

class ModelSerializer
{

    protected $_orm_model = null;

    protected $_fields = array();

    protected $_has_many = array();

    protected $_has_one = array();

    protected $_belongs_to = array();

    public function __construct()
    {
        if ($this->_orm_model) {
            $this->_orm_model = ORM::factory($this->_orm_model);
        }
    }

    public function get_model()
    {
        return get_class($this->_orm_model);
    }

    public function get_data($instance = null)
    {
        $res = $this->set_types($instance);

        $keys = array_keys($res);

        if ($this->_fields) {
            $except = array_diff($keys, $this->_fields);

            foreach ($except as $key) {
                unset($res[$key]);
            }
        }
        if ($this->_has_many) {
            $res = array_merge($res, $this->get_related_many($instance));
        }
        if ($this->_belongs_to) {
            $res = array_merge($res, $this->get_related_belongs_to($instance));
        }
        return $res;
    }

    public function set_types($instance)
    {
        $res = $instance->as_array();
        $neoarray = array();
        $lc = $instance->list_columns();
        foreach ($res as $field => $value) {
            if(!isset($lc[$field]))
                continue;
            $type = $lc[$field]["type"];
            settype($value, $type);
            $neoarray[$field] = $value;
        }
        return $neoarray;

    }

    public function to_json($instance)
    {
        return json_encode($this->get_data($instance));
    }

    protected function get_related_many($instance)
    {
        foreach ($this->_has_many as $name => $type_relation) {
            if (!in_array($name, array_keys($instance->has_many()))) {
                throw new Exception("The '$name' alias does not exist.");
            }
        }
        if ($many = $this->_has_many) {
            $res = array();
            foreach ($many as $name => $type_relation) {
                switch ($type_relation[0]) {
                    case Relationship::NestedRelated:
                        $childs = array();
                        foreach ($instance->{$name}->find_all() as $child) {
                            $class = Arr::get($type_relation, 1);
                            if ($class) {
                                $s = new $class;
                            } else {
                                $s = new ModelSerializer();
                            }
                            array_push($childs, $s->get_data($child));
                        }
                        $res[$name] = $childs;
                        break;
                    case Relationship::FieldRelated;
                        $childs = array();
                        foreach ($instance->{$name}->find_all() as $child) {
                            $field = Arr::get($type_relation, 1);
                            if ($field) {
                                $isfunc = false;
                                try {
                                    $value = $child->{$field};
                                } catch (Exception $ex) {
                                    $isfunc = true;
                                }
                                if ($isfunc) {
                                    try {
                                        $value = $child->{$field}();
                                    } catch (Exception $ex) {
                                        echo "no es nada";
                                    }
                                }
                                array_push($childs, $value);
                            } else {
                                array_push($childs, $child->__toString());
                            }
                        }
                        $res[$name] = $childs;
                        break;
                    case Relationship::PrimaryKeyRelated:
                        $childs = array();
                        foreach ($instance->{$name}->find_all() as $child) {
                            $value = $child->pk();
                            $pk = $child->primary_key();
                            $type = $child->list_columns()[$pk]["type"];
                            settype($value, $type);
                            array_push($childs, $value);
                        }
                        $res[$name] = $childs;
                        break;
                }
            }
            return $res;
        }

    }

    protected function get_related_belongs_to($instance)
    {
        foreach ($this->_belongs_to as $name => $type_relation) {
            if (!in_array($name, array_keys($instance->belongs_to()))) {
                throw new Exception("The '$name' alias does not exist.");
            }
        }
        if ($many = $this->_belongs_to) {
            $res = array();
            foreach ($many as $name => $type_relation) {
                switch ($type_relation[0]) {
                    case Relationship::NestedRelated:
                        $child = $instance->{$name};
                        $class = Arr::get($type_relation, 1);
                        if ($class) {
                            $s = new $class;
                        } else {
                            $s = new ModelSerializer();
                        }
                        $res[$name] = $s->get_data($child);
                        break;
                    case Relationship::FieldRelated;
                        $child = $instance->{$name};
                        $field = Arr::get($type_relation, 1);
                        if ($field) {
                            $isfunc = false;
                            try {
                                $value = $child->{$field};
                            } catch (Exception $ex) {
                                $isfunc = true;
                            }
                            if ($isfunc) {
                                try {
                                    $value = $child->{$field}();
                                } catch (Exception $ex) {
                                    echo "no es nada";
                                }
                            }
                            $res[$name] = $value;
                        } else {
                            $res[$name] = $child->__toString();
                        }
                        break;
                    case Relationship::PrimaryKeyRelated:
                        $child = $instance->{$name};
                        $value = $child->pk();
                        $pk = $child->primary_key();
                        $type = $child->list_columns()[$pk]["type"];
                        settype($value, $type);
                        $res[$name] = $value;
                        break;
                }
            }
            return $res;
        }

    }
}
