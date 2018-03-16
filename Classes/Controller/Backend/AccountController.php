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
use Filoucrackeur\Hubic\Service\HubicService;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class AccountController
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
     * @var \Filoucrackeur\Hubic\Service\HubicService
     */
    protected $hubicService;

    public function indexAction(): void
    {
        $accounts = $this->accountRepository->findAll();
        $this->view->assign('accounts', $accounts);
    }

    /**
     * @param Account $account
     */
    public function showAction(Account $account): void
    {
        if ($account->getAccessToken()) {
            $this->hubicService->setAccount($account);
            $clientAccountQuota = $this->hubicService->getAccountQuota();
            $clientAccount = $this->hubicService->getAccount();
            $agreement = $this->hubicService->getAgreement();
            $links = $this->hubicService->getAllLinks();
            $this->view->assignMultiple([
                'clientAccount' => $clientAccount,
                'clientAccountQuota' => $clientAccountQuota,
                'agreement' => $agreement,
                'links' => $links
            ]);
        }

        $this->view->assign('account', $account);
    }

    /**
     * @param Account $account
     */
    public function refreshTokenAction(Account $account): void
    {
        $this->hubicService->refreshToken($account);

        $this->forward('show', null, null, ['account' => $account]);
    }

    /**
     * @param Account $account
     */
    public function accessTokenAction(Account $account): void
    {
        $this->hubicService->redirectUrlRequestToken($account);
    }

    /**
     * @param Account $account
     */
    public function callbackAction(Account $account): void
    {
        if ($this->hubicService->accessToken($account)) {
            $this->addFlashMessage(LocalizationUtility::translate('flashmessage.token_added', 'hubic'),
                LocalizationUtility::translate('flashmessage.authentication', 'hubic'),
                AbstractMessage::OK);
        } else {
            $this->addFlashMessage(LocalizationUtility::translate('flashmessage.missing_client_data', 'hubic'),
                LocalizationUtility::translate('flashmessage.authentication', 'hubic'),
                AbstractMessage::ERROR);
        }
        $this->forward('show', null, null, ['account' => $account]);
    }

    /**
     * @param Account $account
     */
    public function deleteAction(Account $account): void
    {
        $this->persistenceManager->remove($account);
        $this->addFlashMessage(LocalizationUtility::translate('flashmessage.account_deleted', 'hubic'),
            LocalizationUtility::translate('account'),
            AbstractMessage::OK);
        $this->forward('index');
    }

    /**
     * @param Account $account
     */
    public function unlinkAction(Account $account): void
    {
        $account->setAccessToken('');
        $account->setRefreshToken('');
        $this->persistenceManager->update($account);
        $this->persistenceManager->persistAll();
        $this->addFlashMessage(LocalizationUtility::translate('flashmessage.account_unlinked', 'hubic'),
            LocalizationUtility::translate('account', 'hubic'),
            AbstractMessage::OK);
        $this->forward('show', null, null, ['account' => $account]);
    }

    /**
     * @param AccountRepository $accountRepository
     */
    public function injectAccountRepository(AccountRepository $accountRepository): void
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param PersistenceManager $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManager $persistenceManager): void
    {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param HubicService $hubicService
     */
    public function injectHubicService(HubicService $hubicService): void
    {
        $this->hubicService = $hubicService;
    }
}
