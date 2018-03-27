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

namespace Filoucrackeur\Hubic\Tests\Service;

use Filoucrackeur\Hubic\Domain\Model\Account;
use Filoucrackeur\Hubic\Domain\Repository\AccountRepository;
use Filoucrackeur\Hubic\Service\HubicService;
use Filoucrackeur\Hubic\Tests\Unit\AbstractUnitTest;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class HubicServiceTest extends AbstractUnitTest
{

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var \Filoucrackeur\Hubic\Service\HubicService
     */
    protected $hubicService;

    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var Account
     */
    protected $accountMock;


    public function setUp()
    {
        $this->hubicService = new HubicService();
        $this->account = new Account();
        $this->account->setName('myName');
        $this->account->setClientId('myClientId');
        $this->account->setClientSecret('myClientSecret');
        $this->account->setAccessToken('myAccessToken');
        $this->account->setRefreshToken('myRefreshToken');
        $this->account->setScope('myScope');

        $this->accountRepository = $this->getMockBuilder(AccountRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestFactory = $this->getMockBuilder(RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['request'])
            ->getMock();

        $this->persistenceManager = $this->getMockBuilder(PersistenceManager::class)
            ->setMethods(['update', 'persistAll', 'remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->accountMock = $this->getMockBuilder(Account::class)
            ->setMethods(['getAccessToken'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->inject($this->hubicService, 'requestFactory', $this->requestFactory);

        $this->inject($this->hubicService, 'persistenceManager', $this->persistenceManager);

    }

    public function tearDown()
    {
        unset($this->account, $this->hubicService);
    }

    /**
     * @test
     */
    public function getAccountsWillReturnArray()
    {
        $this->accountRepository->method('findAll')->willReturn([]);
        $this->inject($this->hubicService, 'accountRepository', $this->accountRepository);
        $accounts = $this->hubicService->getAccounts();

        $this->assertInternalType('array', $accounts);
    }

    /**
     * @test
     */
    public function getHostsReturnString()
    {
        $this->assertInternalType('string', $this->hubicService->getHost());
    }

    /**
     * @test
     */
    public function getAccessTokenWillReturnBool()
    {

        $response = new Response();
        $this->requestFactory->method('request')->willReturn($response);
        $this->persistenceManager->method('update');

        $result = $this->hubicService->accessToken($this->account);
        $this->assertInternalType('bool', $result);
    }

    /**
     * @test
     */
    public function getRefreshTokenWillReturnBool()
    {

        $response = new Response();
        $this->requestFactory->method('request')->willReturn($response);
        $this->persistenceManager->method('update');

        $result = $this->hubicService->refreshToken($this->account);
        $this->assertInternalType('bool', $result);
    }

    /**
     * @test
     */
    public function deleteReturnResponse()
    {
        $this->persistenceManager->expects($this->once())->method('remove');
        $this->persistenceManager->expects($this->once())->method('persistAll');
        $this->hubicService->delete($this->account);
    }

    /**
     * @test
     */
    public function unlinkReturnResponse()
    {
        $this->persistenceManager->expects($this->once())->method('update');
        $this->persistenceManager->expects($this->once())->method('persistAll');
        $this->hubicService->unlink($this->account);
    }


    /**
     * @test
     */
    public function deleteLinkReturnResponse()
    {
        $hubMock = $this->getMockBuilder(HubicService::class)
            ->setMethods(['fetch', 'getAccessToken'])
            ->disableOriginalConstructor()
            ->getMock();

        $hubMock->method('fetch')->willReturn(new Response());
        $this->inject($this->hubicService, 'account', $this->account);
        $this->assertInstanceOf(Response::class, $hubMock->deleteLink($this->account, 'https://test.fr'));
    }

    /**
     * @test
     */
    public function getAccountQuotaReturnResponse()
    {
        $hubMock = $this->getMockBuilder(HubicService::class)
            ->setMethods(['fetch', 'getAccessToken'])
            ->disableOriginalConstructor()
            ->getMock();

        $hubMock->method('fetch')->willReturn(new Response());
        $this->inject($this->hubicService, 'account', $this->account);
        $this->assertInstanceOf(Response::class, $hubMock->getAccountQuota());
    }

    /**
     * @test
     */
    public function getAgreementReturnResponse()
    {
        $hubMock = $this->getMockBuilder(HubicService::class)
            ->setMethods(['fetch', 'getAccessToken'])
            ->disableOriginalConstructor()
            ->getMock();

        $hubMock->method('fetch')->willReturn(new Response());
        $this->inject($this->hubicService, 'account', $this->account);
        $this->assertInstanceOf(Response::class, $hubMock->getAgreement());
    }

    /**
     * @test
     */
    public function getAllLinksReturnResponse()
    {
        $hubMock = $this->getMockBuilder(HubicService::class)
            ->setMethods(['fetch', 'getAccessToken'])
            ->disableOriginalConstructor()
            ->getMock();

        $hubMock->method('fetch')->willReturn(new Response());
        $this->inject($this->hubicService, 'account', $this->account);
        $this->assertInstanceOf(Response::class, $hubMock->getAllLinks());
    }

    /**
     * @test
     */
    public function getAccountReturnResponse()
    {
        $hubMock = $this->getMockBuilder(HubicService::class)
            ->setMethods(['fetch', 'getAccessToken'])
            ->disableOriginalConstructor()
            ->getMock();

        $hubMock->method('fetch')->willReturn(new Response());
        $this->inject($this->hubicService, 'account', $this->account);
        $this->assertInstanceOf(Response::class, $hubMock->getAccount());
    }

    /**
     * @test
     */
    public function getRedirectUrlUriReturnString()
    {
        $hubMock = $this->getMockBuilder(HubicService::class)
            ->setMethods(['fetch', 'getAccessToken','getRedirectUri'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->inject($this->hubicService, 'account', $this->account);
        $hubMock->expects($this->once())->method('getRedirectUri');

        $this->assertInternalType('string', $hubMock->getRedirectUri($this->account));
    }

    public function getRedirectUrlRequestTokenRedirect()
    {
//        $hubMock = $this->getMockBuilder(HubicService::class)
//            ->setMethods(['fetch', 'getAccessToken','redirectUrlRequestToken'])
//            ->disableOriginalConstructor()
//            ->getMock();
//
////        $hubMock->method('fetch')->willReturn(new Response());
//        $this->inject($this->hubicService, 'account', $this->account);
//        $hubMock->expects($this->once())->method('redirectUrlRequestToken');
//        var_dump($this->hub->redirectUrlRequestToken($this->account));
////        $this->assertInstanceOf(Response::class, $this->hubicService->redirectUrlRequestToken($this->account));
    }


}


