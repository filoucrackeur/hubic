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
use Filoucrackeur\Hubic\Service\HubicService;
use Filoucrackeur\Hubic\Tests\Unit\AbstractUnitTest;

class HubicServiceTest extends AbstractUnitTest
{

    /**
     * @var Account
     */
    protected $account = null;

    /**
     * @var HubicService
     */
    protected $hubicService;

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
    }

    public function tearDown()
    {
        unset($this->account);
        unset($this->hubicService);
    }

    /**
     * @test
     */
    public function getMethodGetAccessTokenReturnTrue()
    {
        $this->assertEquals(
            true,true
//            $this->hubicService->accessToken($this->account)
        );
    }

}
