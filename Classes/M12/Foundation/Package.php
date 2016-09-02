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
class Package extends BasePackage
{

    /**
     * Invokes custom PHP code directly after the package manager has been initialized.
     *
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect('TYPO3\TYPO3CR\Domain\Model\Node', 'afterNodeCreate',
            'M12\Foundation\Node\NodeConfigurator', 'afterNodeCreate');
    }
}
