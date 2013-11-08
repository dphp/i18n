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

use Dphp\Commons\File;
use Dphp\Commons\Properties;

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
     * Constructs a new Ddth_Mls_FileLanguage object.
     */
    public function __construct() {
        parent::__construct ();
        // $this->LOGGER = Ddth_Commons_Logging_LogFactory::getLog ( __CLASS__ );
    }
    
    /**
     * This function loads language data from all {@link \Dphp\Commons\Properties properties files}
     * within the directory <i><baseDirectory>/<location></i>.
     *
     * @see AbstractI18n::buildLanguageData()
     * @see \Dphp\Commons\Properties
     */
    protected function buildLanguageData() {
        $config = $this->getConfig ();
        $baseDirectory = isset ( $config [self::CONF_BASE_DIRECTORY] ) ? $config [self::CONF_BASE_DIRECTORY] : '';
        $i18nDir = new File ( $baseDirectory );
        $location = isset ( $config [self::CONF_LOCATION] ) ? $config [self::CONF_LOCATION] : '';
        $i18nDir = new File ( $location, $baseDirectory );
        if (! $i18nDir->isDirectory ()) {
            $msg = "[{$i18nDir->getPathname()}] is not a directory!";
            throw new I18nException ( $msg );
        }
        $languageData = new Properties ();
        $dh = @opendir ( $i18nDir->getPathname () );
        if ($dh) {
            $file = @readdir ( $dh );
            while ( $file ) {
                $langFile = new File ( $file, $i18nDir );
                if ($langFile->isFile () && $langFile->isReadable () && preg_match ( '/^.+\.properties$/i', $file )) {
                    try {
                        // $msg = "Load language file [{$langFile->getPathname()}]...";
                        // $this->LOGGER->info ( $msg );
                        $languageData->load ( $langFile->getPathname () );
                    } catch ( Exception $e ) {
                        // $msg = $e->getMessage ();
                        // $this->LOGGER->warn ( $msg, $e );
                    }
                }
                $file = @readdir ( $dh );
            }
            @closedir ( $dh );
        } else {
            $msg = "[{$i18nDir->getPathname()}] is not accessible!";
            throw new I18nException ( $msg );
        }
        $this->setLanguageData ( $languageData->toArray () );
    }
}
