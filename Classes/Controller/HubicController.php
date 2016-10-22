<?php
namespace Filoucrackeur\Hubic\Controller;

use Filoucrackeur\Hubic\Domain\Model\Account;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class HubicController extends ActionController {

    /**
     * @var \Filoucrackeur\Hubic\Service\ClientUtility
     * @inject
     */
    protected $client;

    /**
     * @param Account $account
     */
    public function listAction(Account $account) {
        $this->view->assign('links', $this->client->getAllLinks());
    }

}
