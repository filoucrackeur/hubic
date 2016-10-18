<?php
namespace Filoucrackeur\Hubic\Controller\Backend;

use Filoucrackeur\Hubic\Domain\Model\Account;
use Filoucrackeur\Hubic\Service\ClientUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class AccountController
 * @package Filoucrackeur\Hubic\Controller\Backend
 */
class AccountController extends ActionController
{
    /**
     * @var \Filoucrackeur\Hubic\Domain\Repository\AccountRepository
     * @inject
     */
    protected $accountRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * @var \Filoucrackeur\Hubic\Service\ClientUtility
     * @inject
     */
    protected $client;


    public function indexAction()
    {
        $accounts = $this->accountRepository->findAll();
        $this->view->assign('accounts', $accounts);
    }

    /**
     * @param Account $account
     */
    public function showAction(Account $account)
    {
        if ($account->getAccessToken()) {
            $this->client->callHubic($account);
            $clientAccount = $this->client->getAccount();
            $clientAccountQuota = $this->client->getAccountQuota();
            $agreement = $this->client->getAgreement();
            $this->view->assignMultiple([
                'account' => $account,
                'clientAccount' => $clientAccount,
                'clientAccountQuota' => $clientAccountQuota,
                'agreement' => $agreement
            ]);
        } else {
            $this->view->assign('account', $account);
        }
    }

    /**
     * @param Account $account
     */
    public function authenticationRequestAction(Account $account)
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
    public function authenticationResponseAction(Account $account)
    {
        $this->client->callHubic($account);
        $this->addFlashMessage('Token successfully added', 'Authentication request', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->redirect('show', '', '', ['account' => $account]);
    }


    /**
     * @param Account $account
     */
    public function deleteAction(Account $account)
    {
        $this->persistenceManager->remove($account);
        $this->addFlashMessage('Account successfully deleted', 'Account', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->redirect('index');
    }
}
