<?php

defined('TYPO3_MODE') or die();

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_hubic_domain_model_account');

    if (version_compare(TYPO3_version, '7.6.0', '>=')) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'Filoucrackeur.hubic',
            'tools',
            'hubic',
            '',
            [
                'Backend\Account' => 'index,show,delete,add,unlink,accessToken,refreshToken,callback',
            ],
            [
                'access' => 'user,group',
                'icon' => 'EXT:hubic/ext_icon.png',
                'labels' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:tx_hubic_domain_model_account',
            ]
        );
    }
}
