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
        'iconfile' => 'EXT:hubic/Resources/Public/Icons/icon_tx_hubic_domain_model_account.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'name,client_id,client_secret,access_token'
    ],
    'types' => [
        '0' => ['showitem' => 'hidden,name,client_id,client_secret,scope,access_token,refresh_token'],
        '1' => ['showitem' => 'hidden,name,client_id,client_secret,scope,access_token,refresh_token']
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
            ]
        ],
        'client_secret' => [
            'label' => 'Client Secret',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            ]
        ],
        'scope' => [
            'label' => 'Scope',
            'config' => [
                'type' => 'input',
                'size' => 100,
                'default' => 'usage.r,account.r,getAllLinks.r,credentials.r,sponsorCode.r,activate.w,sponsored.r,links.drw',
                'eval' => 'trim,required'
            ]
        ],
        'access_token' => [
            'label' => 'Access Token',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'readOnly' => 1,
                'default' => '',
                'eval' => 'trim'
            ]
        ],
        'refresh_token' => [
            'label' => 'Refresh Token',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'readOnly' => 1,
                'default' => '',
                'eval' => 'trim'
            ]
        ],
    ]
];
