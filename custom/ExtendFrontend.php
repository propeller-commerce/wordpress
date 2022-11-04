<?php

namespace Propeller\Custom;

use Propeller\Frontend\PropellerFrontend;
use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

/**
 * Translations: 
 * 
 * Make .pot file from within the plugin root directory executing the command:
 *  wp i18n make-pot . .\Custom\languages\propeller-ecommerce.pot
 * 
 * Copy that file into a new one, but rename it with the locale suffix, for example nl_NL.
 * The new file should have extension .po and it's name should like this: propeller-ecommerce-nl_NL.po
 * Make sure you add your translations in this .po file before creating the json file.
 * 
 * Make a json file with all the translations inside the .po file by running this command from within
 * the root folder of the plugin: wp i18n make-json .\Custom\languages\propeller-ecommerce-nl_NL.po --no-purge
 * 
 */

class ExtendFrontend extends PropellerFrontend {
    protected $propeller;
    protected $version;

    public function __construct($propeller, $version) {
        $this->propeller = $propeller;
        $this->version = $version;  
    }

	/**
     * Propeller shortcodes overrides goes from here on
     * 
     */
}