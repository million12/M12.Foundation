<?php
namespace M12\Foundation\Tests\Functional\DataSource;

/*                                                                        *
 * This script belongs to the Neos package "M12.Foundation".              *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Tests\FunctionalTestCase;
use M12\Foundation\DataSource\FontIconDataSource;

/**
 * Test case for the FontIconDataSourceTest
 */
class FontIconDataSourceTest extends FunctionalTestCase {
	
	public function testGetData() {
		$ds = new FontIconDataSource();
		$data = $ds->getData(NULL, []);
		
		$this->assertGreaterThanOrEqual(500, count($data));
		
		$key = array_keys($data)[0];
		$this->assertContains('fa-', $key);
		$this->assertArrayHasKey('label', $data[$key]);
		$this->assertArrayHasKey('icon', $data[$key]);
	}
}
