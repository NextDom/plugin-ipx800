<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class ipx800_analogique extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */
	public function postUpdate()
	{
        $brut = $this->getCmd(null, 'voltage');
        if ( is_object($brut) ) {
			$brut->setLogicalId('brut');
			$brut->save();
		} else {
			$brut = $this->getCmd(null, 'brut');
		}
        $reel = $this->getCmd(null, 'reel');
        if ( ! is_object($reel) ) {
            $reel = new ipx800_analogiqueCmd();
			$reel->setName('Réel');
			$reel->setEqLogic_id($this->getId());
			$reel->setType('info');
			$reel->setSubType('numeric');
			$reel->setLogicalId('reel');
			$reel->setEventOnly(1);
			$reel->setConfiguration('calcul', '#' . $brut->getId() . '#');
			$reel->save();
		}
	}

	public function postInsert()
	{
        $brut = $this->getCmd(null, 'brut');
        if ( ! is_object($brut) ) {
            $brut = new ipx800_analogiqueCmd();
			$brut->setName('Brut');
			$brut->setEqLogic_id($this->getId());
			$brut->setType('info');
			$brut->setSubType('numeric');
			$brut->setLogicalId('brut');
			$brut->setIsVisible(false);
			$brut->setEventOnly(1);
			$brut->save();
		}
        $reel = $this->getCmd(null, 'reel');
        if ( ! is_object($reel) ) {
            $reel = new ipx800_analogiqueCmd();
			$reel->setName('Réel');
			$reel->setEqLogic_id($this->getId());
			$reel->setType('info');
			$reel->setSubType('numeric');
			$reel->setLogicalId('reel');
			$reel->setEventOnly(1);
			$reel->setConfiguration('calcul', '#' . $brut->getId() . '#');
			$reel->save();
		}
	}

	public function preInsert()
	{
		$gceid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2);
		$this->setEqType_name('ipx800_analogique');
		$this->setIsEnable(0);
		$this->setIsVisible(0);
	}

    public static function event() {
        $cmd = ipx800_analogiqueCmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception('Commande ID virtuel inconnu : ' . init('id'));
        }
		$cmd->event(init('voltage'));
    }

    public function getLinkToConfiguration() {
        return 'index.php?v=d&p=ipx800&m=ipx800&id=' . $this->getId();
    }
    /*     * **********************Getteur Setteur*************************** */
}

class ipx800_analogiqueCmd extends cmd 
{
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */

    /*     * *********************Methode d'instance************************* */

    /*     * **********************Getteur Setteur*************************** */
    public function preSave() {
        if ( $this->getLogicalId() == 'reel' ) {
            $this->setValue('');
            $calcul = $this->getConfiguration('calcul');
            preg_match_all("/#([0-9]*)#/", $calcul, $matches);
            $value = '';
            foreach ($matches[1] as $cmd_id) {
                if (is_numeric($cmd_id)) {
                    $cmd = self::byId($cmd_id);
                    if (is_object($cmd) && $cmd->getType() == 'info') {
                        $value .= '#' . $cmd_id . '#';
                        break;
                    }
                }
            }
			$this->setConfiguration('calcul', $calcul);
			
            $this->setValue($value);
        }
    }

    public function execute($_options = null) {
        if ($this->getLogicalId() == 'reel') {
			try {
				$calcul = $this->getConfiguration('calcul');
				if ( preg_match("/#brut#/", $calcul) ) {
					$EqLogic = $this->getEqLogic();
					$brut = $EqLogic->getCmd(null, 'brut');
					$calcul = preg_replace("/#brut#/", "#".$brut->getId()."#", $calcul);
				}
				$calcul = scenarioExpression::setTags($calcul);
				$test = new evaluate();
				$result = $test->Evaluer($calcul);
				if (is_numeric($result)) {
					$result = number_format($result, 2);
				} else {
					$result = str_replace('"', '', $result);
				}
				if ($this->getSubType() == 'numeric') {
					if (strpos($result, '.') !== false) {
						$result = str_replace(',', '', $result);
					} else {
						$result = str_replace(',', '.', $result);
					}
				}
				return $result;
			} catch (Exception $e) {
				log::add('ipx800', 'info', $e->getMessage());
				return scenarioExpression::setTags(str_replace('"', '', cmd::cmdToValue($this->getConfiguration('calcul'))));
			}
		} else {
			return $this->getConfiguration('value');
		}
    }
}
?>
