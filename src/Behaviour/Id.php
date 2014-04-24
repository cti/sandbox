<?php

namespace Cti\Storage\Behaviour;

use Cti\Storage\Component\Model;
use Cti\Storage\Component\Property;

class Id extends Behaviour
{
    protected $name = 'id';

    public function init(Model $model)
    {
        $this->name = 'id_' . $model->getName();
        $this->properties = array(
            $this->name => new Property(array(
                'behaviour' => true,
                'name' => $this->name,
                'comment' => 'identifier',
                'type' => 'integer',
                'primary' => true,
            ))
        );
    }

    public function getPk()
    {
        return array($this->name);
    }
}