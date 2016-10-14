<?php
if (TYPO3_MODE === 'BE') {
    if (version_compare(TYPO3_version, '7.6.0', '>=')) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'Filoucrackeur.hubic',
            'web',          // Main area
            'hubic',         // Name of the module
            '',             // Position of the module
            array(          // Allowed controller action combinations
                'Backend\Auth' => 'index'
            ),
            array(          // Additional configuration
                'access' => 'user,group',
                'icon' => 'EXT:hubic/ext_icon.png',
                'labels' => 'LLL:EXT:hubic/Resources/Private/Language/locallang.xlf',
            )
        );
    }
}
