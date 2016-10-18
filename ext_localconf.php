<?php
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Filoucrackeur.hubic',
    'hubic',
    array(
        'Backend/Account' => 'index,show,authenticationRequest,authenticationResponse'
    ),
    array(
        'Backend/Account' => 'index,show,authenticationRequest,authenticationResponse'
    )
);
