<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * I18n specific exception.
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
 * I18n specific exception.
 *
 * @package I18n
 * @author Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @copyright See COPYRIGHT.md
 * @license See LICENSE.md
 * @version $Id$
 * @since Class available since v0.1
 */
class I18nException extends \Dphp\Commons\Exceptions\AbstractException {
    /**
     * Constructs a new I18nException object.
     *
     * @param
     *            string exception message
     * @param
     *            int user defined exception code
     */
    public function __construct($message = NULL, $code = 0) {
        parent::__construct ( $message, $code );
    }
}