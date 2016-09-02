<?php

namespace M12\Foundation\Eel;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Eel\ProtectedContextAwareInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

class SpacingHelper implements ProtectedContextAwareInterface
{

    const DEFAULT_UNIT = 'px';

    const PROPERTY_MAPPING = [
        'marginTop'    => 'margin-top',
        'marginBottom' => 'margin-bottom',
        'marginLeft'   => 'margin-left',
        'marginRight'  => 'margin-right',
    ];

    /**
     * Generate CSS styles from spacing* properties
     *
     * @param NodeInterface $node
     * @return string
     */
    public function generateCss(NodeInterface $node)
    {
        $css = '';

        foreach (self::PROPERTY_MAPPING as $key => $cssProperty) {
            // Get value, also check for `0` values using strlen()
            if (($value = $node->getProperty($key)) || strlen($value)) {
                // if numeric value is provided, add default unit (e.g. px)
                // but don't add it to zero values
                if ($value && is_numeric($value)) {
                    $value .= self::DEFAULT_UNIT;
                }

                $css .= "$cssProperty:$value;";
            }
        }

        return $css;
    }

    /**
     * All methods are considered safe, i.e. can be executed from within Eel
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
