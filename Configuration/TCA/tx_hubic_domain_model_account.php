<?php

return [
    'ctrl' => array(
        'title' => 'LLL:EXT:hubic/Resources/Private/Language/locallang_db.xlf:tx_hubic_domain_model_account',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'languageField' => 'sys_language_uid',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden'
        ),
        'iconfile' => 'EXT:hubic/Resources/Public/Icons/icon_tx_hubic_domain_model_account.gif'
    ),
    'interface' => array(
        'showRecordFieldList' => 'name,client_id,client_secret,access_token'
    ),
    'types' => array(
        '0' => array('showitem' => 'hidden,name,client_id,client_secret,access_token'),
        '1' => array('showitem' => 'hidden,name,client_id,client_secret,access_token')
    ),
    'palettes' => array(),
    'columns' => array(
        'name' => array(
            'label' => 'Name',
            'config' => array(
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            )
        ),
        'client_id' => array(
            'label' => 'Client ID',
            'config' => array(
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            )),
        'client_secret' => array(
            'label' => 'Client Secret',
            'config' => array(
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim,required'
            )),
        'access_token' => array(
            'label' => 'Access Token',
            'config' => array(
                'type' => 'input',
                'size' => 40,
                'readOnly' => 1,
                'default' => '',
                'eval' => 'trim'
            )),
    )
];
