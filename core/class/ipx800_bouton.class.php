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

class ipx800_bouton extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */
	public function postInsert()
	{
        $state = $this->getCmd(null, 'state');
        if ( ! is_object($state) ) {
            $state = new ipx800_boutonCmd();
			$state->setName('Etat');
			$state->setEqLogic_id($this->getId());
			$state->setType('info');
			$state->setSubType('binary');
			$state->setLogicalId('state');
			$state->setEventOnly(1);
			$state->save();
		}
        $btn_on = $this->getCmd(null, 'btn_on');
        if ( ! is_object($btn_on) ) {
            $btn_on = new ipx800_boutonCmd();
			$btn_on->setName('On');
			$btn_on->setEqLogic_id($this->getId());
			$btn_on->setType('action');
			$btn_on->setSubType('other');
			$btn_on->setLogicalId('btn_on');
			$btn_on->setEventOnly(1);
			$btn_on->setIsVisible(0);
			$btn_on->save();
		}
        $btn_off = $this->getCmd(null, 'btn_off');
        if ( ! is_object($btn_off) ) {
            $btn_off = new ipx800_boutonCmd();
			$btn_off->setName('Off');
			$btn_off->setEqLogic_id($this->getId());
			$btn_off->setType('action');
			$btn_off->setSubType('other');
			$btn_off->setLogicalId('btn_off');
			$btn_off->setEventOnly(1);
			$btn_off->setIsVisible(0);
			$btn_off->save();
		}
	}

	public function postUpdate()
	{
        $nbimpulsion = $this->getCmd(null, 'nbimpulsion');
        if ( is_object($nbimpulsion) ) {
			$nbimpulsion->remove();
		}
        $state = $this->getCmd(null, 'etat');
        if ( is_object($state) ) {
			$state->setLogicalId('state');
			$state->save();
		}
        $btn_on = $this->getCmd(null, 'btn_on');
        if ( ! is_object($btn_on) ) {
            $btn_on = new ipx800_boutonCmd();
			$btn_on->setName('On');
			$btn_on->setEqLogic_id($this->getId());
			$btn_on->setType('action');
			$btn_on->setSubType('other');
			$btn_on->setLogicalId('btn_on');
			$btn_on->setEventOnly(1);
			$btn_on->setIsVisible(0);
			$btn_on->save();
		}
        $btn_off = $this->getCmd(null, 'btn_off');
        if ( ! is_object($btn_off) ) {
            $btn_off = new ipx800_boutonCmd();
			$btn_off->setName('Off');
			$btn_off->setEqLogic_id($this->getId());
			$btn_off->setType('action');
			$btn_off->setSubType('other');
			$btn_off->setLogicalId('btn_off');
			$btn_off->setEventOnly(1);
			$btn_off->setIsVisible(0);
			$btn_off->save();
		}
	}

	public function preInsert()
	{
		$gceid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2);
		$this->setEqType_name('ipx800_bouton');
		$this->setIsEnable(0);
		$this->setIsVisible(0);
	}

    public static function event() {
        $cmd = ipx800_boutonCmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception('Commande ID virtuel inconnu : ' . init('id'));
        }
		if ($cmd->execCmd(null, 2) != $cmd->formatValue(init('state'))) {
			$cmd->setCollectDate('');
			$cmd->event(init('state'));
		}
    }

	public function configPush($url_serveur, $ipjeedom, $pathjeedom) {
        $cmd = $this->getCmd(null, 'state');
		$gceid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2);
		$url_serveur .= 'protect/settings/push1.htm?channel='.$gceid;
		$url = $url_serveur .'&server='.$ipjeedom.'&port=80&pass=&enph=1';
		log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
		$result = file_get_contents($url);
		if ( $result === false )
			throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
		$url = $url_serveur .'&cmd1='.urlencode($pathjeedom.'core/api/jeeApi.php?api='.config::byKey('api').'&type=ipx800_bouton&id='.$cmd->getId().'&state=1');
		log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
		$result = file_get_contents($url);
		if ( $result === false )
			throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
		$url = $url_serveur .'&cmd2='.urlencode($pathjeedom.'core/api/jeeApi.php?api='.config::byKey('api').'&type=ipx800_bouton&id='.$cmd->getId().'&state=0');
		log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
		$result = file_get_contents($url);
		if ( $result === false )
			throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
	}

    public function getLinkToConfiguration() {
        return 'index.php?v=d&p=ipx800&m=ipx800&id=' . $this->getId();
    }
    /*     * **********************Getteur Setteur*************************** */
}

class ipx800_boutonCmd extends cmd 
{
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */
    public function execute($_options = null) {
		log::add('ipx800','debug','execute '.$_options);
		$eqLogic = $this->getEqLogic();
        if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
            throw new Exception(__('Equipement desactivé impossible d\éxecuter la commande : ' . $this->getHumanName(), __FILE__));
        }
		$IPXeqLogic = eqLogic::byId(substr ($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")));
		$gceid = substr($eqLogic->getLogicalId(), strpos($eqLogic->getLogicalId(),"_")+2);
		$url = $IPXeqLogic->getUrl();
		if ( $this->getLogicalId() == 'btn_on' )
			$url .= 'leds.cgi?set='.$gceid;
		else if ( $this->getLogicalId() == 'btn_off' )
			$url .= 'leds.cgi?clear='.$gceid;
		else
			return false;
			
		$result = file_get_contents($url);
		log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
		$count = 0;
		while ( $result === false && $count < 3 ) {
			$result = file_get_contents($url);
			$count++;
		}
		if ( $result === false ) {
			throw new Exception(__('L\'ipx ne repond pas.',__FILE__)." ".$IPXeqLogic->getName());
		}
        return false;
    }

    public function formatValue($_value) {
        if (trim($_value) == '') {
            return '';
        }
        if ($this->getType() == 'info') {
            switch ($this->getSubType()) {
                case 'binary':
                    $_value = strtolower($_value);
                    if ($_value == 'dn') {
                        $_value = 1;
                    }
                    if ($_value == 'up') {
                        $_value = 0;
                    }
					if ((is_numeric(intval($_value)) && intval($_value) > 1) || $_value || $_value == 1) {
                        $_value = 1;
                    }
					if ( $this->getDisplay('invertBinary') == 1 ) {
						$_value = ($_value == 1) ? 0 : 1;
					}
                    return $_value;
            }
        }
        return $_value;
    }
    /*     * **********************Getteur Setteur*************************** */
}
?>
