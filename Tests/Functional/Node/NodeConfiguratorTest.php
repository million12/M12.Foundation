<?php
namespace M12\Foundation\Tests\Functional\Node;

/*                                                                        *
 * This script belongs to the Neos package "M12.Foundation".              *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * Test case for the NodeConfiguratorTest
 */
class NodeConfiguratorTest extends \TYPO3\Neos\Tests\Functional\AbstractNodeTest {

	/**
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @var NodeInterface
	 */
	protected $rootCC;
	
	
	public function setUp() {
		parent::setUp();
		$this->nodeTypeManager = $this->objectManager->get('TYPO3\TYPO3CR\Domain\Service\NodeTypeManager');

		// Get any CC from $this->node: we'll be creating there our test nodes
		$ccNodes = $this->node->getChildNodes('TYPO3.Neos:ContentCollection');
		$this->rootCC = array_shift($ccNodes);
	}
	
	/**
	 * Test standard/basic assistance nodes
	 */
	public function testNodeConfigurator() {
		// Test Alert
		$newNode = $this->rootCC->createNode(uniqid('node-'), $this->nodeTypeManager->getNodeType('M12.Foundation:Alert'));
		$this->assertEquals(1, $newNode->getNumberOfChildNodes());
		
		// Test Panel
		$newNode = $this->rootCC->createNode(uniqid('node-'), $this->nodeTypeManager->getNodeType('M12.Foundation:Panel'));
		$this->assertEquals(1, $newNode->getNumberOfChildNodes());
		
		// Test Orbit
		$newNode = $this->rootCC->createNode(uniqid('node-'), $this->nodeTypeManager->getNodeType('M12.Foundation:Orbit'));
		$this->assertEquals(3, $newNode->getNumberOfChildNodes());

		// Test Tabs
		$newNode = $this->rootCC->createNode(uniqid('node-'), $this->nodeTypeManager->getNodeType('M12.Foundation:Tabs'));
		$this->assertEquals(3, $newNode->getNumberOfChildNodes());

		// Test Accordion
		$newNode = $this->rootCC->createNode(uniqid('node-'), $this->nodeTypeManager->getNodeType('M12.Foundation:Accordion'));
		$this->assertEquals(3, $newNode->getNumberOfChildNodes());
	}
	
	public function testNodeConfigurator_Grid() {
		// Test 2-column Grid
		$newNode = $this->rootCC->createNode(uniqid('node-'), $this->nodeTypeManager->getNodeType('M12.Foundation:GridRow2Col'));
		$this->assertEquals(2, $newNode->getNumberOfChildNodes());
		/** @var NodeInterface $columnNode */
		$columnNode = $newNode->getChildNodes()[0];
		$this->assertEquals(1, $columnNode->getNumberOfChildNodes());
	}
	
	public function testNodeConfigurator_LinkingWithButton() {
		$newNode = $this->rootCC->createNode(uniqid('node-'), $this->nodeTypeManager->getNodeType('M12.Foundation:RevealModal'));
		$this->assertEquals(2, $newNode->getNumberOfChildNodes());

		$buttonNodes = $this->rootCC->getChildNodes('M12.Foundation:Button');
		/** @var NodeInterface $lastButton */
		$lastButton = array_pop($buttonNodes);
		$this->assertNotEmpty($lastButton);
		
		$this->assertNotEmpty($lastButton->getProperty('htmlDataRevealId'));
		$this->assertContains('link-node', $lastButton->getProperty('htmlDataRevealId'));
	}
	
	public function testNodeConfigurator_Form() {
		$newNode = $this->rootCC->createNode(uniqid('node-'), $this->nodeTypeManager->getNodeType('M12.Foundation:Form'));
		$this->assertEquals(1, $newNode->getNumberOfChildNodes());
	}
}
