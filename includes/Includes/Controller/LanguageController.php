<?php
namespace Propeller\Includes\Controller;

class LanguageController extends BaseController {
    protected $add_flag = false;
    protected $trp_available = false;
    public $languages = [];

    public function __construct() {
        $this->trp_available = (in_array('translatepress-multilingual/index.php', apply_filters('active_plugins', get_option('active_plugins'))) && class_exists('TRP_Translate_Press'));

        if ($this->trp_available) {
            parent::__construct();

            add_shortcode('propel-language-switcher', [$this, 'custom_language_switcher'], 10);
        }
    }

    public function custom_language_switcher() {
        global $propel;

        ob_start();

        $langs = trp_custom_language_switcher();

        foreach ($langs as $name => $item) {    
            $item_lang = $item['short_language_name'];
            if (strpos($item_lang, '_'))
                $item_lang = explode('_', $item_lang)[1];

            if (strtolower(PROPELLER_LANG) != $item_lang && isset($propel['url_slugs'])) {
                $link_chunks = explode('/', $item['current_page_url']);
                
                $slug = $this->get_lang_slug($item_lang, $propel['url_slugs']);

                if ($slug != "") {
                    $link_chunks[count($link_chunks) - 2] = $slug;

                    $item['current_page_url'] = implode('/', $link_chunks);
                }
            }

            $this->languages[$name] = $item;
        }

        require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-language-switcher.php');
        
        return ob_get_clean();
    }

    private function get_lang_slug($lang, $slugs) {
        foreach ($slugs as $slug) {
            if (strtolower($slug->language) == $lang)
                return $slug->value;
        }

        return "";
    }
}

?>