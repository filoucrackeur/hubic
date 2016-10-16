<?php
namespace Filoucrackeur\Hubic\Domain\Repository;


use TYPO3\CMS\Extbase\Persistence\Repository;

class AccountRepository extends  Repository  {

    // Order by BE sorting
    protected $defaultOrderings = array(
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );
}
