<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'R3H6\\MailSpool\\Command\\SpoolCommandController';
}

// Merge our configuration with the core mail configuration.
$GLOBALS['TYPO3_CONF_VARS']['MAIL'] += (array) unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);

// If transport_spool is not disabled, we set the transport to our spool transport,
// and set transport_real to transport.
if (!empty($GLOBALS['TYPO3_CONF_VARS']['MAIL']['spool'])) {
    if (empty($GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_real'])) {
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_real'] = $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport'];
    }
    $GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport'] = 'R3H6\\MailSpool\\Mail\\SpoolTransport';
}
