<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Represents an i18n pack.
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
 * Represents an i18n pack.
 *
 * @package I18n
 * @author Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @copyright See COPYRIGHT.md
 * @license See LICENSE.md
 * @version $Id$
 * @since Class available since v0.1
 */
interface I18n {
    const CONF_DISPLAY_NAME = 'displayName';
    const CONF_LOCALE = 'locale';
    const CONF_DESCRIPTION = 'description';
    
    /**
     * Gets an i18n text message.
     *
     * Note: the official type of the argument $replacements is an array.
     * Implementations of this interface, however, can take advantage of PHP's
     * variable arguments support to take in any number of single replacement.
     *
     * @param
     *            string key of the text message to get
     * @param
     *            Array() replacements for place-holders within the text message
     * @return string
     */
    public function getMessage($key, $replacements = NULL);
    
    /**
     * Gets the locale associated with the i18n pack.
     *
     * @return string
     */
    public function getLocale();
    
    /**
     * Gets description of the i18n pack.
     *
     * @return string
     */
    public function getDescription();
    
    /**
     * Gets display name of the i18n pack.
     *
     * @return string
     */
    public function getDisplayName();
    
    /**
     * Gets name of the i18n pack.
     *
     * @return string
     */
    public function getName();
    
    /**
     * Initializes the i18n pack.
     *
     * @param string $name
     *            name of the i18n pack
     * @param Array $config            
     */
    public function init($name, $config = Array());
}
