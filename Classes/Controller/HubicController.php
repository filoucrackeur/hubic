<?php
namespace Filoucrackeur\Hubic\Controller;

use Filoucrackeur\Hubic\Domain\Model\Account;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class HubicController extends ActionController {

    /**
     * @var \Filoucrackeur\Hubic\Service\ClientUtility
     * @inject
     */
    protected $client;

    /**
     * @var \Filoucrackeur\Hubic\Domain\Repository\AccountRepository
     * @inject
     */
    protected $accountRepository;

    public function listAction() {
        $account = $this->accountRepository->findByIdentifier($this->settings['account']);
        $this->client->callHubic($account);
        $this->view->assign('links', $this->client->getAllLinks());
    }

}
