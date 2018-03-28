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

use Filoucrackeur\Hubic\Controller\Backend\AccountController;
use Filoucrackeur\Hubic\Domain\Model\Account;
use Filoucrackeur\Hubic\Domain\Repository\AccountRepository;
use Filoucrackeur\Hubic\Service\HubicService;
use Filoucrackeur\Hubic\Tests\Unit\AbstractUnitTest;
use TYPO3\CMS\Extbase\Mvc\Web\Request;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Fluid\View\TemplateView;

class AccountControllerTest extends AbstractUnitTest
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
     * @var \Filoucrackeur\Hubic\Controller\Backend\AccountController
     */
    protected $controller = null;

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    /**
     * Set up framework
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->controller = new AccountController();

        $this->accountRepository = $this->getMockBuilder(AccountRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->hubicService = $this->getMockBuilder(HubicService::class)
            ->setMethods(['getAccounts','redirectUrlRequestToken','refreshToken','delete'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->view = $this->getMockBuilder(TemplateView::class)
            ->setMethods([
                'assign',
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $this->persistenceManager = $this->getMockBuilder(PersistenceManager::class)
            ->setMethods(['update', 'persistAll', 'remove'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function getIndexAction()
    {
        $hubicMock = $this->getMockBuilder(HubicService::class)
            ->setMethods(['getAccounts','redirectUrlRequestToken','refreshToken'])
            ->disableOriginalConstructor()
            ->getMock();
        $hubicMock->method('getAccounts')->willReturn([]);
        $this->inject($this->controller, 'hubicService', $hubicMock);
        $hubicMock->expects($this->once())->method('getAccounts');

        $this->inject($this->controller, 'view', $this->view);
        $this->view->expects($this->once())->method('assign');

        $this->controller->indexAction();
    }


    /**
     * @test
     */
    public function getRefreshTokenAction()
    {
        $ctrlAccountMock = $this->getMockBuilder(AccountController::class)
            ->setMethods(['forward'])
            ->disableOriginalConstructor()
            ->getMock();
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->inject($ctrlAccountMock, 'hubicService', $this->hubicService);
        $this->inject($this->controller, 'request', $request);
        $this->hubicService->expects($this->once())->method('refreshToken');
        $ctrlAccountMock->method('forward')->willReturn(null);
        $ctrlAccountMock->expects($this->once())->method('forward');
        $ctrlAccountMock->refreshTokenAction(new Account());
    }

    /**
     * @test
     */
    public function getAccessTokenAction()
    {
        $this->inject($this->controller, 'hubicService', $this->hubicService);
        $this->hubicService->expects($this->once())->method('redirectUrlRequestToken');
        $this->controller->accessTokenAction(new Account());
    }

    public function deleteAction()
    {
        $account = new Account();

        $this->inject($this->controller, 'hubicService', $this->hubicService);
        $this->hubicService->expects($this->once())->method('delete')->with($account);

        $this->controller->deleteAction($account);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
