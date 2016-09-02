<?php
namespace M12\Foundation\NodeTypePostprocessor;

class GridColumnNodeTypePostprocessor extends AbstractGridNodeTypePostprocessor
{

    /**
     * Settings section used to generate grid column properties
     * @see Settings.yaml
     * @var string
     */
    protected static $SETTINGS_SECTION = 'gridColumnSettings';

    protected static $PROPERTY_NAME_PATTERN = 'classGrid%s';
}
