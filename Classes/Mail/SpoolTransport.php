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
use R3H6\MailSpool\Mail\SpoolFactory;

/**
 * SpoolTransport
 *
 * Wrapper class for using configured spool for transport.
 */
class SpoolTransport extends \Swift_SpoolTransport
{

    /**
     * Mail configuration
     *
     * @var array
     */
    protected $configuration;

    /**
     * Constructor
     *
     * @param array $configuration System mail configuration.
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
        $spool = GeneralUtility::makeInstance(SpoolFactory::class)->get($configuration);
        parent::__construct($spool);
    }

     /**
     * Get real transport for sending messages.
     *
     * @return Swift_Transport
     */
    public function getRealTransport()
    {
        try {
            $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport'] = $this->configuration['transport_real'];

            $mailer = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\Mailer::class);

            $transport = $mailer->getTransport();

            return $transport;
        } catch (\Exception $exception) {
            throw new \Exception('Could not create real transport ' . $exception->getMessage(), 1476212381);
        } finally {
            $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport'] = $this->configuration['transport'];
        }
    }
}
