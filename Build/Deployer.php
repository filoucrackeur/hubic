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

namespace Filoucrackeur;

use Composer\Script\Event;

class Deployer
{
    /**
     * @param Event $event
     */
    public static function deploy(Event $event)
    {
        $_EXTKEY = 'hubic';
        require __DIR__  . '../../ext_emconf.php';


        // Zip
        shell_exec('zip -r ../hubic_' . $EM_CONF[$_EXTKEY]['version'] . '.zip * -x ../.Build\*  ../Build\* ../Tests\* ../.travis.yml ../.scrutinize.yml ../.coveralls.yml');
    }
}
