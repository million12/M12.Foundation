# M12.Foundation - Foundation components inside TYPO3 Neos
[![Circle CI](https://circleci.com/gh/million12/M12.Foundation.svg?style=svg)](https://circleci.com/gh/million12/M12.Foundation)

M12.Foundation aims to implement all [Zurb Foundation](http://foundation.zurb.com/) components, in the best possible way, inside [TYPO3 Neos](http://neos.typo3.org/) CMS.

The best way is to install it together with [M12.FoundationSite](https://github.com/million12/M12.FoundationSite) Neos site package. Unfortunately, M12.Foundation **conflicts with NeosDemoTypo3Org**, so you'll have to remove `typo3/neosdemotypo3org` from your `composer.json`.

## Usage

This plugin currently works with TYPO3 Neos, version 2.0. See previous releases for older Neos versions.

Include in your main `composer.json` the `m12/neos-foundation` and `m12/neos-foundation-site` dependencies:  
``` json
    "require": {
        "other-dependenies-here...": "*",
        "m12/neos-foundation": "dev-master",
        "m12/neos-foundation-site": "dev-master"
    },
```  
and run `composer install`

## Usage with `neos-typostrap-distribution`

We encourage you to try ready-to-use [neos-typostrap-distribution](https://github.com/million12/neos-typostrap-distribution), TYPO3 Neos distribution which has this package installed and configured. We also prepared Docker image with whole package, so you can launch the project in no time. See the instructions in the README there.


## Author(s)

* Marcin Ryzycki marcin@m12.io  
* Samuel Ryzycki samuel@m12.io
