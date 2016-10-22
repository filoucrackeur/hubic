<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Filoucrackeur.' . $_EXTKEY,
    'List',
    array('Hubic' => 'list'),
    array('Hubic' => 'list')
);
