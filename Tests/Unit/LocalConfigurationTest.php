<?php

namespace R3H6\MailSpool\Tests\Unit\Mail;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 3 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Unit test for the SpoolFactory.
 */
class LocalConfigurationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @test
     */
    public function extensionConfigurationWillOverrideTransportAndSetRealTransport()
    {
        $_EXTKEY = 'mail_spool';
        $GLOBALS['TYPO3_CONF_VARS']['MAIL'] = array(
            'transport' => 'smtp'
        );
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = serialize(array(
            'spool' => 'file',
            'spool_file_path' => 'uploads/tx_mailspool',
            'transport_real' => '',
        ));

        require GeneralUtility::getFileAbsFileName('EXT:mail_spool/ext_localconf.php');

        $this->assertSame('R3H6\\MailSpool\\Mail\\SpoolTransport', $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport']);
        $this->assertSame('smtp', $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_real']);
    }

    /**
     * @test
     */
    public function realTransportWillOverrideDefaultTransport()
    {
        $_EXTKEY = 'mail_spool';
        $GLOBALS['TYPO3_CONF_VARS']['MAIL'] = array(
            'transport' => 'smtp'
        );
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = serialize(array(
            'spool' => 'file',
            'spool_file_path' => 'uploads/tx_mailspool',
            'transport_real' => 'mail',
        ));

        require GeneralUtility::getFileAbsFileName('EXT:mail_spool/ext_localconf.php');

        $this->assertSame('mail', $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_real']);
    }
}
