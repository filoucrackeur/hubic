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

use Filoucrackeur\Hubic\Domain\Model\Account;
use Filoucrackeur\Hubic\Service\OAuth2\Client;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
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
     * @var \Filoucrackeur\Hubic\Service\OAuth2\Client
     */
    protected $OAuth2client;

    /**
     * @var  \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var \Filoucrackeur\Hubic\Domain\Model\Account
     */
    protected $account;

    public function __construct(Account $account)
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);

        $this->OAuth2client = new Client($account->getClientId(), $account->getClientSecret());
        $this->OAuth2client->setScope('usage.r,account.r,getAllLinks.r,credentials.r,sponsorCode.r,activate.w,sponsored.r,links.r');
        $this->OAuth2client->setAccessTokenType(1);
//        $this->OAuth2client->setResponseType('code');
//        $this->OAuth2client->setState(md5(time()));
        if ($account->getAccessToken()) {
            $this->OAuth2client->setAccessToken($account->getAccessToken());
        } else {
            if (isset($_GET['formToken'])) {
                $code = str_replace('code=', '', strstr($_GET['formToken'], "code="));
            }
            if (isset($code)) {
                $params = array('code' => $code, 'redirect_uri' => $this->getRedirectUri($account));
                $response = $this->OAuth2client->getAccessToken(self::TOKEN_ENDPOINT, 'authorization_code', $params);

                $account->setAccessToken($response['result']['access_token']);
                $this->persistenceManager->update($account);
                $this->persistenceManager->persistAll();
//                $this->OAuth2client->setAccessToken($account->getAccessToken());
            }
        }
    }


    public function getAccount()
    {
        $response = $this->OAuth2client->fetch('https://api.hubic.com/1.0/account');
        return $response;
    }

    public function getRedirectUri(Account $account)
    {

        $formProtection = \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get();
        $formToken = $formProtection->generateToken('AuthorizationRequest');

        return 'http://' . $_SERVER['HTTP_HOST'] . BackendUtility::getModuleUrl('tools_HubicHubic', [
            'tx_hubic_tools_hubichubic' => [
                'action' => 'authenticationResponse',
                'controller' => 'Backend\Account',
                'account' => $account->getUid()
            ],
            'formToken' => $formToken
        ]);

    }

    public function getAuthorizationRequestUrl(Account $account)
    {
        $authUrl = $this->OAuth2client->getAuthenticationUrl(self::AUTHORIZATION_ENDPOINT, $this->getRedirectUri($account));
        return $authUrl;
    }

    /**
     * Get hubiC account Quota
     * @return array
     */
    public function getAccountQuota()
    {
        $response = $this->OAuth2client->fetch('https://api.hubic.com/1.0/account/usage');
        return $response;
    }

    /**
     * Get hubiC agreements
     * @return array
     */
    public function getAgreement()
    {
        $response = $this->OAuth2client->fetch('https://api.hubic.com/1.0/agreement');
        return $response;
    }

}
