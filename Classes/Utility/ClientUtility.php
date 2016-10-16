<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Filoucrackeur\Hubic\Service;

use Filoucrackeur\Hubic\Service\OAuth2\Client;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Client for hubiC (http://api.hubic.com).
 *
 * @package Filoucrackeur\Hubic
 */
class ClientUtility implements SingletonInterface
{
    const AUTHORIZATION_ENDPOINT = 'https://api.hubic.com/oauth/auth/';

    const TOKEN_ENDPOINT = 'https://api.hubic.com/oauth/token/';

    /**
     * @var \string
     */
    public $client_id;

    /**
     * @var \string
     */
    public $client_secret;

    /**
     * @var \string
     */
    public $access_token;

    /**
     * @var \string
     */
    protected $redirect_uri;

    /**
     * @var string
     */
    protected $scope = 'usage.r,account.r,getAllLinks.r,credentials.r,sponsorCode.r,activate.w,sponsored.r,links.r';

    /**
     * @var string
     */
    protected $responseType = 'code';

    /**
     * @var string
     */
    protected $state;

    /**
     * @var \Filoucrackeur\Hubic\Service\OAuth2\Client
     */
    protected $client;

    /**
     * @var  \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        /** @var \TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility $configurationUtility */
        $configurationUtility = $this->objectManager->get('TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility');
        $extensionConfiguration = $configurationUtility->getCurrentConfiguration('hubic');
        $this->client_id = $extensionConfiguration['client_id']['value'];
        $this->client_secret = $extensionConfiguration['client_secret']['value'];
        $this->redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . BackendUtility::getModuleUrl('tools_HubicHubic');
        $this->access_token = $extensionConfiguration['access_token']['value'];
        $this->state = md5(time());
//         echo $this->redirect_uri;

        $formProtection = \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get();
        $formToken = $formProtection->generateToken('AuthorizationRequest');
        $this->redirect_uri .= '&formToken=' . $formToken;

        $this->client = new Client($this->client_id, $this->client_secret);
        $this->client->setScope($this->scope);
        $this->client->setAccessTokenType(1);

        if ($this->access_token) {
            $this->client->setAccessToken($this->access_token);
        }else {
            if (isset($_GET['formToken'])) {
                $_GET['code'] = str_replace('code=', '', strstr($_GET['formToken'], "code="));
            }
            if (!isset($_GET['code'])) {
                //$this->getAuthorizationRequestUrl();
            } else {
                $params = array('code' => $_GET['code'], 'redirect_uri' => $this->redirect_uri);
                $response = $this->client->getAccessToken(self::TOKEN_ENDPOINT, 'authorization_code', $params);

                if ($response['result']['access_token']) {
                    $configurationUtility->writeConfiguration([
                        'client_id' => $this->client_id,
                        'client_secret' => $this->client_secret,
                        'access_token' => $response['result']['access_token']
                    ], 'hubic');
                }

                $this->client->setAccessToken($response['result']['access_token']);
            }
        }
    }


    public function getAccount()
    {
        $response = $this->client->fetch('https://api.hubic.com/1.0/account');
        return $response;
    }

    public function hasValidToken()
    {

    }

    public function getAuthorizationRequestUrl()
    {
        $authUrl = $this->client->getAuthenticationUrl(self::AUTHORIZATION_ENDPOINT, $this->redirect_uri);
        return $authUrl;
    }

    /**
     * Get hubiC account Quota
     * @return array
     */
    public function getAccountQuota()
    {
        $response = $this->client->fetch('https://api.hubic.com/1.0/account/usage');
        return $response;
    }

    /**
     * Get hubiC agreements
     * @return array
     */
    public function getAgreement() {
        $response = $this->client->fetch('https://api.hubic.com/1.0/agreement');
        return $response;
    }


    /*
    $client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);
    if (!isset($_GET['code']))
    {
    $auth_url = $client->getAuthenticationUrl(AUTHORIZATION_ENDPOINT, REDIRECT_URI);
    header('Location: ' . $auth_url);
    die('Redirect');
    }
    else
        {
            $base64 = base64_encode(CLIENT_ID.':'.CLIENT_SECRET);
            //var_dump($_GET['code']);
            //var_dump($base64);
            //die();
            $params = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
            $response = $client->getAccessToken(TOKEN_ENDPOINT, 'authorization_code', $params);
    //    echo "<pre>";
    //                     var_dump($response);
    //    echo "</pre>";
    //die();
            //parse_str($response['result'], $info);
            $client->setAccessTokenType(1);
            $client->setAccessToken($response['result']['access_token']);
            $response = $client->fetch('https://api.hubic.com/1.0/account');
            echo "<pre>";
            var_dump($response);
            echo "</pre>";
        }
    */
}
