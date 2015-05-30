<?php
namespace M12\Foundation\Node;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Exception\NodeConfigurationException;

/**
 * Class NodeConfigurator listens to `afterNodeCreate` from CR
 * and create/configure nodes according to `assistanceChildNodes` settings.
 *
 * @Flow\Scope("singleton")
 */
class NodeConfigurator {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * Grid size (num of columns)
	 * 
	 * @Flow\InjectConfiguration("gridSize")
	 * @var array
	 */
	protected $gridSize;

	/**
	 * Assistance/help child nodes to be created when given node type is created.
	 *
	 * @Flow\InjectConfiguration("assistanceChildNodes")
	 * @var array
	 */
	protected $assistanceChildNodes;

	/**
	 * When FALSE, assistance nodes configured in Settings `assistanceChildNodes`
	 * won't be created automatically.
	 * 
	 * This is set internally when we want to switch that off
	 * temporarily because we want to handle it differently.
	 * E.g. we might want to not create default Text nodes
	 * inside each `M12.Foundation:GridRow2Col` column,
	 * instead we want create sth else there.
	 * 
	 * @var bool
	 */
	protected $enableAutoCreateNodes = TRUE;

	/**
	 * Get config/settings for given node type
	 * 
	 * @param string $nodeTypeName
	 * @return array Array with (optional) keys
	 *               [beforeNodes, childNodes, afterNodes, enableAutoCreateNodes]
	 */
	public function getAssistanceConfigForNodeType($nodeTypeName) {
		return empty($this->assistanceChildNodes[$nodeTypeName]) 
			? [] 
			: $this->assistanceChildNodes[$nodeTypeName];
	}

	/**
	 * Hooks into `afterNodeCreate` event dispatched from CR,
	 * when new node has been created.
	 * 
	 * @param NodeInterface $node
	 */
	public function afterNodeCreate(NodeInterface $node) {
		if (!($nodeType = $node->getNodeType())) {
			return;
		}
		// array with [beforeNodes, childNodes, afterNodes] keys
		$config = $this->getAssistanceConfigForNodeType($nodeType->getName());
		
		switch ($nodeType->getName()) {
			case 'M12.Foundation:GridRow1Col':
			case 'M12.Foundation:GridRow2Col':
			case 'M12.Foundation:GridRow3Col':
			case 'M12.Foundation:GridRow4Col':
				$this->configureGridRow($node, $nodeType);
				break;
			
			case 'M12.Foundation:GridColumn':
				break; // assistance text nodes are created from $this->configureGridRow()
			
			case 'M12.Foundation:RevealModal':
				$this->configureLinkingButton($node, $nodeType, $config, 'htmlDataRevealId');
				break;
			
			case 'M12.Foundation:Dropdown':
			case 'M12.Foundation:DropdownContent':
				$this->configureLinkingButton($node, $nodeType, $config, 'htmlDataDropdownId');
				break;
			
			default:
				$this->configureCreateAssistanceChildNodes($node, $nodeType, $config);
				break;
		}
	}

	/**
	 * Configure assistance nodes: configures sub-nodes and their properties
	 * 
	 * @param NodeInterface $node
	 * @param NodeType      $nodeType
	 * @param array         $config
	 * @param array         $args
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeInterface[]
	 * @throws NodeConfigurationException
	 */
	protected function configureCreateAssistanceChildNodes(NodeInterface $node, NodeType $nodeType, array $config, $args = []) {
		// empty $config equals no nodes to create
		if (empty($config)) {
			return [];
		}
		
		if (isset($config['properties'])) {
			$this->setNodeProperties($node, $config['properties'], $args);
		}
		
		$createdNodes = [];
		
		// Only proceed if $enableAutoCreateNodes was not set to FALSE before (on previous level)
		if (TRUE === $this->enableAutoCreateNodes) {
			if (isset($config['beforeNodes'])) {
				$createdNodes += $this->createNodes($node, $nodeType, $config['beforeNodes'], 'before', $args);
			}
			if (isset($config['childNodes'])) {
				$createdNodes += $this->createNodes($node, $nodeType, $config['childNodes'], 'inside', $args);
			}
			if (isset($config['afterNodes'])) {
				$createdNodes += $this->createNodes($node, $nodeType, $config['afterNodes'], 'after', $args);
			}
		}
		
		return $createdNodes;
	}

