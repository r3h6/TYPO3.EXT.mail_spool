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
 * Unit test for the SpoolTransport.
 */
class SpoolTransportTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \R3H6\MailSpool\Mail\SpoolTransport
     */
    protected $subject;

    public function setUp()
    {
        // $GLOBALS['TYPO3_DB'] = $this->getMock('TYPO3\\CMS\\Core\\Database\\DatabaseConnection', get_class_methods('TYPO3\\CMS\\Core\\Database\\DatabaseConnection'), array(), '', false);

        $classInfoCacheMock = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\Container\\ClassInfoCache', array(), array(), '', false);
        GeneralUtility::addInstance('TYPO3\\CMS\\Extbase\\Object\\Container\\ClassInfoCache', $classInfoCacheMock);

        $configuration = array(
            'transport' => 'R3H6\\MailSpool\\Mail\\SpoolTransport',
            'spool' => 'R3H6\\MailSpool\\Tests\\Unit\\Mail\\Fixtures\\TestSpool',
            'transport_real' => 'R3H6\\MailSpool\\Tests\\Unit\\Mail\\Fixtures\\TestTransport',
            'do_not_spool_syslog_messages' => '1',
        );
        $this->subject =  $this->getMock('R3H6\\MailSpool\\Mail\\SpoolTransport', array('getMailTransport'), array($configuration), '', true);
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getRealTransportReturnsTransport()
    {
        $this->assertInstanceOf('R3H6\\MailSpool\\Tests\\Unit\\Mail\\Fixtures\\TestTransport', $this->subject->getRealTransport());
        $this->assertSame('R3H6\\MailSpool\\Mail\\SpoolTransport', $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport']);
    }

    /**
     * @test
     */
    public function sendWillSendMessageWithMailTransport()
    {
        $messageFixture = new \Swift_Message('Warning - error in TYPO3 installation');

        $transportMock = $this->getMock('MailTransport', array('send'), array(), '', false);
        $transportMock
            ->expects($this->once())
            ->method('send')
            ->with($this->equalTo($messageFixture))
            ->will($this->returnValue(1));

        $this->subject
            ->expects($this->once())
            ->method('getMailTransport')
            ->will($this->returnValue($transportMock));


        $this->subject->send($messageFixture);
    }
}
