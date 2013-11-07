<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * {@link \Dphp\Commons\Properties}-based I18n pack.
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
 * {@link \Dphp\Commons\Properties}-based I18n pack.
 *
 * This i18n pack loads i18n configuration data from .properties file on disk. It needs 2
 * {@link I18nFactory::getInstance() configuration settings}:
 * <ul>
 * <li><b>i18n.baseDirectory</b>: base directory where i18n packs are located</li>
 * <li><b>location</b>: location (relative to i18n.baseDirectory) of this i18n pack</li>
 * </ul>
 * I18n data is loaded from all {@link \Dphp\Commons\Properties .properties files} within
 * directory <i><i18n.baseDirectory>/<location></i>.
 *
 * @package I18n
 * @author Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @copyright See COPYRIGHT.md
 * @license See LICENSE.md
 * @version $Id$
 * @since Class available since v0.1
 */
class PropertiesI18n extends AbstractI18n {
    const CONF_LOCATION = "location";
    const CONF_BASE_DIRECTORY = "i18n.baseDirectory";
    
    /**
     *
     * @var Ddth_Commons_Logging_ILog
     */
    private $LOGGER;
    
    /**
     * Constructs a new Ddth_Mls_FileLanguage object.
     */
    public function __construct() {
        parent::__construct ();
        $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog ( __CLASS__ );
    }
    
    /**
     * This function loads language data from all {@link Ddth_Commons_Properties .
     *
     *
     *
     *
     *
     *
     * properties files}
     * within the directory <i><baseDirectory>/<location></i>.
     *
     * @see Ddth_Mls_AbstractLanguage::buildLanguageData()
     * @see Ddth_Commons_Properties
     */
    protected function buildLanguageData() {
        $config = $this->getConfig ();
        $baseDirectory = isset ( $config [self::CONF_BASE_DIRECTORY] ) ? $config [self::CONF_BASE_DIRECTORY] : '';
        $langDir = new Ddth_Commons_File ( $baseDirectory );
        $location = isset ( $config [self::CONF_LOCATION] ) ? $config [self::CONF_LOCATION] : '';
        $langDir = new Ddth_Commons_File ( $location, $baseDirectory );
        if (! $langDir->isDirectory ()) {
            $msg = "[{$langDir->getPathname()}] is not a directory!";
            throw new Ddth_Mls_MlsException ( $msg );
        }
        $languageData = new Ddth_Commons_Properties ();
        $dh = @opendir ( $langDir->getPathname () );
        if ($dh) {
            $file = @readdir ( $dh );
            while ( $file ) {
                $langFile = new Ddth_Commons_File ( $file, $langDir );
                if ($langFile->isFile () && $langFile->isReadable () && preg_match ( '/^.+\.properties$/i', $file )) {
                    try {
                        $msg = "Load language file [{$langFile->getPathname()}]...";
                        $this->LOGGER->info ( $msg );
                        $languageData->load ( $langFile->getPathname () );
                    } catch ( Exception $e ) {
                        $msg = $e->getMessage ();
                        $this->LOGGER->warn ( $msg, $e );
                    }
                }
                $file = @readdir ( $dh );
            }
            @closedir ( $dh );
        } else {
            $msg = "[{$langDir->getPathname()}] is not accessible!";
            throw new Ddth_Mls_MlsException ( $msg );
        }
        $this->setLanguageData ( $languageData->toArray () );
    }
}
