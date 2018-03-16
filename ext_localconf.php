<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$boot = function ($_EXTKEY) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Filoucrackeur.' . $_EXTKEY,
        'List',
        ['Hubic' => 'list']
    );
};

$boot($_EXTKEY);
unset($boot);
