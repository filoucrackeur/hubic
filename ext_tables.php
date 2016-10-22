<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Filoucrackeur.' . $_EXTKEY,
    'List',
    'hubiC shared links'
);

if (TYPO3_MODE === 'BE') {
    if (version_compare(TYPO3_version, '7.6.0', '>=')) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'Filoucrackeur.hubic',
            'tools',          // Main area
            'hubic',         // Name of the module
            '',             // Position of the module
            array(          // Allowed controller action combinations
                'Backend\Account' => 'index,show,delete,add,unlink,authenticationRequest,authenticationResponse'
            ),
            array(          // Additional configuration
                'access' => 'user,group',
                'icon' => 'EXT:hubic/ext_icon.png',
                'labels' => 'LLL:EXT:hubic/Resources/Private/Language/locallang.xlf',
            )
        );
    }
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_hubic_domain_model_account');
}


$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_list']='layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_list']='pi_flexform';
       // new!
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $_EXTKEY.'_list',
    'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_list.xml'
);
