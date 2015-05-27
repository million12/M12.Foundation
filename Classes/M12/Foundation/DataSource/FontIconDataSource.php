<?php
namespace M12\Foundation\DataSource;

use TYPO3\Neos\Service\DataSource\AbstractDataSource;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\Flow\Cache\CacheManager;

/**
 * Class FontIconDataSource provides data for field 'faName'
 * in M12.Foundation:AbstractFontIcon node type
 * with list of all available icon names.
 */
class FontIconDataSource extends AbstractDataSource {

	/**
	 * @var string
	 */
	static protected $identifier = 'font-icon-list';

	/**
	 * Path to file containing list of available FontAwesome icons
	 * 
	 * @var string
	 */
	static protected $iconsListFilePath = 'resource://M12.Foundation/Private/Misc/font-icon-list.scss';

	/**
	 * @var CacheManager
	 */
	protected $cacheManager;

	/**
	 * @param CacheManager $cacheManager
	 * @return void
	 */
	public function injectCacheManager(CacheManager $cacheManager) {
		$this->cacheManager = $cacheManager;
	}

	/**
	 * Get icons list, compiled from FontAwesome variables list
	 *
	 * @param NodeInterface $node The node that is currently edited (optional)
	 * @param array $arguments Additional arguments (key / value)
	 * @return array JSON serializable data
	 */
	public function getData(NodeInterface $node = NULL, array $arguments) {
		$data = [];
		
		foreach ($this->parseListOfIcons() as $iconName) {
			$data['fa-'.$iconName] = [
				'label' => $iconName,
				'icon' => 'fa fa-' . $iconName,
			];
		}
		
		return $data;
	}

	/**
	 * Parse list of icons and get pure icon names.
	 * 
	 * @return array
	 */
	protected function parseListOfIcons() {
		$cache = $this->cacheManager->getCache('Default');
		$cacheId = 'FontIconDataSource_parseListOfIcons';
		
		if (!($icons = $cache->get($cacheId))) {
			$icons = [];
			foreach (file(self::$iconsListFilePath) as $content) {
				if (preg_match('#fa-var-([-_a-z0-9]+):#i', $content, $match)) {
					$icons[] = $match[1];
				}
			}

			$cache->set($cacheId, $icons, [], 0);
		}
		
		return $icons;
	}
}
