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

/**
 * Unit test for the SpoolTransport.
 */
class SpoolTransportTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var R3H6\MailSpool\Mail\SpoolTransport
     */
    protected $subject;

    public function setUp()
    {
        $GLOBALS['TYPO3_DB'] = $this->getMock('TYPO3\\CMS\Core\Database\DatabaseConnection', get_class_methods('TYPO3\\CMS\\Core\\Database\\DatabaseConnection'), array(), '', false);

        $configuration = array(
            'transport' => 'R3H6\\MailSpool\\Mail\\SpoolTransport',
            'spool' => 'R3H6\\MailSpool\\Tests\\Unit\\Mail\\Fixtures\\TestSpool',
            'transport_real' => 'R3H6\\MailSpool\\Tests\\Unit\\Mail\\Fixtures\\TestTransport',
        );
        $this->subject = new \R3H6\MailSpool\Mail\SpoolTransport($configuration);
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getRealTransport()
    {
        $this->assertInstanceOf('R3H6\\MailSpool\\Tests\\Unit\\Mail\\Fixtures\\TestTransport', $this->subject->getRealTransport());
        $this->assertSame('R3H6\\MailSpool\\Mail\\SpoolTransport', $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport']);
    }
}
