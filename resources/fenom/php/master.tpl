<?php

namespace Storage;

use OutOfRangeException;

{include 'php/blocks/comment.tpl'}

class Master
{
    /**
     * @inject
     * @var \Cti\Di\Manager
     */
    protected $manager;

{foreach $schema->getModels() as $model}
    /**
     * Get {$model->getClassName()} repository
     * @return {$model->getRepositoryClass()} 
     */
    public function get{$model->getClassNameMany()}()
    {
        return $this->manager->get('{$model->getRepositoryClass()}');
    }

{/foreach}
    /**
     * create new instance
     * @param  string $name model name
     * @param  array  $data
     * @return mixed
     */
    public function create($name, $data)
    {
        return $this->getRepository($name)->create($data);
    }

    /**
     * Get repository by model nick
     * @param  string $name
     * @return mixed
     * @throws OutOfRangeException
     */
    public function getRepository($name)
    {
        $map = array(
{foreach $schema->getModels() as $model}
            '{$model->getName()}' => 'get{$model->getClassNameMany()}',
{/foreach}
        );
        if (!isset($map[$name])) {
            throw new OutOfRangeException("Model $name was not defined");
        }
        $method = $map[$name];
        return $this->$method();
    }
}