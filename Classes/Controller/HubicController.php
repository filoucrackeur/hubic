<?php

namespace Filoucrackeur\Hubic\Controller;

use Filoucrackeur\Hubic\Domain\Model\Account;
use Filoucrackeur\Hubic\Domain\Repository\AccountRepository;
use Filoucrackeur\Hubic\Service\HubicService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class HubicController extends ActionController
{
    /**
     * @var \Filoucrackeur\Hubic\Service\HubicService
     */
    protected $hubicService;

    /**
     * @var \Filoucrackeur\Hubic\Domain\Repository\AccountRepository
     */
    protected $accountRepository;

    public function listAction()
    {
        try {

            /** @var Account $account */
            $account = $this->accountRepository->findByIdentifier($this->settings['account']);
            if ($account) {
                $this->hubicService->setAccount($account);
                $this->view->assign('links', $this->hubicService->getAllLinks());
            }
        } catch(\Exception $e) {
        }
    }

    /**
     * @param AccountRepository $accountRepository
     */
    public function injectAccountRepository(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param HubicService $hubicService
     */
    public function injectHubicService(HubicService $hubicService)
    {
        $this->hubicService = $hubicService;
    }
}
