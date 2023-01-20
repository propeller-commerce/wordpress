<?php

namespace Propeller\Admin;

use DirectoryIterator;
use Exception;
use Gettext\Generator\MoGenerator;
use Gettext\Generator\PoGenerator;
use Gettext\Loader\PoLoader;
use Gettext\Scanner\PhpScanner;
use Gettext\Translation;
use Gettext\Translations;
use Propeller\FileHandler;
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

        $this->translations_path = $this->get_translations_path();
        
        $this->get_files();
        $this->build_headers();
    }

    private function get_translations_path() {
        $translations_dir = PROPELLER_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'languages';
        $custom_translations_dir = PROPELLER_PLUGIN_EXTEND_DIR . DIRECTORY_SEPARATOR . 'languages';
        
        return is_dir($custom_translations_dir) ? $custom_translations_dir : $translations_dir;
    }

    private function get_lang_bkp_dir() {
        $uploads_dir = wp_upload_dir();

        $propel_backup_dir = $uploads_dir['basedir'] . DIRECTORY_SEPARATOR . 'propeller-ecommerce';

        if (!is_dir($propel_backup_dir)) {
            if (!wp_mkdir_p($propel_backup_dir))
                propel_log("$propel_backup_dir cannot be created \r\n");
        }
        
        $langs_backup_dir = $propel_backup_dir . DIRECTORY_SEPARATOR . 'languages';
        
        if (!is_dir($langs_backup_dir)) {
            if (!wp_mkdir_p($langs_backup_dir))
                propel_log("$langs_backup_dir cannot be created \r\n");
        }
            
        return $langs_backup_dir;
    }

    protected function backup_languages() {
        $backups_dir = $this->get_lang_bkp_dir();

        if (!$this->translations_path)
            $this->translations_path = $this->get_translations_path();

        $now = date('Y-m-d+H_i_s');

        $backup_dir = $backups_dir . DIRECTORY_SEPARATOR . $now;

        if (!is_dir($backup_dir)) {
            if (wp_mkdir_p($backup_dir)) 
                FileHandler::copy_dir($this->translations_path, $backup_dir);
            else 
                propel_log("$backup_dir cannot be created \r\n");
        }

        $this->purge_backups();
    }

    private function purge_backups() {
        $bkps = $this->get_backups();

        if (count($bkps) > 10) {
            FileHandler::rmdir($bkps[0]);

            if (is_dir($bkps[0]))
                propel_log('Cannot delete: ' . $bkps[0] . "\r\n");
        }
    }

    public function restore_translations() {
        $data = $_POST;
        $success = true;

        if (!wp_verify_nonce($data['nonce'], 'ajax-nonce' ) )
            die(json_encode(['success' => false, 'message' => __('Security check failed', 'propeller-ecommerce')]));

        if (empty($data['backup_date']))
            die(json_encode(['success' => false, 'message' => __('Please select a backup', 'propeller-ecommerce')]));

        if (current_user_can('manage_options')) {
            try {
                if (!$this->translations_path)
                    $this->translations_path = $this->get_translations_path();

                $backups_dir = $this->get_lang_bkp_dir();

                $selected_bkp = $backups_dir . DIRECTORY_SEPARATOR . $data['backup_date'];

                FileHandler::copy_dir($selected_bkp, $this->translations_path);

                $msg = __('Translations restored', 'propeller-ecommerce') . ': ' . str_replace('_', ':', $data['backup_date']);
            }
            catch (Exception $ex) {
                $success = false;
                $msg = $ex->getMessage();
            }
        }
        else {
            $success = false;
            $msg = __('Not enought rights to save translations', 'propeller-ecommerce');
        }

        die(json_encode([
            'success' => $success, 
            'message' => $msg
        ]));
    }

    public function get_backups() {
        $backups_dir = $this->get_lang_bkp_dir();

        return FileHandler::scan_dir($backups_dir)['dirs'];
    }

    public function load_translations_backups() {
        $this->purge_backups();

        $success = true;
        $msg = '';
        $opts = [];

        try {
            $backups_dir = $this->get_lang_bkp_dir();

            $backups = FileHandler::scan_dir($backups_dir)['dirs'];
        
            if (count($backups)) {
                $opts[] = '<option value="">' . __('Restore translations', 'propeller-ecommerce') . '</option>';
                
                foreach ($backups as $bkp) {
                    $opts[] = '<option value="' . wp_basename($bkp) . '">' . str_replace('+', ' ', str_replace('_', ':', wp_basename($bkp))) . '</option>';
                }
            }
        }
        catch (Exception $ex) {
            $success = false;
            $msg = $ex->getMessage();
        }
        
        die(json_encode(['success' => $success, 'message' => $msg, 'options' => implode('', $opts)]));
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

        if ($translation->position > -1 && count($this->translations)) {
            try {
                return $this->loader->loadFile($this->translations[$translation->position]);
            }
            catch (Exception $ex) {
                var_dump($ex->getMessage());
                propel_log($ex->getMessage());
            }
        }

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
        $locales = include PROPELLER_PLUGIN_DIR . '/includes/Locales.php'; 
        
        return $locales;
    }

    public function save_translations() {
        $_data = $_POST;

        $msg = __('Translations saved', 'propeller-ecommerce');
        $success = true;

        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce' ) )
            die(json_encode(['success' => false, 'message' => __('Security check failed', 'propeller-ecommerce')]));

        if (current_user_can('manage_options')) {
            if (empty($_data['po_file']))
                die(json_encode(['success' => false, 'message' => __('Please select a translations file', 'propeller-ecommerce')]));
            
            $this->backup_languages();

            $translations = $this->load_translation($_data['po_file']);        
            $translations_file = $this->get_translations_file($_data['po_file']);
            
            for ($i = 0; $i < count($_data['original']); $i++) {
                try {
                    if (empty($_data['original'][$i]))
                        continue;

                    $_data['original'][$i] = htmlspecialchars_decode($_data['original'][$i]);
                    $_data['original'][$i] = stripslashes($_data['original'][$i]);

                    $translation = $translations->find(null, $_data['original'][$i]);
        
                    if ($translation)
                        $translation->translate($_data['translation'][$i]);
                    else 
                        propel_log("- Original string for " . $_data['original'][$i] . "not found\r\n");
                }
                catch (Exception $ex) {
                    $success = false;
                    $msg = $ex->getMessage();
                    propel_log($ex->getMessage());
                }
            }
    
            try {
                $this->apply_headers($translations);
        
                $this->po_generator->generateFile($translations, $translations_file->file);
            }
            catch (Exception $ex) {
                $success = false;
                $msg = $ex->getMessage();
                propel_log($ex->getMessage());
            }
        }
        else {
            $success = false;
            $msg = __('Not enought rights to save translations', 'propeller-ecommerce');
        }

        die(json_encode(['success' => $success, 'message' => $msg]));
    }

    public function scan_translations() {
        $success = true;
        $msg = '';

        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce' ) )
            die(json_encode(['success' => false, 'message' => __('Security check failed', 'propeller-ecommerce')]));

        if (current_user_can('manage_options')) {
            ini_set('memory_limit', '1024M');

            $this->backup_languages();
        
            try {
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
    
                $msg = __('New translations template file created', 'propeller-ecommerce');
            }
            catch (Exception $ex) {
                $success = false;
                $msg = $ex->getMessage();
                propel_log($ex->getMessage());
            }
        }
        else {
            $success = false;
            $msg = __('Not enought rights to scan for new translations', 'propeller-ecommerce');
        }
        
        die(json_encode(['success' => $success, 'total' => $count, 'message' => $msg]));
    }

    public function create_translations_file() {
        $success = true;
        $msg = '';

        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce' ) )
            die(json_encode(['success' => false, 'message' => __('Security check failed', 'propeller-ecommerce')]));

        if (current_user_can('manage_options')) {
            
            $this->backup_languages();
            
            try {
                $translations = $this->loader->loadFile($this->templates[0]);

                $new_translations = $translations;
        
                if (isset($_REQUEST['merge']) && !empty($_REQUEST['merge'])) {
                    $old_translations = $this->load_translation($_REQUEST['merge']);
        
                    $new_translations = $old_translations->mergeWith($translations);
                }   
        
                $this->apply_headers($new_translations);
        
                $this->po_generator->generateFile($new_translations, $this->translations_path . DIRECTORY_SEPARATOR . PROPELLER_PLUGIN_NAME . '-' . $_REQUEST['locale'] . '.po');    
    
                $msg = __('New translations file created', 'propeller-ecommerce') . ': ' . PROPELLER_PLUGIN_NAME . '-' . $_REQUEST['locale'] . '.po';
            }
            catch (Exception $ex) {
                $success = false;
                $msg = $ex->getMessage();
                propel_log($ex->getMessage());
            }
        }
        else {
            $success = false;
            $msg = __('Not enought rights to create translations file', 'propeller-ecommerce');
        }

        die(json_encode([
            'success' => $success, 
            'file' => PROPELLER_PLUGIN_NAME . '-' . $_REQUEST['locale'] . '.po',
            'tab' => $_REQUEST['tab'],
            'page' => $_REQUEST['page'],
            'action' => 'open_translation',
            'message' => $msg
        ]));
    }

    public function generate_translations() {
        $success = true;
        $msg = '';

        if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce' ) )
            die(json_encode(['success' => false, 'message' => __('Security check failed', 'propeller-ecommerce')]));
            
        if (current_user_can('manage_options')) {
            $this->backup_languages();

            try {
                $_data = $_POST;

                $translations = $this->load_translation($_data['po_file']);        
                $translations_file = $this->get_translations_file($_data['po_file']);
        
                $path_parts = pathinfo($translations_file->file);
        
                $this->mo_generator->generateFile($translations, $this->translations_path . DIRECTORY_SEPARATOR . $path_parts['filename'] . '.mo');    
    
                $msg = __('New translations are generated', 'propeller-ecommerce');
            }
            catch (Exception $ex) {
                $success = false;
                $msg = $ex->getMessage();
                propel_log($ex->getMessage());
            }
        }
        else {
            $success = false;
            $msg = __('Not enought rights to generate translations', 'propeller-ecommerce');
        }
        
        die(json_encode([
            'success' => $success, 
            'message' => $msg 
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