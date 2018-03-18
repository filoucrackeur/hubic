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

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        'tx-hubic-icon-toolbar',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:hubic/Resources/Public/Icons/hubic-icon-toolbar.svg']
    );
};

$boot($_EXTKEY);
unset($boot);
