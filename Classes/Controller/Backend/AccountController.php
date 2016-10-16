<?php
namespace Filoucrackeur\Hubic\Controller\Backend;

use Filoucrackeur\Hubic\Service\ClientUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class AccountController extends ActionController {

    public function indexAction() {

        /* @var ClientUtility $client */
        $client = GeneralUtility::makeInstance(ClientUtility::class);
       // $url = $hubicClient->authorizationRequest();
//        DebuggerUtility::var_dump(BackendUtility::getModuleUrl());
//        DebuggerUtility::var_dump($client->getAuthorizationRequestUrl());
//        die();
        //$this->redirectToUri($url);


        $this->view->assignMultiple([
            'client' => $client,
            'authorizationRequestUrl' => $client->getAuthorizationRequestUrl()
        ]);
    }

    public function infoAction() {

    }

    public function configAction() {

    }
}
