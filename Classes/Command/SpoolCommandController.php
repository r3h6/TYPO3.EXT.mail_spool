<?php

namespace R3H6\MailSpool\Command;

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

use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * SpoolCommandController
 * @link https://github.com/symfony/swiftmailer-bundle/blob/master/Command/SendEmailCommand.php
 */
class SpoolCommandController extends CommandController
{
    /**
     * Sends emails from the spool.
     *
     * @param  int $messageLimit   The maximum number of messages to send.
     * @param  int $timeLimit      The time limit for sending messages (in seconds).
     * @param  int $recoverTimeout The timeout for recovering messages that have taken too long to send (in seconds).
     * @return void
     */
    public function sendCommand($messageLimit = null, $timeLimit = null, $recoverTimeout = null)
    {
        $this->outputLine(sprintf('<info>[%s]</info> Processing mailer... ', date('Y-m-d H:i:s')));

        $mailer = $this->getMailer();

        $transport = $mailer->getTransport();
        if ($transport instanceof \R3H6\MailSpool\Mail\SpoolTransport) {
            $spool = $transport->getSpool();
            if ($spool instanceof \Swift_ConfigurableSpool) {
                $spool->setMessageLimit($messageLimit);
                $spool->setTimeLimit($timeLimit);
            }
            if ($spool instanceof \Swift_FileSpool) {
                if (null !== $recoverTimeout) {
                    $spool->recover($recoverTimeout);
                } else {
                    $spool->recover();
                }
            }
            $sent = $spool->flushQueue($transport->getRealTransport());
            $this->outputLine(sprintf('<comment>%d</comment> emails sent', $sent));
        } else {
            $this->outputLine('Transport is not a <info>Swift_Transport_SpoolTransport</info>.');
        }
    }

    /**
     * Returns the TYPO3 mailer.
     *
     * @return \TYPO3\CMS\Core\Mail\Mailer
     */
    protected function getMailer()
    {
        return GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\Mailer');
    }
}