	/**
	 * Create nodes. If any node has defined any sub-nodes again,
	 * it calls configureCreateAssistanceChildNodes() recursively.
	 * 
	 * @param NodeInterface $node
	 * @param NodeType      $nodeType
	 * @param array         $nodesToCreate
	 * @param string        $position: before, after, inside
	 * @param array         $args: arguments to be replaced in in property values (via vsprintf())
	 * @return NodeInterface[]
	 * @throws NodeConfigurationException
	 * @throws \TYPO3\TYPO3CR\Exception\NodeConstraintException
	 * @throws \TYPO3\TYPO3CR\Exception\NodeException
	 * @throws \TYPO3\TYPO3CR\Exception\NodeExistsException
	 * @throws \TYPO3\TYPO3CR\Exception\NodeTypeNotFoundException
	 */
	protected function createNodes(NodeInterface $node, NodeType $nodeType, array $nodesToCreate, $position, array $args = []) {
		$createdNodes = [];
		foreach ($nodesToCreate as $config) {
			if (FALSE === isset($config['nodeType'])) {
				throw new NodeConfigurationException("Missing `nodeType` to create for node `{$nodeType->getName()}`.", 1432821591);
			}
			
			// New node is just about to be created. Do we need to switch off auto-childNodes creation
			if (isset($config['enableAutoCreateNodes']) && FALSE === $config['enableAutoCreateNodes'])
				$this->enableAutoCreateNodes = FALSE;
			
			$newNodeType = $this->nodeTypeManager->getNodeType($config['nodeType']);
			if ('before' === $position) {
				$newNode = $node->getParent()->createNode(uniqid('node-'), $newNodeType);
				$newNode->moveBefore($node);
			} elseif ('after' === $position) {
				$newNode = $node->getParent()->createNode(uniqid('node-'), $newNodeType);
				$newNode->moveAfter($node);
			} else {
				$newNode = $node->createNode(uniqid('node-'), $newNodeType);
			}
			
			// Restore $enableAutoCreateNodes after node has been created
			$this->enableAutoCreateNodes = TRUE;
			
			$createdNodes[$newNode->getIdentifier()] = $newNode;
			
			if (isset($config['properties'])) {
				$this->setNodeProperties($newNode, $config['properties'], $args);
			}
			
			// do we have another [beforeNodes, childNodes, afterNodes] to create? Go on...
			$this->configureCreateAssistanceChildNodes($newNode, $newNodeType, $config, $args);
		}
		
		return $createdNodes;
	}

	/**
	 * Set node properties
	 * 
	 * @param NodeInterface $node
	 * @param array         $properties
	 * @param array         $args
	 */
	protected function setNodeProperties(NodeInterface $node, array $properties, array $args = []) {
		foreach ($properties as $property => $value) {
			$value = count($args) && is_string($value) ? vsprintf($value, $args) : $value;
			$node->setProperty($property, $value);
		}
	}

	/**
	 * Configure grid row element (M12.Foundation:GridRowXCol):
	 * - add child columns
	 * - set default column width (based on num of columns and grid size)
	 * - create text node with sample content in each column
	 * 
	 * @param NodeInterface $node
	 * @param NodeType      $nodeType: one of M12.Foundation:GridRowXCol
	 * @throws \TYPO3\TYPO3CR\Exception\NodeTypeNotFoundException
	 */
	protected function configureGridRow(NodeInterface $node, NodeType $nodeType) {
		if (FALSE === $this->enableAutoCreateNodes) {
			return;
		}
		
		$gs = $this->gridSize;
		
		// Get number of columns to create from M12.Foundation:GridRowXCol node type name
		$cols = (int) substr($nodeType->getName(), -4, 1);
		for ($k = 1; $k <= $cols; $k++) {
			$newNodeType = $this->nodeTypeManager->getNodeType('M12.Foundation:GridColumn');
			$newNode = $node->createNode(uniqid('node-'), $newNodeType);
			// configure sensible default columns width
			if (1 === $cols) {
				$newNode->setProperty('classGridSize', ['small-'.$gs]);
			} elseif (4 === $cols) {
				$newNode->setProperty('classGridSize', ['small-'.$gs, 'medium-'.($gs/2), 'large-'.$gs/4]);
			} else {
				$newNode->setProperty('classGridSize', ['small-'.$gs, 'medium-'.((int)$gs/$cols)]);
			}
			
			// Create sample content in each column
			$config = $this->getAssistanceConfigForNodeType($newNodeType->getName());
			$this->configureCreateAssistanceChildNodes($newNode, $newNodeType, $config, [$k]);
		}
	}

	/**
	 * Configure given node according to `assistanceChildNodes` settings
	 * + links the node with the button/link (e.g. reveal modal needs a trigger button,
	 * so it links it together and it works out of the box)
	 * 
	 * @param NodeInterface $node
	 * @param NodeType      $nodeType
	 * @param array         $config
	 * @param string        $buttonLinkPropertyName: name of property to set with linkId on link/button
	 */
	protected function configureLinkingButton(NodeInterface $node, NodeType $nodeType, array $config, $buttonLinkPropertyName) {
		// Create any child nodes, if configured
		$createdNodes = $this->configureCreateAssistanceChildNodes($node, $nodeType, $config);
		
		foreach ($createdNodes as $maybeButtonNode) {
			// Take the 1st created button/link and link it with dropdown node
			if ($maybeButtonNode->getNodeType()->isOfType('M12.Foundation:ButtonLinkAbstract')) {
				$linkId = uniqid('link-node-');
				$node->setProperty('customHtmlId', $linkId);
				$maybeButtonNode->setProperty($buttonLinkPropertyName, $linkId);
				break;
			}
		}
	}
}
