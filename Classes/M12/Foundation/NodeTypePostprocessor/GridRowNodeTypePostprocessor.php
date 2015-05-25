<?php
namespace M12\Foundation\NodeTypePostprocessor;

class GridRowNodeTypePostprocessor extends AbstractGridNodeTypePostprocessor {

	/**
	 * Settings section used to generate properties for AbstractGenericHelpers node type
	 * @see Settings.yaml
	 * @var string
	 */
	protected static $SETTINGS_SECTION = 'gridRowSettings';
}
