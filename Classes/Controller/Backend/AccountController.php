<?php
namespace Filoucrackeur\Hubic\Controller\Backend;

use Filoucrackeur\Hubic\Domain\Model\Account;
use Filoucrackeur\Hubic\Service\ClientUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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


    public function indexAction()
    {
        $accounts = $this->accountRepository->findAll();
        $this->view->assign('accounts', $accounts);
    }

    public function showAction(Account $account)
    {
        /* @var ClientUtility $client */
        $client = GeneralUtility::makeInstance(ClientUtility::class,$account);
        if ($account->getAccessToken()) {

            $clientAccount = $client->getAccount();
            $clientAccountQuota = $client->getAccountQuota();
            $agreement = $client->getAgreement();
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


    public function authenticationRequestAction(Account $account)
    {
        $this->client = GeneralUtility::makeInstance(ClientUtility::class,$account);
        $this->redirectToUri($this->client->getAuthorizationRequestUrl($account));
    }

    public function authenticationResponseAction(Account $account)
    {
        $this->client = GeneralUtility::makeInstance(ClientUtility::class,$account);

        $this->addFlashMessage('Token successfully added', 'Authentication request', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->redirect('show', '', '', ['account' => $account]);
    }


    public function deleteAction(Account $account)
    {
        $this->persistenceManager->remove($account);
        $this->addFlashMessage('Account successfully deleted', 'Account', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->redirect('index');
    }
}
