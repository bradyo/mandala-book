<?php
namespace Mandala\DesignModule;

class DesignDataValidator 
{
    /**
     * @param array $jsonDesignData json string of design data
     * @return array array of error messages
     */
    public function validate($jsonDesignData)
    {
        $errors = array();
        $data = json_decode($jsonDesignData, true);
        if (json_last_error()  === JSON_ERROR_NONE) {
            if (count($data) === 0) {
                $errors[] = 'Layers data cannot by empty';
            }
            foreach ($data as $layerData) {
                $errors = array_merge($errors, $this->validateLayerData($layerData));
            }
        } else {
            $errors[] = 'Invalid json string';
        }
        return $errors;
    }

    public function validateLayerData(array $layerData)
    {
        $errors = array();
        $requiredProperties = array(
            'shapeType',
            'shapeSize',
            'shapeCount',
            'displacement',
            'angleOffset',
            'rotation',
        );
        foreach ($requiredProperties as $property) {
            if (! array_key_exists($property, $layerData)) {
                $errors[] = 'Layer property "' . $property . '" is required';
            } else {
                switch ($property) {
                    case 'shapeType':
                        if (! in_array($layerData['shapeType'], Design::$shapes)) {
                            $errors[] = 'shapeType must be one of the following: ' . join(', ', Design::$shapes);
                        }
                        break;
                    case 'shapeSize':
                        if ($layerData['shapeSize'] < 0 || $layerData['shapeSize'] > 360) {
                            $errors[] = 'shapeSize must be between 0 and 360';
                        }
                        break;
                    case 'shapeCount':
                        if ($layerData['shapeCount'] < 0 || $layerData['shapeCount'] > 50) {
                            $errors[] = 'shapeCount must be between 0 and 50';
                        }
                        break;
                    case 'displacement':
                        if ($layerData['displacement'] < 0 || $layerData['displacement'] > 1000) {
                            $errors[] = 'displacement must be between 0 and 1000';
                        }
                        break;
                    case 'angleOffset':
                        if ($layerData['angleOffset'] < 0 || $layerData['angleOffset'] > 360) {
                            $errors[] = 'angleOffset must be between 0 and 360';
                        }
                        break;
                    case 'rotation':
                        if ($layerData['rotation'] < 0 || $layerData['rotation'] > 360) {
                            $errors[] = 'rotation must be between 0 and 360';
                        }
                        break;
                }
            }
        }
        return $errors;
    }
}