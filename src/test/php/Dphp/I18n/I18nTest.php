<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * PHPUnit (http://www.phpunit.de/) test case(s) for Dphp\I18n\I18n.
 *
 * @author Thanh Ba Nguyen <btnguyen2k@gmail.com>
 * @version $Id$
 * @since File available since v0.1
 */
namespace Dphp\Commons;

use Dphp\I18n\I18nFactory;

$DPHP_I18N_CONFIG = Array (
        'i18n_list' => 'default, vn',
        'i18n.class' => '\\Dphp\\I18n\\PropertiesI18n',
        'i18n.baseDirectory' => 'src/test/php/Dphp/I18n',
        
        'i18n.default.displayName' => 'Default',
        'i18n.default.description' => 'This is the default i18n pack',
        'i18n.default.location' => 'i18n-default',
        
        'i18n.vn.displayName' => 'Vietnamese',
        'i18n.vn.description' => 'This is the Vietnamese i18n pack',
        'i18n.vn.location' => 'i18n-vn' 
);

require_once 'vendor/autoload.php';
class I18nTest extends \PHPUnit_Framework_TestCase {
    /**
     * Tests creation of i18n factory
     */
    public function testObjCreation() {
        $obj1 = I18nFactory::getInstance ();
        $this->assertNotNull ( $obj1, "Can not create i18n factory!" );
        
        $obj2 = I18nFactory::getInstance ();
        $this->assertNotNull ( $obj2, "Can not create i18n factory!" );
        
        $this->assertTrue ( $obj1 === $obj2, "The two objects are expected to be equal!" );
    }
    
    /**
     * Tests creation of i18n packs
     */
    public function testGetI18n() {
        $obj = I18nFactory::getInstance ();
        $this->assertNotNull ( $obj, "Can not create i18n factory!" );
        
        $i18n = $obj->getI18n ( 'default' );
        $this->assertNotNull ( $i18n );
        $this->assertEquals ( 'default', $i18n->getName () );
        $this->assertEquals ( 'Default', $i18n->getDisplayName () );
        
        $i18n = $obj->getI18n ( 'vn' );
        $this->assertNotNull ( $i18n );
        $this->assertEquals ( 'vn', $i18n->getName () );
        $this->assertEquals ( 'Vietnamese', $i18n->getDisplayName () );
    }
}
