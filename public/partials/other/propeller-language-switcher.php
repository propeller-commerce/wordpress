<div class="propel-lang-switch" data-no-translation>
    <button class="d-flex align-items-center btn-language" id="propel-language-switch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="language-icon lang-<?= strtolower(PROPELLER_LANG); ?>"></span>    
        <span class="language lang-<?= strtolower(PROPELLER_LANG); ?>"><?= strtoupper(PROPELLER_LANG); ?></span>
    </button>
    
    <div class="dropdown-menu dropdown-menu-right lang-options" aria-labelledby="propel-language-switch">
        <?php foreach($this->languages as $lang) { 
            if (strtolower(PROPELLER_LANG) != $lang['short_language_name']) { ?>                               
                <a class="dropdown-item lang" href="<?= $lang['current_page_url']; ?>">
                    <span class="language-icon lang-<?= strtolower($lang['short_language_name']); ?>"></span>
                    <span class="language lang-<?= strtolower($lang['short_language_name']); ?>"><?= strtoupper($lang['short_language_name']); ?></span>
                </a>
            
        <?php } } ?>
    </div>
</div>