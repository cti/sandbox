<?php

namespace Cti\Storage\Converter;
/**
 * Class DBAL
 * Converts Cti\Storage\Schema to Doctrine Schema
 * @package Cti\Storage\Converter
 */
class DBAL {

    /**
     * @inject
     * @var \Cti\Core\Application
     */
    protected $application;
    /**
     * @var \Doctrine\DBAL\Schema\Schema
     */
    protected $schema;
    /**
     * @var \Cti\Storage\Schema
     */
    protected $inputSchema;

    public function convert(\Cti\Storage\Schema $inputSchema)
    {
        $schema = new \Doctrine\DBAL\Schema\Schema;


        foreach($inputSchema->getModels() as $model) {
            $table = $schema->createTable($model->getName());
            foreach($model->getProperties() as $property) {
                $table->addColumn($property->getName(), $property->getType(), array(
                    'comment' => $property->getComment(),
                    'notnull' => $property->getRequired(),
                ));
            }
            $table->setPrimaryKey($model->getPk());
            foreach($model->getIndexes() as $index) {
                $table->addIndex($index->getFields());
            }
        }
        return $schema;
    }
}