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
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class HubicService implements SingletonInterface
{
    public const AUTHORIZATION_ENDPOINT = 'https://api.hubic.com/oauth/auth/';

    public const TOKEN_ENDPOINT = 'https://api.hubic.com/oauth/token/';

    public const DOMAIN_API = 'https://api.hubic.com/';

    public const VERSION_API = '1.0';

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var \Filoucrackeur\Hubic\Domain\Model\Account
     */
    protected $account;

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @param Account $account
     *
     * @return bool
     * @throws \RuntimeException
     */
    public function accessToken(Account $account)
    {
        $credentials = base64_encode($account->getClientId() . ':' . $account->getClientSecret());
        $additionalOptions = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . $credentials,
            ],
            RequestOptions::FORM_PARAMS => [
                'code' => GeneralUtility::_GET('code'),
                'redirect_uri' => 'http://localhost/',
                'grant_type' => 'authorization_code',
            ],
            RequestOptions::VERSION => '1.1',
        ];

        $response = $this->requestFactory->request(self::TOKEN_ENDPOINT, 'POST', $additionalOptions);

        if (200 === $response->getStatusCode()) {
            $content = json_decode($response->getBody()->getContents());
            $account->setAccessToken($content->access_token);
            $account->setRefreshToken($content->refresh_token);
            $this->persistenceManager->update($account);
            $this->persistenceManager->persistAll();
        }

        return true;
    }

    /**
     * @param Account $account
     */
    public function refreshToken(Account $account)
    {

        $credentials = base64_encode($account->getClientId() . ':' . $account->getClientSecret());
        $additionalOptions = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . $credentials,
            ],
            RequestOptions::FORM_PARAMS => [
                'refresh_token' => $account->getRefreshToken(),
                'grant_type' => 'refresh_token',
            ],
            RequestOptions::VERSION => '1.1',
        ];

        $response = $this->requestFactory->request(self::TOKEN_ENDPOINT, 'POST', $additionalOptions);

        if (200 === $response->getStatusCode()) {
            $content = json_decode($response->getBody()->getContents());
            $account->setAccessToken($content->access_token);
            $this->persistenceManager->update($account);
            $this->persistenceManager->persistAll();
        }

        return true;
    }

    /**
     * @param string $path
     * @param string $method
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function fetch(string $path, $method = 'GET')
    {

        $additionalOptions = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer ' . $this->account->getAccessToken(),
            ],
            RequestOptions::VERSION => '1.1',
        ];

        try {
            $response = $this->requestFactory->request(self::DOMAIN_API . self::VERSION_API . $path, $method, $additionalOptions);

            if (200 === $response->getStatusCode()) {
                return json_decode($response->getBody()->getContents());
            }
        } catch (\Exception $e) {
            if ($this->refreshToken($this->account)) {
                return $this->fetch($path, $method);
            }
        }
    }

    /**
     * @param Account $account
     */
    public function redirectUrlRequestToken(Account $account)
    {
        $arguments = [
            'client_id' => $account->getClientId(),
            'redirect_uri' => $this->getRedirectUri($account),
            'scope' => $account->getScope(),
            'response_type' => 'code',
            'state' => time(),
        ];

        $uri = self::AUTHORIZATION_ENDPOINT . '?' . urldecode(http_build_query($arguments));
        header('Location: ' . $uri);
        die();
    }

    /**
     * @param Account $account
     *
     * @return string
     */
    private function getRedirectUri(Account $account)
    {
        $formProtection = FormProtectionFactory::get();
        $formToken = $formProtection->generateToken('AuthorizationRequest');

        return urlencode('http://' . $_SERVER['HTTP_HOST'] . BackendUtility::getModuleUrl('tools_HubicHubic', [
                'tx_hubic_tools_hubichubic' => [
                    'action' => 'callback',
                    'controller' => 'Backend\Account',
                    'account' => $account->getUid(),
                ],
                'formToken' => $formToken,
            ]));
    }

    public function getAccount()
    {
        return $this->fetch('/account');
    }

    /**
     * Get hubiC account Quota.
     *
     * @see https://api.hubic.com/console/
     *
     * @return ResponseInterface
     */
    public function getAccountQuota()
    {
        return $this->fetch('/account/usage');
    }

    /**
     * Get hubiC agreements.
     *
     * @see https://api.hubic.com/console/
     *
     * @return ResponseInterface
     */
    public function getAgreement()
    {
        return $this->fetch('/agreement');
    }

    /**
     * Get hubiC getAllLinks.
     *
     * @see https://api.hubic.com/console/
     *
     * @return ResponseInterface
     */
    public function getAllLinks()
    {
        return $this->fetch('/account/getAllLinks');
    }

    /**
     * @param RequestFactory $requestFactory
     */
    public function injectRequestFactory(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param PersistenceManager $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManager $persistenceManager): void
    {
        $this->persistenceManager = $persistenceManager;
    }
}
