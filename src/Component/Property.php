<?php

namespace Cti\Storage\Component;

use Cti\Core\String;

class Property
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var string
     */
    protected $foreignName;

    /**
     * @var boolean
     */
    protected $primary;

    /**
     * @var boolean
     */
    protected $required;

    /**
     * @var boolean
     */
    protected $behaviour;

    /**
     * @var boolean
     */
    protected $relation;

    /**
     * @var string
     */
    protected $setter;

    /**
     * @var string
     */
    protected $getter;

    /**
     * @var integer
     */
    protected $min;

    /**
     * @var integer
     */
    protected $max;

    /**
     * @var mixed
     */
    protected $model;

    /**
     * @var bool
     */
    protected $readonly;

    /**
     * @param $params
     */
    public function __construct($params)
    {
        if(isset($params[0])) {
            // numeric keys
            switch(count($params)) {
                case 3:
                    $params = array('name' => $params[0], 'type' => $params[1], 'comment' => $params[2]);
                    break;
                default: 
                    throw new Exception("Error Processing property ");
            }
        }
        $this->name = $params['name'];
        $this->comment = isset($params['comment']) ? $params['comment'] : null;
        $this->foreignName = isset($params['foreignName']) ? $params['foreignName'] : null;
        $this->required = isset($params['required']) ? $params['required'] : false;
        $this->primary = isset($params['primary']) ? $params['primary'] : false;
        $this->behaviour = isset($params['behaviour']) ? $params['behaviour'] : false;
        $this->relation = isset($params['relation']) ? $params['relation'] : null;
        $this->readonly = isset($params['readonly']) ? $params['readonly'] : $this->primary;

        if(isset($params['model'])) {
            $this->model = $params['model'];
        }

        if (isset($params['type'])) {
            $this->type = $params['type'];
        } else {
            if (substr($this->name, 0, 3) == 'dt_') {
                $this->type = 'date';
            } elseif (substr($this->name, 0, 3) == 'id_') {
                $this->type = 'integer';
            } elseif (substr($this->name, 0, 3) == 'is_') {
                $this->type = 'boolean';
            } else {
                $this->type = 'string';
            }
        }

        if (isset($params['setter'])) {
            $this->setter = $params['setter'];
        } else {    
            $this->setter = 'set'.String::convertToCamelCase($this->name);
        }

        if (isset($params['getter'])) {
            $this->getter = $params['getter'];
        } else {
            $this->getter = 'get'.String::convertToCamelCase($this->name);
        }

        if (isset($params['min'])) {
            $this->min = $params['min'];
        }

        if (isset($params['max'])) {
            $this->max = $params['max'];
        }
    }

    /**
     * @param array $override
     * @return Property
     */
    function copy($override = array())
    {
        $config = get_object_vars($this);
        foreach($override as $k => $v) {
            $config[$k] = $v;
        }
        return new Property($config);
    }

    /**
     * @return boolean
     */
    public function getBehaviour()
    {
        return $this->behaviour;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return string
     */
    public function getForeignName()
    {
        return $this->foreignName;
    }

    /**
     * @return string
     */
    public function getGetter()
    {
        return $this->getter;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function getPrimary()
    {
        return $this->primary;
    }

    /**
     * @return boolean
     */
    public function getReadonly()
    {
        return $this->readonly;
    }

    /**
     * @return Relation
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getSetter()
    {
        return $this->setter;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }



}