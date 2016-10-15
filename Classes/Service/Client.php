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

use Doctrine\Common\Util\Debug;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Client for hubiC (http://api.hubic.com).
 *
 * @package Filoucrackeur\Hubic
 */
class Client implements SingletonInterface
{
    const HUBIC_API_URL = 'https://api.hubic.com/oauth/auth/';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var
     */
    protected $token;

    /**
     * @var string
     */
    protected $redirectUri = 'https%3A%2F%2Fapi.hubic.com%2Fsandbox%2F';

    /**
     * @var string
     */
    protected $scope = 'usage.r,account.r,getAllLinks.r,credentials.r,sponsorCode.r,activate.w,sponsored.r,links.drw';

    /**
     * @var string
     */
    protected $responseType = 'code';

    /**
     * @var string
     */
    protected $state;

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

        $this->id = $extensionConfiguration['client_id']['value'];
        $this->secret = $extensionConfiguration['client_secret']['value'];
        $this->token = $extensionConfiguration['token']['value'];
        $this->state = md5(time());
    }

    public function authorizationRequest() {
        $this->setRedirectUri(urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']));
//        $this->redirectUri = 'http://localhost:32768/';

        $params =
            'client_id='.$this->getId().
//            'client_secret' => $this->getSecret(),
            '&redirect_url=' . $this->getRedirectUri().
            '&scope='.
            '&response_type=' . $this->getResponseType().
            '&state=' . uniqid()
        ;

        $authorizationRequestUrl = self::HUBIC_API_URL . '?' . $params;
//DebuggerUtility::var_dump($_SERVER);
//        die();
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $authorizationRequestUrl);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        $result = curl_exec($ch);
//        curl_close($ch);
//        echo $result;
//        DebuggerUtility::var_dump($result);
//        die();

        return $authorizationRequestUrl;
    }
    public function getAccessToken() {

    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * @param string $redirectUri
     */
    public function setRedirectUri(string $redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     */
    public function setScope(string $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }


    /**
     * @return string
     */
    public function getResponseType(): string
    {
        return $this->responseType;
    }

    /**
     * @param string $responseType
     */
    public function setResponseType(string $responseType)
    {
        $this->responseType = $responseType;
    }
}
