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
    protected $account = null;

    public function setUp()
    {
        $this->account = new Account();
        $this->account->setName('myName');
        $this->account->setClientId('myClientId');
        $this->account->setClientSecret('myClientSecret');
        $this->account->setAccessToken('myAccessToken');
        $this->account->setRefreshToken('myRefreshToken');
        $this->account->setScope('myScope');
        $this->account->setExpirationDate(new \DateTime('-1 day'));
    }

    public function tearDown()
    {
        unset($this->account);
    }

    /**
     * @test
     */
    public function getNameForStringGetName()
    {
        $this->assertEquals(
            'myName',
            $this->account->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->account->setName('Account test');

        $this->assertAttributeEquals(
            'Account test',
            'name',
            $this->account
        );
    }

    /**
     * @test
     */
    public function getClientIdForStringGetClientId()
    {
        $this->assertEquals(
            'myClientId',
            $this->account->getClientId()
        );
    }

    /**
     * @test
     */
    public function setClientIdStringSetsClientId()
    {
        $this->account->setClientId('myClientId');

        $this->assertAttributeEquals(
            'myClientId',
            'clientId',
            $this->account
        );
    }

    /**
     * @test
     */
    public function getClientSecretForStringGetClientSecret()
    {
        $this->assertEquals(
            'myClientSecret',
            $this->account->getClientSecret()
        );
    }

    /**
     * @test
     */
    public function setClientSecretStringSetsClientSecret()
    {
        $this->account->setClientSecret('myClientSecret');

        $this->assertAttributeEquals(
            'myClientSecret',
            'clientSecret',
            $this->account
        );
    }

    /**
     * @test
     */
    public function getAccessTokenForStringGetAccessToken()
    {
        $this->assertEquals(
            'myAccessToken',
            $this->account->getAccessToken()
        );
    }

    /**
     * @test
     */
    public function setAccessTokenStringSetsAccessToken()
    {
        $this->account->setAccessToken('myAccessToken');

        $this->assertAttributeEquals(
            'myAccessToken',
            'accessToken',
            $this->account
        );
    }

    /**
     * @test
     */
    public function getRefreshTokenForStringGetRefreshToken()
    {
        $this->assertEquals(
            'myRefreshToken',
            $this->account->getRefreshToken()
        );
    }

    /**
     * @test
     */
    public function setRefreshTokenStringSetsRefreshToken()
    {
        $this->account->setRefreshToken('myRefreshToken');

        $this->assertAttributeEquals(
            'myRefreshToken',
            'refreshToken',
            $this->account
        );
    }
    
    /**
     * @test
     */
    public function getScopeForStringGetScope()
    {
        $this->assertEquals(
            'myScope',
            $this->account->getScope()
        );
    }

    /**
     * @test
     */
    public function setScopeStringSetsScope()
    {
        $this->account->setScope('myScope');

        $this->assertAttributeEquals(
            'myScope',
            'scope',
            $this->account
        );
    }

    /**
     * @test
     */
    public function getExpirationDateGetDateTime()
    {
        $this->assertInstanceOf(\DateTime::class,
            $this->account->getExpirationDate()
        );
    }

    /**
     * @test
     */
    public function getIsExpiredReturnFalse()
    {
        $this->assertTrue($this->account->isExpired());
    }
}
