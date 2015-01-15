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

class ipx800_relai extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */
	public function postInsert()
	{
        $state = $this->getCmd(null, 'state');
        if ( ! is_object($state) ) {
            $state = new ipx800_relaiCmd();
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
            $btn_on = new ipx800_relaiCmd();
			$btn_on->setName('On');
			$btn_on->setEqLogic_id($this->getId());
			$btn_on->setType('action');
			$btn_on->setSubType('other');
			$btn_on->setLogicalId('btn_on');
			$btn_on->setEventOnly(1);
			$btn_on->save();
		}
        $btn_off = $this->getCmd(null, 'btn_off');
        if ( ! is_object($btn_off) ) {
            $btn_off = new ipx800_relaiCmd();
			$btn_off->setName('Off');
			$btn_off->setEqLogic_id($this->getId());
			$btn_off->setType('action');
			$btn_off->setSubType('other');
			$btn_off->setLogicalId('btn_off');
			$btn_off->setEventOnly(1);
			$btn_off->save();
		}
        $commute = $this->getCmd(null, 'commute');
        if ( ! is_object($commute) ) {
            $commute = new ipx800_relaiCmd();
			$commute->setName('Commute');
			$commute->setEqLogic_id($this->getId());
			$commute->setType('action');
			$commute->setSubType('other');
			$commute->setLogicalId('commute');
			$commute->setEventOnly(1);
			$commute->save();
		}
        $impulsion = $this->getCmd(null, 'impulsion');
        if ( ! is_object($impulsion) ) {
            $impulsion = new ipx800_relaiCmd();
			$impulsion->setName('Impulsion');
			$impulsion->setEqLogic_id($this->getId());
			$impulsion->setType('action');
			$impulsion->setSubType('other');
			$impulsion->setLogicalId('impulsion');
			$impulsion->setEventOnly(1);
			$impulsion->save();
		}
	}

	public function postUpdate()
	{
        $switch = $this->getCmd(null, 'switch');
        if ( is_object($switch) ) {
			$switch->remove();
		}
        $impulsion = $this->getCmd(null, 'impulsion');
        if ( ! is_object($impulsion) ) {
            $impulsion = new ipx800_relaiCmd();
			$impulsion->setName('Impulsion');
			$impulsion->setEqLogic_id($this->getId());
			$impulsion->setType('action');
			$impulsion->setSubType('other');
			$impulsion->setLogicalId('impulsion');
			$impulsion->setEventOnly(1);
			$impulsion->save();
		}
        $state = $this->getCmd(null, 'etat');
        if ( is_object($state) ) {
			$state->setLogicalId('state');
			$state->save();
		}
        $state_old = $this->getCmd(null, 'state');
        if ( is_object($state_old) && get_class ($state_old) != "ipx800_relaiCmd" ) {
            $state = new ipx800_relaiCmd();
			$state->setName($state_old->getName());
			$state->setEqLogic_id($this->getId());
			$state->setType('info');
			$state->setSubType('binary');
			$state->setLogicalId('state');
			$state->setEventOnly(1);
			$state->setIsHistorized($state_old->getIsHistorized());
			$state->setIsVisible($state_old->getIsVisible());
			$state->save();
			$state_old->remove();
		}
        $btn_on_old = $this->getCmd(null, 'btn_on');
        if ( is_object($btn_on_old) && get_class ($btn_on_old) != "ipx800_relaiCmd" ) {
            $btn_on = new ipx800_relaiCmd();
			$btn_on->setName($btn_on_old->getName());
			$btn_on->setEqLogic_id($this->getId());
			$btn_on->setType('action');
			$btn_on->setSubType('other');
			$btn_on->setLogicalId('btn_on');
			$btn_on->setEventOnly(1);
			$btn_on->setIsHistorized($btn_on_old->getIsHistorized());
			$btn_on->setIsVisible($btn_on_old->getIsVisible());
			$btn_on->save();
			$btn_on_old->remove();
		}
        $btn_off_old = $this->getCmd(null, 'btn_off');
        if ( is_object($btn_off_old) && get_class ($btn_off_old) != "ipx800_relaiCmd" ) {
            $btn_off = new ipx800_relaiCmd();
			$btn_off->setName($btn_off_old->getName());
			$btn_off->setEqLogic_id($this->getId());
			$btn_off->setType('action');
			$btn_off->setSubType('other');
			$btn_off->setLogicalId('btn_off');
			$btn_off->setEventOnly(1);
			$btn_off->setIsHistorized($btn_off_old->getIsHistorized());
			$btn_off->setIsVisible($btn_off_old->getIsVisible());
			$btn_off->save();
			$btn_off_old->remove();
		}
        $commute_old = $this->getCmd(null, 'commute');
        if ( is_object($commute_old) && get_class ($commute_old) != "ipx800_relaiCmd" ) {
            $commute = new ipx800_relaiCmd();
			$commute->setName($commute_old->getName());
			$commute->setEqLogic_id($this->getId());
			$commute->setType('action');
			$commute->setSubType('other');
			$commute->setLogicalId('commute');
			$commute->setEventOnly(1);
			$commute->setIsHistorized($commute_old->getIsHistorized());
			$commute->setIsVisible($commute_old->getIsVisible());
			$commute->save();
			$commute_old->remove();
		}
	}

	public function preInsert()
	{
		$gceid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2);
		$this->setEqType_name('ipx800_relai');
		$this->setIsEnable(0);
		$this->setIsVisible(0);
	}

    public static function event() {
        $cmd = ipx800_relaiCmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception('Commande ID virtuel inconnu : ' . init('id'));
        }
		if ($cmd->execCmd(null, 2) != $cmd->formatValue(init('value'))) {
			$cmd->setCollectDate('');
			$cmd->event(init('value'));
		}
    }

	public function configPush($url_serveur, $ipjeedom, $pathjeedom) {
        $cmd = $this->getCmd(null, 'state');
		$gceid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2);
		$url_serveur .= 'protect/settings/push2.htm?channel='.($gceid+32);
		$url = $url_serveur .'&server='.$ipjeedom.'&port=80&pass=&enph=1';
		log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
		$result = file_get_contents($url);
		if ( $result === false ) {
			log::add('ipx800','error',__('L\'ipx ne repond pas.',__FILE__)." get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
			throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
		}
		$url = $url_serveur .'&cmd1='.urlencode($pathjeedom.'core/api/jeeApi.php?api='.config::byKey('api').'&type=ipx800_relai&id='.$cmd->getId().'&value=1');
		log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
		$result = file_get_contents($url);
		if ( $result === false ) {
			log::add('ipx800','error',__('L\'ipx ne repond pas.',__FILE__)." get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
			throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
		}
		$url = $url_serveur .'&cmd2='.urlencode($pathjeedom.'core/api/jeeApi.php?api='.config::byKey('api').'&type=ipx800_relai&id='.$cmd->getId().'&value=0');
		log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
		$result = file_get_contents($url);
		if ( $result === false ) {
			log::add('ipx800','error',__('L\'ipx ne repond pas.',__FILE__)." get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
			throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
		}
	}

    public function getLinkToConfiguration() {
        return 'index.php?v=d&p=ipx800&m=ipx800&id=' . $this->getId();
    }
    /*     * **********************Getteur Setteur*************************** */
}

class ipx800_relaiCmd extends cmd 
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
			$url .= 'preset.htm?set'.($gceid+1).'=1';
		else if ( $this->getLogicalId() == 'btn_off' )
			$url .= 'preset.htm?set'.($gceid+1).'=0';
		else if ( $this->getLogicalId() == 'impulsion' )
			$url .= 'preset.htm?RLY'.($gceid+1).'=1';
		else if ( $this->getLogicalId() == 'commute' )
			$url .= 'leds.cgi?led='.$gceid;
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
    /*     * **********************Getteur Setteur*************************** */
}
?>
