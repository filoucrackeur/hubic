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
namespace Filoucrackeur\Hubic\Controller\Backend;

use Filoucrackeur\Hubic\Domain\Model\Account;
use Filoucrackeur\Hubic\Domain\Repository\AccountRepository;
use Filoucrackeur\Hubic\Service\OAuth2\Client;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Class AccountController
 * @package Filoucrackeur\Hubic\Controller\Backend
 */
class AccountController extends ActionController
{
    /**
     * @var \Filoucrackeur\Hubic\Domain\Repository\AccountRepository
     */
    protected $accountRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var \Filoucrackeur\Hubic\Utility\ClientUtility
     */
    protected $client;

    /**
     * @param AccountRepository $accountRepository
     */
    public function injectAccountRepository(AccountRepository $accountRepository) : void
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param PersistenceManager $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManager $persistenceManager) : void
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param Client $client
     */
    public function injectClient(Client $client) : void
    {
        $this->client = $client;
    }

    public function indexAction() : void
    {
        $accounts = $this->accountRepository->findAll();
        $this->view->assign('accounts', $accounts);
    }

    /**
     * @param Account $account
     */
    public function showAction(Account $account) : void
    {
        if ($account->getAccessToken()) {
            $this->client->callHubic($account);
            $clientAccount = $this->client->getAccount();
            $clientAccountQuota = $this->client->getAccountQuota();
            $agreement = $this->client->getAgreement();
            $links = $this->client->getAllLinks();
            $this->view->assignMultiple([
                'account' => $account,
                'clientAccount' => $clientAccount,
                'clientAccountQuota' => $clientAccountQuota,
                'agreement' => $agreement,
                'links' => $links
            ]);
        } else {
            $this->view->assign('account', $account);
        }
    }

    /**
     * @param Account $account
     */
    public function authenticationRequestAction(Account $account) : void
    {

        $account->setAccessToken('');
        $this->persistenceManager->update($account);
        $this->persistenceManager->persistAll();

        $this->client->setAccount($account);
        $this->redirectToUri($this->client->getAuthorizationRequestUrl($account));
    }

    /**
     * @param Account $account
     */
    public function authenticationResponseAction(Account $account) : void
    {
        if( $this->client->callHubic($account) ){
            $this->addFlashMessage('Token successfully added', 'Authentication request', AbstractMessage::OK);
        }else {
            $this->addFlashMessage('Failed getting token please check client ID and client secret', 'Authentication request', AbstractMessage::ERROR);
        }
        $this->redirect('show', '', '', ['account' => $account]);
    }

    public function addAction() {

    }


    /**
     * @param Account $account
     */
    public function deleteAction(Account $account) : void
    {
        $this->persistenceManager->remove($account);
        $this->addFlashMessage('Account successfully deleted', 'Account', AbstractMessage::OK);
        $this->redirect('index');
    }

    /**
     * @param Account $account
     */
    public function unlinkAction(Account $account) : void
    {
        $account->setAccessToken('');
        $this->persistenceManager->update($account);
        $this->persistenceManager->persistAll();
        $this->addFlashMessage('Account successfully unlinked', 'Account', AbstractMessage::OK);
        $this->redirect('show', '', '', ['account' => $account]);
    }
}
