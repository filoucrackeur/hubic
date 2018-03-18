<?php

defined('TYPO3_MODE') or die();

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_hubic_domain_model_account');

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Filoucrackeur.hubic',
        'tools',
        'hubic',
        '',
        [
            'Backend\Account' => 'index,show,delete,add,unlink,unlinkUri,accessToken,refreshToken,callback',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:hubic/ext_icon.svg',
            'labels' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:hubic',
        ]
    );

    $GLOBALS['TYPO3_CONF_VARS']['BE']['toolbarItems'][1505997677] = \Filoucrackeur\Hubic\Backend\ToolBarItems\NotificationsToolbarItem::class;

}
