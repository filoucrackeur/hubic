<?php

defined('TYPO3_MODE') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Filoucrackeur.hubic',
    'List',
    'hubiC shared links'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['hubic_list'] = 'layout,select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['hubic_list'] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'hubic_list',
    'FILE:EXT:hubic/Configuration/FlexForms/flexform_list.xml'
);
