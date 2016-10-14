<?php
namespace Filoucrackeur\Hubic\Controller\Backend;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class AuthController extends ActionController {

    public function indexAction() {
        DebuggerUtility::var_dump($this->view);
    }
}
