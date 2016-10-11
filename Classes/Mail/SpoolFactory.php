<?php

namespace R3H6\MailSpool\Mail;

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
 * SpoolFactory
 */
class SpoolFactory implements \TYPO3\CMS\Core\SingletonInterface
{
    const SPOOL_MEMORY = 'memory';
    const SPOOL_FILE = 'file';

    /**
     * [$memorySpool description]
     * @var \Swift_MemorySpool
     */
    protected $memorySpool = null;

    /**
     * Get a new spool instance from configuration.
     *
     * @param  array  $configuration Spool configuration
     * @return Swift_Spool
     * @throws MailSpoolException
     * @throws Swift_IoException
     */
    public function get(array $configuration)
    {
        switch ($configuration['spool']) {
            case self::SPOOL_FILE:
                $path = GeneralUtility::getFileAbsFileName($configuration['spool_file_path']);
                $spool = new \Swift_FileSpool($path);
                break;
            case self::SPOOL_MEMORY:
                if ($this->memorySpool === null) {
                    $this->memorySpool = new \Swift_MemorySpool();
                }
                $spool = $this->memorySpool;
                break;
            default:
                $spool = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($configuration['spool'], $configuration);
                if (!($spool instanceof \Swift_Spool)) {
                    throw new \RuntimeException($configuration['spool'] . ' is not an implementation of \\Swift_Spool,
                            but must implement that interface to be used as a mail spool.', 1466799482);
                }
                break;
        }
        $this->spools[] = $spool;
        return $spool;
    }

    protected function flushMemoryQueue()
    {
        if ($this->memorySpool !== null) {
            $this->memorySpool->flushQueue();
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->flushMemoryQueue();
    }
}