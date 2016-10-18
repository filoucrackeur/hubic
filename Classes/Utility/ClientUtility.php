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
use Filoucrackeur\Hubic\Service\OAuth\Client;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\SingletonInterface;

class ClientUtility implements SingletonInterface
{
    const AUTHORIZATION_ENDPOINT = 'https://api.hubic.com/oauth/auth/';

    const TOKEN_ENDPOINT = 'https://api.hubic.com/oauth/token/';

    /**
     * @var \Filoucrackeur\Hubic\Service\OAuth\Client
     */
    protected $OAuth;

    /**
     * @var  \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * @var \Filoucrackeur\Hubic\Domain\Model\Account
     * @inject
     */
    protected $account;

    public function callHubic(Account $account) {
        $this->account = $account;
        $this->OAuth = new Client($this->account->getClientId(), $this->account->getClientSecret());
        $this->OAuth->setScope('usage.r,account.r,getAllLinks.r,credentials.r,sponsorCode.r,activate.w,sponsored.r,links.r');
        $this->OAuth->setAccessTokenType(1);
//        $this->OAuth->setResponseType('code');
//        $this->OAuth->setState(md5(time()));
        if ($this->account->getAccessToken()) {
            $this->OAuth->setAccessToken($this->account->getAccessToken());
        } else {
            if (isset($_GET['formToken'])) {
                $code = str_replace('code=', '', strstr($_GET['formToken'], "code="));
            }
            if (isset($code)) {
                $params = array('code' => $code, 'redirect_uri' => $this->getRedirectUri($this->account));
                $response = $this->OAuth->getAccessToken(self::TOKEN_ENDPOINT, 'authorization_code', $params);

                $this->account->setAccessToken($response['result']['access_token']);
                $this->persistenceManager->update($this->account);
                $this->persistenceManager->persistAll();
//                $this->OAuth->setAccessToken($account->getAccessToken());
            }
        }
    }

    public function getAccount()
    {
        $response = $this->OAuth->fetch('https://api.hubic.com/1.0/account');
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
        $this->callHubic($account);
        $authUrl = $this->OAuth->getAuthenticationUrl(self::AUTHORIZATION_ENDPOINT, $this->getRedirectUri($account));
        return $authUrl;
    }

    /**
     * Get hubiC account Quota
     * @return array
     */
    public function getAccountQuota()
    {
        $response = $this->OAuth->fetch('https://api.hubic.com/1.0/account/usage');
        return $response;
    }

    /**
     * Get hubiC agreements
     * @return array
     */
    public function getAgreement()
    {
        $response = $this->OAuth->fetch('https://api.hubic.com/1.0/agreement');
        return $response;
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

}
