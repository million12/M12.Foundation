# M12.Foundation: Zurb Foundation components for Neos CMS
[![Circle CI](https://circleci.com/gh/million12/M12.Foundation.svg?style=svg)](https://circleci.com/gh/million12/M12.Foundation)

M12.Foundation aims to implement majority of [Zurb Foundation](http://foundation.zurb.com/) (v5) components, in the best possible way, inside [Neos CMS](http://neos.io/).

# Features

### Implemented Zurb Foundation components

* Accordion
* Alert and Panel
* Button
* Dropdown (with links or any content)
* Font Icon (based on [Font Awesome](http://fontawesome.io/) icons)
* Form elements: Form Container, Fieldset, Label (incl. pre/postfix labels), Input, Checkbox, Radio, Select, Textarea
* Grid: Block Grid, Grid Row with Grid Column, simple Container
* Lightbox (for images in Block Grid) 
* Navigation: TopBar
* Navigation: Side Nav, Sub Nav
* Navigation: Magellan Sticky Navigation support
* Orbit Slider
* Reveal Modal (incl. support for opening images in modal window)
* Tabs
* Tooltips
* Video (Flex Video)

### Other features

* Automatically pre-configured complex components (so called `Assistance Nodes`). Thanks to that feature, you get complex components working out-of-the-box. Examples:
  * `Reveal Modal`: when you add this component to the page, it will also create a `Button` and wire it together with `Reveal Modal`, so you can just click it, see the modal and edit its content.
  * `Dropdown`: when you add this component to the page, it will also create a `Button` and wire it together with Dropdown, so you can just click it, see the dropdown and edit its content.
  * `Grid Row`: for example adding a grid row with 4 columns will set sensible defaults for columns width for each device (i.e. small 12/12, medium 6/12, large 4/12).
  * `Orbit Slider`: adding this component will also add 3 child nodes with dummy images.
  * `Form Container`: adding this component will create sample responsive form for you, incl. all grid row components, labels inside etc.
  
  See [Settings.yaml](Configuration/Settings.yaml) for full `Assistance Nodes` configuration (`M12.Foundation:assistanceChildNodes` section).

* Background image support (both from Media library and using external image) for many components. Thanks to that you can easily create a'la Hero components.
* Spacing before/after and left/right from Inspector for each component.
* Semantic tags: ability to define custom, semantic tag (e.g. `section` instead of `div`) for most components.
* Ability to define several Foundation common-use classes like `rounded`, `radius`, floats, text-align to almost all components (or where applicable).
* Tooltips: ability to use them with many components, incl. default Neos compnents (e.g. Headline).
* Font Icons: ability to add it to components where it make sense (i.e. Button).

... and many more.

Check the [issue tracker](https://github.com/million12/M12.Foundation/issues) to see current work in progress and/or future plans.  
Check the [releases](https://github.com/million12/M12.Foundation/releases) page to see stable releases for your Neos version.

## Usage

The best way is to install it together with [M12.FoundationSite](https://github.com/million12/M12.FoundationSite) site package, which has all CSS/JS in it, including all JS code to work around Neos (e.g. re-initialising Foundation components as soon as they're added to the page).

Note: pay attention to release notes to match the right version with your Neos version.

Include in your main `composer.json` the `m12/neos-foundation` and `m12/neos-foundation-site` dependencies:  
``` json
    "require": {
        "other-dependenies-here...": "*",
        "m12/neos-foundation": "dev-master",
        "m12/neos-foundation-site": "dev-master"
    },
```  
and run `composer install`

## Usage with `neos-protobrew-distribution`

You can try this plugin with ready-to-use, working out-of-the-box
[neos-protobrew-distribution](https://github.com/million12/neos-protobrew-distribution).
This is an open-source Neos distribution created for 
[PrototypeBrewery.io](http://prototypebrewery.io/) project
and it has `M12.Foundation` and `M12.FoundationSite` plugins installed 
and configured. If you're familiar with Docker, there's also Docker image
with the whole package, so it's very easy to try. See the instructions 
in the README there.


## Author(s)

* Marcin Ryzycki marcin@m12.io  
* Samuel Ryzycki samuel@m12.io
* Dmitri Pisarev dimaip@gmail.com

Licensed under: The MIT License (MIT)

**Sponsored by** [PrototypeBrewery.io - the new prototyping tool](http://prototypebrewery.io/) 
for building fully interactive prototypes of your website or web app. Built on top of 
Neos CMS and Zurb Foundation framework.
