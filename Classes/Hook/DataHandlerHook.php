<?php
namespace Portrino\PxDbsequencer\Hook;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Andre Wuttig <wuttig@portrino.de>, portrino GmbH
 *           Axel Boeswetter <boeswetter@portrino.de>, portrino GmbH
 *           Thomas Griessbach <griessbach@portrino.de>, portrino GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use Portrino\PxDbsequencer\Service;

/**
 * DataHandlerHook
 *
 * @package px_dbsequencer
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DataHandlerHook {
    /**
     * @var Service\TYPO3Service
     */
    private $TYPO3Service;

    /**
     * Constructor
     *
     * @return DataHandlerHook
     */
    public function __construct() {
        $this->TYPO3Service = new Service\TYPO3Service(new Service\SequencerService());
    }

    /**
     * Hook: processDatamap_preProcessFieldArray
     *
     * @param array $incomingFieldArray
     * @param string $table
     * @param int $id
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $pObj
     * @return void
     */
    public function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, &$pObj) {
        if (strpos($id, 'NEW') !== FALSE && $this->TYPO3Service->needsSequencer($table)) {
            $newId = $this->TYPO3Service->getSequencerService()->getNextIdForTable($table);
            if ($newId) {
                $incomingFieldArray['uid'] = $newId;
                $pObj->suggestedInsertUids[$table . ':' . $newId] = TRUE;
            }
        }
    }

}