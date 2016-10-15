<?php

namespace R3H6\MailSpool\Tests\Unit\Command;

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

/**
 * Unit test for the SpoolCommand.
 */
class SpoolCommandControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \R3H6\MailSpool\Command\SpoolCommandController
     */
    protected $subject;

    /**
     * @var \TYPO3\CMS\Core\Mail\Mailer
     */
    protected $mailer;

    public function setUp()
    {
        $this->subject = $this->getMock('R3H6\\MailSpool\\Command\\SpoolCommandController', array('outputLine', 'getMailer'), array(), '', false);
        $this->mailer = $this->getMock('TYPO3\\CMS\\Core\\Mail\\Mailer', array('getTransport'), array(), '', false);

        $this->subject
            ->expects($this->any())
            ->method('getMailer')
            ->will($this->returnValue($this->mailer));
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function doNothingIfTransportIsNotOfTypeSpoolTransport()
    {
        $transportMock = $this->getMock('Swift_SmtpTransport', array('getSpool'), array(), '', false);
        $transportMock
            ->expects($this->never())
            ->method('getSpool');

        $this->mailer
            ->expects($this->any())
            ->method('getTransport')
            ->will($this->returnValue($transportMock));

        $this->subject
            ->expects($this->at(2))
            ->method('outputLine')
            ->with($this->equalTo('Transport is not a <info>Swift_Transport_SpoolTransport</info>.'));

        $this->subject->sendCommand();
    }

    /**
     * @test
     */
    public function sendCommandFlushsSpool()
    {
        $realTransportMock = $this->getMock('R3H6\\MailSpool\\Tests\\Unit\\Mail\\Fixtures\\TestTransport', array(), array(), '', false);

        $spoolMock = $this->getMock('Swift_FileSpool', array('setMessageLimit', 'setTimeLimit', 'recover', 'flushQueue'), array(), '', false);
        $spoolMock
            ->expects($this->once())
            ->method('setMessageLimit')
            ->with($this->equalTo(3));
        $spoolMock
            ->expects($this->once())
            ->method('setTimeLimit')
            ->with($this->equalTo(5));
        $spoolMock
            ->expects($this->once())
            ->method('recover')
            ->with($this->equalTo(7));
        $spoolMock
            ->expects($this->once())
            ->method('flushQueue')
            ->with($this->equalTo($realTransportMock))
            ->will($this->returnValue(9));

        $transportMock = $this->getMock('R3H6\\MailSpool\\Mail\\SpoolTransport', array('getSpool', 'getRealTransport'), array(), '', false);
        $transportMock
            ->expects($this->any())
            ->method('getSpool')
            ->will($this->returnValue($spoolMock));
        $transportMock
            ->expects($this->any())
            ->method('getRealTransport')
            ->will($this->returnValue($realTransportMock));

        $this->mailer
            ->expects($this->any())
            ->method('getTransport')
            ->will($this->returnValue($transportMock));

        $this->subject
            ->expects($this->at(2))
            ->method('outputLine')
            ->with($this->equalTo('<comment>9</comment> emails sent'));

        $this->subject->sendCommand(3, 5, 7);
    }
}
