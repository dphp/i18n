<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Factory to create i18n packs (objects of type {@link I18n}).
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
 * Factory to create i18n packs (objects of type {@link I18n}).
 *
 * It can be used as the base class to develop custom i18n factory class.
 *
 * This factory provides a {@link getInstance() static function} to get instance of
 * {@link I18nFactory}. The static function takes an array as parameter.
 * See {@link getInstance()} for details of the configuration array.
 *
 * Usage:
 * <code>
 * $i18nFactory = \Dphp\I18n\I18nFactory::getInstance();
 * $i18n = $i18nFactory->getI18n($name);
 * echo $i18n->getMessage('home');
 * echo $i18n->getMessage('login');
 * //...
 * </code>
 *
 * @package I18n
 * @author Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @copyright See COPYRIGHT.md
 * @license See LICENSE.md
 * @version $Id$
 * @since Class available since v0.1
 */
class I18nFactory {
    private static $cacheInstances = Array ();
    const DEFAULT_FACTORY_CLASS = '\\Dphp\\I18n\\I18nFactory';
    const CONF_PREFIX = 'i18n.';
    const CONF_I18N_LIST = 'i18n_list';
    const CONF_FACTORY_CLASS = 'factory.class';
    const CONF_I18N_CLASS = 'i18n.class';
    
    /**
     *
     * @var Array
     */
    private $config = Array ();
    
    /**
     * List of declared i18n names (index array).
     *
     * @var Array
     */
    private $i18nNames = NULL;
    
    /**
     * Registered i18n packs (associative array {i18nName => i18nObject}).
     *
     * @var Array
     */
    private $i18nPacks = NULL;
    
    /**
     * Static function to get instances of {@link I18nFactory}.
     *
     * This function accepts an associative array as parameter. If the argument is NULL,
     * the global variable $DPHP_I18N_CONFIG is used instead (if there is no global variable
     * $DPHP_I18N_CONFIG, the function falls back to use the global variable $DPHP_I18N_CONF).
     *
     * Detailed specs of the configuration array:
     * <code>
     * Array(
     * # Class name of the concrete factory.
     * # Default value is \Dphp\I18n\I18nFactory.
     * 'factory.class' => '\\Dphp\\I18n\\I18nFactory',
     *
     * # Names of registered i18n packs, separated by (,) or (;) or spaces.
     * # I18n pack name should contain only lower-cased letters (a-z), digits (0-9)
     * # and underscores (_) only!
     * 'i18n_list' => 'default, en, vn',
     *
     * # Class name of the concrete i18n pack, it *must* implement interface
     * # \Dphp\I18n\I18n.
     * 'i18n.class' => '\\Dphp\\I18n\\I18n',
     *
     * # Configuration settings for each i18n pack. Each configuration
     * # setting follows the format:
     * 'i18n.<name>.<key>' => <value>,
     * # Note: <name> is the i18n pack name
     * # Note: all those configuration settings will be passed to the i18n
     * # pack I18n::init() function. Before being passed to
     * # the function, the "i18n.<name>." will be removed from the key.
     * # Which means, the passed array will contain elements such as {'<key>' => <value>} (not {'i18n.<name>.<key>' => <value>}).
     *
     * # Three special i18n settings that would be widely used:
     * # - Display name of the i18n pack:
     * 'i18n.en.displayName' => 'English',
     * # - Locale associated with the i18n pack:
     * 'i18n.en.locale' => 'en_GB',
     * # - Description of the i18n pack:
     * 'i18n.en.description' => 'English (GB) i18n pack'
     * )
     * </code>
     *
     * @param Array $config
     *            the configuration array
     * @return I18n
     * @throws {@link I18nException}
     */
    public static function getInstance($config = NULL) {
        if ($config === NULL) {
            global $DPHP_I18N_CONFIG;
            $config = isset ( $DPHP_I18N_CONFIG ) ? $DPHP_I18N_CONFIG : NULL;
        }
        if ($config === NULL) {
            global $DPHP_I18N_CONF;
            $config = isset ( $DPHP_I18N_CONF ) ? $DPHP_I18N_CONF : NULL;
        }
        if ($config === NULL) {
            global $DPHP_I18N_CFG;
            $config = isset ( $DPHP_I18N_CFG ) ? $DPHP_I18N_CFG : NULL;
        }
        if ($config === NULL) {
            return NULL;
        }
        $hash = md5 ( serialize ( $config ) );
        if (! isset ( self::$cacheInstances [$hash] )) {
            $factoryClass = isset ( $config [self::CONF_FACTORY_CLASS] ) ? $config [self::CONF_FACTORY_CLASS] : NULL;
            if ($factoryClass === NULL || trim ( $factoryClass ) === "") {
                $factoryClass = self::DEFAULT_FACTORY_CLASS;
            } else {
                $factoryClass = trim ( $factoryClass );
            }
            try {
                $instance = new $factoryClass ();
                if ($instance instanceof I18nFactory) {
                    $instance->init ( $config );
                } else {
                    $msg = "[$factoryClass] does not implement \\Dphp\\I18n\\I18nFactory";
                    throw new I18nException ( $msg );
                }
            } catch ( I18nException $me ) {
                throw $me;
            } catch ( Exception $e ) {
                $msg = $e->getMessage ();
                throw new I18nException ( $msg );
            }
            self::$cacheInstances [$hash] = $instance;
        }
        return self::$cacheInstances [$hash];
    }
    
