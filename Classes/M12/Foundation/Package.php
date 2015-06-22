<?php
namespace M12\Foundation;

/*                                                                        *
 * This script belongs to the "M12.Foundation" package.                   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Package\Package as BasePackage;
use TYPO3\Flow\Core\Bootstrap;

/**
 * The M12.Foundation Package
 */
class Package extends BasePackage {

	/**
	 * Invokes custom PHP code directly after the package manager has been initialized.
	 *
	 * @param Bootstrap $bootstrap The current bootstrap
	 * @return void
	 */
	public function boot(Bootstrap $bootstrap) {
		$signalDispatcher = 'TYPO3\Neos\Service\NodeOperations';
		$signalName = 'afterNodeCreate';
		
		// We don't use `NodeOperations` during tests, so listen to original signal from TYPO3CR
		if ($bootstrap->getContext()->isTesting()) {
			$signalDispatcher = 'TYPO3\TYPO3CR\Domain\Model\Node';
		}
		
		$dispatcher = $bootstrap->getSignalSlotDispatcher();
		$dispatcher->connect($signalDispatcher, $signalName, 'M12\Foundation\Node\NodeConfigurator', 'afterNodeCreate');
	}
}
