<?php

namespace Propeller\Admin;

use DirectoryIterator;
use Gettext\Generator\MoGenerator;
use Gettext\Generator\PoGenerator;
use Gettext\Loader\PoLoader;
use Gettext\Scanner\PhpScanner;
use Gettext\Translation;
use Gettext\Translations;
use stdClass;

class PropellerTranslations {
    protected $loader;
    protected $mo_generator; 
    protected $po_generator; 

    protected $translations_path;
    protected $templates = [];
    protected $translations = [];
    protected $generated_translations = [];
    protected $headers = [];

    public function __construct() {
        $this->loader = new PoLoader();    
        $this->mo_generator = new MoGenerator();
        $this->po_generator = new PoGenerator();

        $translations_dir = PROPELLER_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'languages';
        $custom_translations_dir = PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'languages';
        
        $this->translations_path = is_dir($custom_translations_dir) ? $custom_translations_dir : $translations_dir;
        
        $this->get_files();
        $this->build_headers();
    }

    public function get_files() {
        $files = array_diff(scandir($this->translations_path), array('.', '..'));

        if (count($files)) {
            foreach ($files as $file) {
                $info = pathinfo($file);

                if ($info['extension'] == 'pot') 
                    $this->templates[] = $this->translations_path . DIRECTORY_SEPARATOR . $file;
                else if ($info['extension'] == 'po') 
                    $this->translations[] = $this->translations_path . DIRECTORY_SEPARATOR . $file;
                else if ($info['extension'] == 'mo') 
                    $this->generated_translations[] = $this->translations_path . DIRECTORY_SEPARATOR . $file;
            }
        }
    }

    public function get_templates() {
        return $this->templates;
    }

    public function get_translations() {
        return $this->translations;
    }

    public function load_translation($file) {
        $translation = $this->get_translations_file($file);

        if ($translation->position > -1 && count($this->translations))
            return $this->loader->loadFile($this->translations[$translation->position]);

        return [];
    }

    private function get_translations_file($file) {
        $res = new stdClass();
        $res->position = -1;
        $res->file = '';

        if (count($this->translations)) {
            foreach ($this->translations as $translation_file) {
                if (basename($translation_file) == $file) {
                    $res->position++;
                    $res->file = $translation_file;

                    break;
                }
                    
                $res->position++;
            }
        }

        return $res;
    }

    public function get_available_languages() {
        return [
            'en_US' => 'English',
            'nl_NL' => 'Dutch'
        ];
    }

    public function save_translations() {
        $_data = $_POST;

        $translations = $this->load_translation($_data['po_file']);        
        $translations_file = $this->get_translations_file($_data['po_file']);

        for ($i = 0; $i < count($_data['original']); $i++) {
            $translation = $translations->find(null, $_data['original'][$i]);

            if ($translation)
                $translation->translate($_data['translation'][$i]);
        }

        $this->apply_headers($translations);

        $this->po_generator->generateFile($translations, $translations_file->file);

        die(json_encode(['success' => true, 'message' => __('Translations saved', 'propeller-ecommerce')]));
    }

    public function scan_translations() {
        ini_set('memory_limit', '1024M');
        
        $phpScanner = new PhpScanner(
            Translations::create(PROPELLER_PLUGIN_NAME)
        );

        $phpScanner->setDefaultDomain(PROPELLER_PLUGIN_NAME);

        $phpScanner->extractCommentsStartingWith('__("', "__('");

        $files = [];

        $this->get_all_directory_and_files(PROPELLER_PLUGIN_DIR, $files);

        //Scan files
        foreach ($files as $file)
            $phpScanner->scanFile($file);

        $count = 0;

        foreach ($phpScanner->getTranslations() as $domain => $translations) {
            $count = count($translations);

            $this->apply_headers($translations);

            $this->po_generator->generateFile($translations, $this->translations_path . DIRECTORY_SEPARATOR . $domain . '.pot');
        }
        
        die(json_encode(['success' => true, 'total' => $count, 'message' => __('New translations template file created', 'propeller-ecommerce')]));
    }

