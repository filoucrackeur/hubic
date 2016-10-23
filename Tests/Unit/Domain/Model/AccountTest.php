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
namespace Filoucrackeur\Hubic\Tests\Domain\Model;

use Filoucrackeur\Hubic\Domain\Model\Account;
use Filoucrackeur\Hubic\Tests\Unit\AbstractUnitTest;

class AccountTest extends AbstractUnitTest
{

    /**
     * @var Account
     */
    protected $account = NULL;

    public function setUp()
    {
        $this->account = new Account();
    }

    public function tearDown()
    {
        unset($this->account);
    }

    /**
     * @test
     */
    public function setClientId()
    {
        $id = (string)"465464";
        $this->account->setClientId($id);

        $this->assertEquals($this->account->getClientId(),$id);
    }
}
