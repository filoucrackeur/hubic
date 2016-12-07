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
namespace Filoucrackeur\Hubic\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class AccountRepository extends  Repository  {

    // Order by BE sorting
    protected $defaultOrderings = array(
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );


    public function initializeObject(){
        /** @var $defaultQuerySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $defaultQuerySettings = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');

        // don't add the pid constraint
        $defaultQuerySettings->setRespectStoragePage(FALSE);
        // don't add fields from enablecolumns constraint
        $defaultQuerySettings->setEnableFieldsToBeIgnored(TRUE);
        // don't add sys_language_uid constraint
        $defaultQuerySettings->setRespectSysLanguage(TRUE);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }
}
