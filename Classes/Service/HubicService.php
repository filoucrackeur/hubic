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
use Filoucrackeur\Hubic\Domain\Repository\AccountRepository;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;

class HubicService implements SingletonInterface
{
    const AUTHORIZATION_ENDPOINT = 'https://api.hubic.com/oauth/auth/';

    const TOKEN_ENDPOINT = 'https://api.hubic.com/oauth/token/';

    const DOMAIN_API = 'https://api.hubic.com/';

    const VERSION_API = '1.0';

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var \Filoucrackeur\Hubic\Domain\Repository\AccountRepository
     */
    protected $accountRepository;

    /**
     * @var \Filoucrackeur\Hubic\Domain\Model\Account
     */
    protected $account;

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
                'redirect_uri' => $this->getHost() . '/',
                'grant_type' => 'authorization_code',
            ],
            RequestOptions::VERSION => '1.1',
        ];

        $response = $this->requestFactory->request(self::TOKEN_ENDPOINT, 'POST', $additionalOptions);

        if (200 === $response->getStatusCode()) {
            $content = json_decode($response->getBody()->getContents());
            $account->setAccessToken($content->access_token);
            $account->setRefreshToken($content->refresh_token);
            $expireIn = new \DateTime('+ ' . $content->expires_in . ' seconds');
            $account->setExpirationDate($expireIn);
            $this->persistenceManager->update($account);
            $this->persistenceManager->persistAll();
        }

        return true;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
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
    private function getRedirectUri(Account $account): string
    {
        $formProtection = FormProtectionFactory::get();
        $formToken = $formProtection->generateToken('AuthorizationRequest');

        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

        return urlencode($this->getHost() . $uriBuilder
                ->setCreateAbsoluteUri(true)
                ->setArguments([
                    'tx_hubic_tools_hubichubic' => [
                        'action' => 'callback',
                        'controller' => 'Backend\Account',
                        'account' => $account->getUid(),
                    ],
                    'formToken' => $formToken
                ])->buildBackendUri());
    }

    public function getAccount()
    {
        return $this->fetch('/account');
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $arguments
     * @return \Psr\Http\Message\ResponseInterface|string
     */
    public function fetch(string $path, $method = 'GET', array $arguments = [])
    {
        $additionalOptions = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer ' . $this->account->getAccessToken(),
            ],
            RequestOptions::VERSION => '1.1',
        ];

        if (!empty($arguments)) {
            $additionalOptions[RequestOptions::FORM_PARAMS] = $arguments;
        }

        try {
            $response = $this->requestFactory->request(self::DOMAIN_API . self::VERSION_API . $path, $method,
                $additionalOptions);

            if (200 === $response->getStatusCode()) {
                return json_decode($response->getBody()->getContents());
            }
        } catch (\Exception $e) {
            if ($this->refreshToken($this->account)) {
                return $this->fetch($path, $method);
            }
        }
        return null;
    }

    /**
     * @param Account $account
     * @return bool
     */
    public function refreshToken(Account $account): bool
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
     * @param Account $account
     */
    public function delete(Account $account): void
    {
        $this->persistenceManager->remove($account);
        $this->persistenceManager->persistAll();
    }

    /**
     * @param Account $account
     */
    public function unlink(Account $account): void
    {
        $account->setAccessToken('');
        $account->setRefreshToken('');
        $this->persistenceManager->update($account);
        $this->persistenceManager->persistAll();
    }

    /**
     * @return QueryResult|null
     */
    public function getAccounts()
    {
        return $this->accountRepository->findAll();
    }

    /**
     * Get hubiC account Quota.
     *
     * @see https://api.hubic.com/console/
     *
     * @return ResponseInterface|null
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
     * @return ResponseInterface|null
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
     * @return ResponseInterface|null
     */
    public function getAllLinks()
    {
        return $this->fetch('/account/getAllLinks');
    }

    /**
     * Delete hubiC link.
     *
     * @see https://api.hubic.com/console/
     *
     * @param string $uri
     * @return ResponseInterface|null
     */
    public function deleteLink(string $uri)
    {
        $arguments = [
            'uri' => $uri
        ];

        return $this->fetch('/account/link', 'DELETE', $arguments);
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
    public function injectPersistenceManager(PersistenceManager $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param AccountRepository $accountRepository
     */
    public function injectAccountRepository(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }
}
