<?php

namespace Cti\Storage\Converter;
/**
 * Class DBAL
 * Converts Cti\Storage\Schema to Doctrine Schema
 * @package Cti\Storage\Converter
 */
class DBAL {

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
                $params = array(
                    'comment' => $property->getComment(),
                    'notnull' => $property->getRequired(),
                );
                $type = $property->getType();
                if ($type === 'char') {
                    $type = 'string';
                    $params['length'] = 1;
                }
                $table->addColumn($property->getName(), $type , $params);
            }
            $table->setPrimaryKey($model->getPk());
            foreach($model->getIndexes() as $index) {
                $table->addIndex($index->getFields());
            }
        }

        foreach($inputSchema->getModels() as $model) {
            $table = $schema->getTable($model->getName());
            foreach($model->getOutReferences() as $reference) {
                if ($inputSchema->getModel($reference->getDestination())->getBehaviour("log")) {
                    continue;
                }
                $destination = $schema->getTable($reference->getDestination());
                $foreignProperties = array();
                foreach($reference->getProperties() as $property) {
                    $foreignProperties[] = $property->getForeignName();
                }
                $localProperties = array_keys($reference->getProperties());

                $table->addForeignKeyConstraint($destination, $localProperties, $foreignProperties);
            }
        }

        foreach($inputSchema->getSequences() as $sequence) {
            $schema->createSequence($sequence->getName());
        }

        return $schema;
    }
}