    public function create_translations_file() {
        $translations = $this->loader->loadFile($this->templates[0]);

        $new_translations = $translations;

        if (isset($_REQUEST['merge']) && !empty($_REQUEST['merge'])) {
            $old_translations = $this->load_translation($_REQUEST['merge']);

            $new_translations = $old_translations->mergeWith($translations);
        }   

        $this->apply_headers($new_translations);

        $this->po_generator->generateFile($new_translations, $this->translations_path . DIRECTORY_SEPARATOR . PROPELLER_PLUGIN_NAME . '-' . $_REQUEST['locale'] . '.po');

        die(json_encode([
            'success' => true, 
            'file' => PROPELLER_PLUGIN_NAME . '-' . $_REQUEST['locale'] . '.po',
            'tab' => $_REQUEST['tab'],
            'page' => $_REQUEST['page'],
            'action' => 'open_translation',
            'message' => __('New translations file created', 'propeller-ecommerce') . ': ' . PROPELLER_PLUGIN_NAME . '-' . $_REQUEST['locale'] . '.po'
        ]));
    }

    public function generate_translations() {
        $_data = $_POST;

        $translations = $this->load_translation($_data['po_file']);        
        $translations_file = $this->get_translations_file($_data['po_file']);

        $path_parts = pathinfo($translations_file->file);

        $this->mo_generator->generateFile($translations, $this->translations_path . DIRECTORY_SEPARATOR . $path_parts['filename'] . '.mo');

        die(json_encode([
            'success' => true, 
            'message' => __('New translations are generated', 'propeller-ecommerce') 
            // 'file' => PROPELLER_PLUGIN_NAME . '-' . $_REQUEST['locale'] . '.po',
            // 'tab' => $_REQUEST['tab'],
            // 'page' => $_REQUEST['page'],
            // 'action' => 'open_translation',
        ]));
    }

    private function build_headers() {
        $this->headers["Project-Id-Version"] = "Propeller E-Commerce " . PROPELLER_VERSION;
        $this->headers["Report-Msgid-Bugs-To"] = "https://wordpress.org/support/plugin/propeller-ecommerce";
        $this->headers["Last-Translator"] = "Propeller <info@propel.us>";
        $this->headers["Language-Team"] = "NL <info@propel.us>";
        $this->headers["MIME-Version"] = "1.0";
        $this->headers["Content-Type"] = "text/plain; charset=UTF-8";
        $this->headers["Content-Transfer-Encoding"] = "8bit";
        $this->headers["POT-Creation-Date"] = "2022-04-19T10:53:53+00:00";
        $this->headers["PO-Revision-Date"] = "2022-04-19T10:53:53+00:00";
        $this->headers["X-Generator"] = "php-gettext";
        $this->headers["X-Domain"] = PROPELLER_PLUGIN_NAME;
    }

    private function apply_headers(&$translations) {
        foreach ($this->headers as $key => $val) 
            $translations->getHeaders()->set($key, $val);
    }

    private function get_all_directory_and_files($dir, &$files){
        $ignore = [
            '.gitignore',
            '.git',
            'vendor',
            'composer.json',
            'composer.lock',
            'readme.txt',
            'bootstrap.min.css',
            'bootstrap.min.js',
            'jquery.min.js',
        ];
 
        $dh = new DirectoryIterator($dir);   
        
        foreach ($dh as $item) {
            if (!$item->isDot()) {
                if (in_array($item->getBasename(), $ignore))
                    continue;

                if ($item->isDir())
                    $this->get_all_directory_and_files("$dir" . DIRECTORY_SEPARATOR . "$item", $files);
                else
                    $files[] = $dir . DIRECTORY_SEPARATOR . $item->getFilename();
            }
        }
    }
}