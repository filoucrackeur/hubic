<?php
namespace Filoucrackeur\Hubic\Controller\Backend;

use Filoucrackeur\Hubic\Service\Client;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class AuthController extends ActionController {

    public function indexAction() {

        /* @var \Filoucrackeur\Hubic\Service\Client $hubicClient */
        $hubicClient = GeneralUtility::makeInstance(Client::class);
        $url = $hubicClient->authorizationRequest();
//        DebuggerUtility::var_dump($url);
//        die();
        $this->redirectToUri($url);

//        DebuggerUtility::var_dump($_GET);
//        die();

//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, 'https://api.hubic.com/oauth/auth/?client_id=' . $extensionConfiguration['client_id']['value'] . '&redirect_uri=https%3A%2F%2Fapi.hubic.com%2Fsandbox%2F&scope=usage.r,account.r,getAllLinks.r,credentials.r,sponsorCode.r,sponsored.r,links.r&response_type=code&state=RandomString_11SY6QK720');
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        $result = curl_exec($ch);
//        curl_close($ch);
//        echo $result;
//        DebuggerUtility::var_dump($result);

    }
}
