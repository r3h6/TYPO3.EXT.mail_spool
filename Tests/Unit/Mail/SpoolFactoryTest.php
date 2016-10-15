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

use R3H6\MailSpool\Mail\SpoolFactory;

/**
 * Unit test for the SpoolFactory.
 */
class SpoolFactoryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \R3H6\MailSpool\Mail\SpoolFactory
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = $this->getMock('R3H6\\MailSpool\\Mail\\SpoolFactory', array('__destruct'));
    }

    public function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function isSingleton()
    {
        $this->assertInstanceOf('TYPO3\\CMS\\Core\\SingletonInterface', $this->subject);
    }

    /**
     * @test
     */
    public function getMemorySpool()
    {
        $configuration = array(
            'spool' => SpoolFactory::SPOOL_MEMORY,
        );

        /** @var \Swift_MemorySpool $spool */
        $spool = $this->subject->get($configuration);
        $this->assertInstanceOf('Swift_MemorySpool', $spool);
    }

    /**
     * @test
     */
    public function getFileSpool()
    {
        $configuration = array(
            'spool' => SpoolFactory::SPOOL_FILE,
            'spool_file_path' => 'EXT:mail_spool/Tests/Unit',
        );

        /** @var \Swift_FileSpool $spool */
        $spool = $this->subject->get($configuration);
        $this->assertInstanceOf('Swift_FileSpool', $spool);
    }

    /**
     * @test
     */
    public function getCustomSpool()
    {
        $configuration = array(
            'spool' => 'R3H6\\MailSpool\\Tests\\Unit\\Mail\\Fixtures\TestSpool',
            'spool_file_path' => '',
        );

        /** @var \Swift_FileSpool $spool */
        $spool = $this->subject->get($configuration);
        $this->assertInstanceOf('R3H6\\MailSpool\\Tests\\Unit\\Mail\\Fixtures\TestSpool', $spool);
    }
}
