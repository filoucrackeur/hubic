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

namespace Filoucrackeur\Hubic\Tests\Controller\Backend;

use Filoucrackeur\Hubic\Controller\HubicController;
use Filoucrackeur\Hubic\Domain\Model\Account;
use Filoucrackeur\Hubic\Domain\Repository\AccountRepository;
use Filoucrackeur\Hubic\Service\HubicService;
use Filoucrackeur\Hubic\Tests\Unit\AbstractUnitTest;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Fluid\View\StandaloneView;

class HubicControllerTest extends AbstractUnitTest
{

    /**
     * @var \Filoucrackeur\Hubic\Service\HubicService
     */
    protected $hubicService;

    /**
     * @var StandaloneView
     */
    public $view;

    /**
     * @var AccountRepository
     */
    protected $accountRepository;

    /**
     * @var \Filoucrackeur\Hubic\Controller\Backend\AccountController
     */
    protected $controller;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var Account
     */
    protected $account;

    /**
     * Set up framework
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->controller = new HubicController();

        $this->account = new Account();
        $this->account->setName('myName');
        $this->account->setClientId('myClientId');
        $this->account->setClientSecret('myClientSecret');
        $this->account->setAccessToken('myAccessToken');
        $this->account->setRefreshToken('myRefreshToken');
        $this->account->setScope('myScope');

        $this->accountRepository = $this->getMockBuilder(AccountRepository::class)
            ->setMethods(['findAll','findByIdentifier'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestFactory = $this->getMockBuilder(RequestFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['request'])
            ->getMock();

        $this->hubicService = $this->getMockBuilder(HubicService::class)
            ->setMethods(['getAccounts'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->view = $this->getMockBuilder(StandaloneView::class)
            ->setMethods([
                'assign',
            ])
            ->disableOriginalConstructor()
            ->getMock();

    }

    /**
     * @test
     */
    public function getListAction()
    {

        $this->inject($this->controller, 'hubicService', $this->hubicService);
        $this->hubicService->method('getAccounts')->willReturn([]);

        $this->inject($this->controller, 'view', $this->view);
        $this->inject($this->controller, 'accountRepository', $this->accountRepository);
        $this->inject($this->hubicService, 'requestFactory', $this->requestFactory);
        $this->accountRepository->method('findByIdentifier')->willReturn($this->account);
        $this->accountRepository->expects($this->once())->method('findByIdentifier');

        $this->controller->listAction();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