    /**
     * Constructs a new I18nFactory object.
     */
    public function __construct() {
    }
    
    /**
     * Initializes the factory.
     */
    public function init($config) {
        $this->config = $config;
        $this->registerI18nPacks ();
    }
    
    /**
     * Registers declared i18n packs.
     */
    protected function registerI18nPacks() {
        $this->i18nPacks = Array ();
        $i18nNames = $this->getI18nNames ();
        foreach ( $i18nNames as $name ) {
            $i18n = $this->createI18nPack ( $name );
            if ($i18n !== NULL) {
                $this->i18nPacks [$name] = $i18n;
            }
        }
    }
    
    /**
     * Creates an i18n pack object.
     *
     * @param string $name
     *            name of the i18n pack to create
     * @return I18n
     */
    protected function createI18nPack($name) {
        $clazz = $this->getI18nClassName ();
        if ($clazz === NULL) {
            // $msg = 'No i18n class specified!';
            // $this->LOGGER->error ( $msg );
            return NULL;
        }
        // if ($this->LOGGER->isDebugEnabled ()) {
        // $msg = "Language class [$languageClass].";
        // $this->LOGGER->debug ( $msg );
        // $msg = "Loading language pack [$langName]...";
        // $this->LOGGER->debug ( $msg );
        // }
        $i18nPack = new $clazz ();
        if (! ($i18nPack instanceof I18n)) {
            $msg = "[$clazz] is not an instance of \Dphp\I18n\I18n!";
            throw new I18nException ( $msg );
        }
        $i18nPack->init ( $name, $this->getI18nConfig ( $name ) );
        return $i18nPack;
    }
    
    /**
     * Gets name of the i18n pack class.
     *
     * This function uses the configuration name {@link CONF_I18N_CLASS} to locate
     * the name of the i18n pack class. Sub-class may override this function to
     * provide its own behavior.
     *
     * @return string
     */
    protected function getI18nClassName() {
        return isset ( $this->config [self::CONF_I18N_CLASS] ) ? $this->config [self::CONF_I18N_CLASS] : NULL;
    }
    
    /**
     * Builds/Extracts the i18n settings from the factory settings.
     * See {@link getInstance()}
     * for more information.
     *
     * @param string $name            
     * @return Array
     */
    protected function getI18nConfig($name) {
        $i18nNames = $this->getI18nNames ();
        $i18nConfig = Array ();
        $prefix = self::CONF_PREFIX . $name . '.';
        $len = strlen ( $prefix );
        foreach ( $this->config as $key => $value ) {
            if ($prefix === substr ( $key, 0, $len )) {
                $key = substr ( $key, $len );
                if ($key !== '') {
                    $i18nConfig [$key] = $value;
                }
            } else {
                $i18nConfig [$key] = $value;
            }
        }
        return $i18nConfig;
    }
    
    /**
     * Gets an I18n pack by name.
     */
    public function getI18n($name) {
        if ($this->i18nPacks === NULL) {
            $this->registerI18nPacks ();
        }
        if (isset ( $this->i18nPacks [$name] )) {
            return $this->i18nPacks [$name];
        } else {
            // $msg = "I18n pack [$name] does not exist!";
            // $this->LOGGER->warn ( $msg );
            return NULL;
        }
    }
    
    /**
     * Gets all declared i18n pack names as a list.
     */
    public function getI18nNames() {
        if ($this->i18nNames === NULL) {
            $this->i18nNames = Array ();
            $i18nPacks = isset ( $this->config [self::CONF_I18N_LIST] ) ? $this->config [self::CONF_I18N_LIST] : '';
            $i18nPacks = trim ( preg_replace ( '/[\s,;]+/', ' ', $i18nPacks ) );
            $tokens = preg_split ( '/[\s,;]+/', trim ( $i18nPacks ) );
            if (count ( $tokens ) === 0) {
                // $msg = 'No i18n pack defined!';
                // $this->LOGGER->error ( $msg );
            } else {
                foreach ( $tokens as $name ) {
                    if ($name === "") {
                        continue;
                    }
                    $this->i18nNames [] = $name;
                }
            }
        }
        return $this->i18nNames;
    }
    
    /**
     * Gets the configuration array.
     *
     * @return Array
     */
    protected function getConfig() {
        return $this->config;
    }
}
