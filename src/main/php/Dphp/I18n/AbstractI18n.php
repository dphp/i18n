<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Abstract implementation of {@link I18n}.
 *
 * LICENSE: See LICENSE.md
 *
 * COPYRIGHT: See COPYRIGHT.md
 *
 * @package I18n
 * @author Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @copyright See COPYRIGHT.md
 * @license See LICENSE.md
 * @version $Id$
 * @since File available since v0.1
 */
namespace Dphp\I18n;

/**
 * Abstract implementation of {@link I18n}.
 *
 * @package I18n
 * @author Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @copyright See COPYRIGHT.md
 * @license See LICENSE.md
 * @version $Id$
 * @since Class available since v0.1
 */
abstract class AbstractI18n implements I18n {
    /**
     *
     * @var Array
     */
    private $config = Array ();
    
    /**
     *
     * @var string
     */
    private $name = NULL;
    
    /**
     *
     * @var string
     */
    private $locale = NULL;
    
    /**
     *
     * @var string
     */
    private $displayName = NULL;
    
    /**
     *
     * @var string
     */
    private $description = NULL;
    
    /**
     *
     * @var Array
     */
    private $languageData = NULL;
    
    /**
     * Constructs a new AbstractI18n object.
     */
    public function __construct() {
    }
    
    /**
     * Gets the configuration array.
     *
     * @return Array
     */
    protected function getConfig() {
        return $this->config;
    }
    
    /**
     *
     * @see I18n::getMessage()
     */
    public function getMessage($key, $replacements = NULL) {
        $msg = isset ( $this->languageData [$key] ) ? $this->languageData [$key] : NULL;
        if ($replacements === NULL) {
            return $msg;
        }
        if (! is_array ( $replacements )) {
            $replacements = Array ();
            for($i = 1, $n = func_num_args (); $i < $n; $i ++) {
                $t = func_get_arg ( $i );
                if ($t !== NULL) {
                    $replacements [] = $t;
                }
            }
        }
        return $msg !== NULL ? \Dphp\Commons\MessageFormat::formatString ( $msg, $replacements ) : NULL;
    }
    
    /**
     *
     * @see I18n::getDescription()
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * Sets the i18n pack's description.
     *
     * @param string $description            
     */
    protected function setDescription($description) {
        $this->description = $description;
    }
    
    /**
     *
     * @see I18n::getDisplayName()
     */
    public function getDisplayName() {
        return $this->displayName;
    }
    
    /**
     * Sets the i18n pack's display name.
     *
     * @param string $displayName            
     */
    protected function setDisplayName($displayName) {
        $this->displayName = $displayName;
    }
    
    /**
     *
     * @see I18n::getLocale()
     */
    public function getLocale() {
        return $this->locale;
    }
    
    /**
     * Sets the i18n pack's locale.
     *
     * @param string $locale            
     */
    protected function setLocale($locale) {
        $this->locale = $locale;
    }
    
    /**
     *
     * @see I18n::getName()
     */
    public function getName() {
        return $this->languageName;
    }
    
    /**
     * Sets the i18n pack's name.
     *
     * @param string $name            
     */
    protected function setName($name) {
        $this->languageName = $name;
    }
    
    /**
     *
     * @see I18n::init()
     */
    public function init($langName, $config) {
        $this->setName ( $langName );
        $this->config = $config;
        $this->setDisplayName ( isset ( $config [self::CONF_DISPLAY_NAME] ) ? $config [self::CONF_DISPLAY_NAME] : NULL );
        $this->setDescription ( isset ( $config [self::CONF_DESCRIPTION] ) ? $config [self::CONF_DESCRIPTION] : NULL );
        $this->setLocale ( isset ( $config [self::CONF_LOCALE] ) ? $config [self::CONF_LOCALE] : NULL );
        $this->buildLanguageData ();
    }
    
    /**
     * Loads and builds language data.
     * Called by
     * {@link AbstractI18n::init()} method.
     *
     * @throws I18nException
     */
    protected abstract function buildLanguageData();
    
    /**
     * Sets language data.
     *
     * @param Array $languageData            
     */
    protected function setLanguageData($languageData) {
        if ($languageData === NULL || ! is_array ( $languageData )) {
            $this->languageData = Array ();
        } else {
            $this->languageData = $languageData;
        }
    }
    
    /**
     * Gets language data.
     *
     * @return Array
     */
    protected function getLanguageData() {
        return $this->languageData;
    }
}