<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_hubic_domain_model_account');

return [
    'ctrl' => [
        'title' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:tx_hubic_domain_model_account',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'languageField' => 'sys_language_uid',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'iconfile' => 'EXT:hubic/Resources/Public/Icons/icon_tx_hubic_domain_model_account.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'name,client_id,client_secret,access_token'
    ],
    'types' => [
        '0' => ['showitem' => 'hidden,name,client_id,client_secret,access_token'],
        '1' => ['showitem' => 'hidden,name,client_id,client_secret,access_token']
    ],
    'palettes' => [],
    'columns' => [
        'name' => [
            'label' => 'Name',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            ]
        ],
        'client_id' => [
            'label' => 'Client ID',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            ]],
        'client_secret' => [
            'label' => 'Client Secret',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            ]],
        'access_token' => [
            'label' => 'Access Token',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'readOnly' => 1,
                'default' => '',
                'eval' => 'trim'
            ]],
    ]
];
