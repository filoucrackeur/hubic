<?php
defined('TYPO3_MODE') or die();

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
        'rootLevel' => 1,
        'iconfile' => 'EXT:hubic/Resources/Public/Icons/icon_tx_hubic_domain_model_account.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'name,client_id,client_secret,access_token,hidden'
    ],
    'types' => [
        '0' => ['showitem' => 'hidden,name,client_id,client_secret,scope,access_token,refresh_token,expiration_date'],
        '1' => ['showitem' => 'hidden,name,client_id,client_secret,scope,access_token,refresh_token,expiration_date']
    ],
    'palettes' => [],
    'columns' => [
        'hidden' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'name' => [
            'label' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:name',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            ]
        ],
        'client_id' => [
            'label' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:client_id',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            ]
        ],
        'client_secret' => [
            'label' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:client_secret',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            ]
        ],
        'scope' => [
            'label' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:scope',
            'config' => [
                'type' => 'input',
                'size' => 100,
                'default' => 'usage.r,account.r,getAllLinks.r,credentials.r,sponsorCode.r,activate.w,sponsored.r,links.drw',
                'eval' => 'trim,required'
            ]
        ],
        'access_token' => [
            'label' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:access_token',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'readOnly' => 1,
                'default' => '',
                'eval' => 'trim'
            ]
        ],
        'refresh_token' => [
            'label' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:refresh_token',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'readOnly' => 1,
                'default' => '',
                'eval' => 'trim'
            ]
        ],
        'expiration_date' => [
            'label' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:date_expiration',
            'config' => [
                'type' => 'input',
                'readOnly' => 1,
                'eval' => 'datetime'
            ]
        ],
    ]
];
