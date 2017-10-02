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
 * SpoolCommandController.
 *
 * @link https://github.com/symfony/swiftmailer-bundle/blob/master/Command/SendEmailCommand.php
 */
class SpoolCommandController extends CommandController
{
    /**
     * Sends emails from the spool.
     *
     * @param int  $messageLimit   The maximum number of messages to send.
     * @param int  $timeLimit      The time limit for sending messages (in seconds).
     * @param int  $recoverTimeout The timeout for recovering messages that have taken too long to send (in seconds).
     * @param bool $daemon         True for running as daemon (EXPERIMENTAL, CLI ONLY!).
     *
     * @throws \Exception
     */
    public function sendCommand($messageLimit = null, $timeLimit = null, $recoverTimeout = null, $daemon = false)
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
            do {
                if ($spool instanceof \Swift_FileSpool) {
                    if (null !== $recoverTimeout) {
                        $spool->recover($recoverTimeout);
                    } else {
                        $spool->recover();
                    }
                }

                try {
                    $sent = $spool->flushQueue($transport->getRealTransport());
                } catch (\Exception $exception) {
                    $message = $exception->getMessage();
                    GeneralUtility::sysLog($message, 'mail_spool', GeneralUtility::SYSLOG_SEVERITY_ERROR);
                    $GLOBALS['BE_USER']->writelog(4, 0, 2, 0, '[mail_spool]: '.$message, []);
                    $this->getLogger()->error($message);
                    throw $exception;
                }

                $this->outputLine(sprintf('<comment>%d</comment> emails sent', $sent));
            } while ($daemon && $this->idle());
        } else {
            $this->outputLine('Transport is not a <info>Swift_Transport_SpoolTransport</info>.');
        }
    }

    /**
     * Be idle for a while.
     *
     * @return bool true if relaxed ;-)
     */
    protected function idle()
    {
        return !sleep(3);
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

    /**
     * Get class logger.
     *
     * @return \TYPO3\CMS\Core\Log\Logger
     */
    protected function getLogger()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Log\\LogManager')->getLogger(__CLASS__);
    }
}
