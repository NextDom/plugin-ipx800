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

include_file('core', 'ipx800_analogique', 'class', 'ipx800');
include_file('core', 'ipx800_relai', 'class', 'ipx800');
include_file('core', 'ipx800_bouton', 'class', 'ipx800');
include_file('core', 'ipx800_compteur', 'class', 'ipx800');

class ipx800 extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */

	public static function cron() {
		log::add('ipx800','debug','cron start');
		foreach (self::byType('ipx800') as $eqLogic) {
			$eqLogic->pull();
		}
		log::add('ipx800','debug','cron stop');
	}

	public function getUrl() {
		$url = 'http://';
		if ( $this->getConfiguration('username') != '' )
		{
			$url .= $this->getConfiguration('username').':'.$this->getConfiguration('password').'@';
		} 
		$url .= $this->getConfiguration('ip');
		if ( $this->getConfiguration('port') != '' )
		{
			$url .= ':'.$this->getConfiguration('port');
		}
		return $url."/";
	}

	public function preUpdate()
	{
		if ( $this->getIsEnable() )
		{
			log::add('ipx800','debug','get '.preg_replace("/:[^:]*@/", ":XXXX@", $this->getUrl()). 'status.xml');
			$this->xmlstatus = simplexml_load_file($this->getUrl(). 'status.xml');
			if ( $this->xmlstatus === false )
				throw new Exception(__('L\'ipx800 ne repond pas.',__FILE__));
		}
	}

	public function preInsert()
	{
		$this->setIsVisible(0);
	}

	public function postInsert()
	{
		$ipx800Cmd = $this->getCmd(null, 'updatetime');
		if ( ! is_object($ipx800Cmd)) {
			$ipx800Cmd = new ipx800Cmd();
			$ipx800Cmd->setName('Dernier refresh');
			$ipx800Cmd->setEqLogic_id($this->getId());
			$ipx800Cmd->setLogicalId('updatetime');
			$ipx800Cmd->setUnite('');
			$ipx800Cmd->setType('info');
			$ipx800Cmd->setSubType('string');
			$ipx800Cmd->setIsHistorized(0);
			$ipx800Cmd->setEventOnly(1);
			$ipx800Cmd->save();		
		}

		$cmd = $this->getCmd(null, 'status');
		if ( ! is_object($cmd) ) {
			$cmd = new ipx800Cmd();
			$cmd->setName('Etat');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('info');
			$cmd->setSubType('binary');
			$cmd->setLogicalId('status');
			$cmd->setIsVisible(1);
			$cmd->setEventOnly(1);
			$cmd->save();
		}
        $all_on = $this->getCmd(null, 'all_on');
        if ( ! is_object($all_on) ) {
            $all_on = new ipx800Cmd();
			$all_on->setName('All On');
			$all_on->setEqLogic_id($this->getId());
			$all_on->setType('action');
			$all_on->setSubType('other');
			$all_on->setLogicalId('all_on');
			$all_on->setEventOnly(1);
			$all_on->save();
		}
        $all_off = $this->getCmd(null, 'all_off');
        if ( ! is_object($all_off) ) {
            $all_off = new ipx800Cmd();
			$all_off->setName('All Off');
			$all_off->setEqLogic_id($this->getId());
			$all_off->setType('action');
			$all_off->setSubType('other');
			$all_off->setLogicalId('all_off');
			$all_off->setEventOnly(1);
			$all_off->save();
		}
        $reboot = $this->getCmd(null, 'reboot');
        if ( ! is_object($reboot) ) {
            $reboot = new ipx800Cmd();
			$reboot->setName('Reboot');
			$reboot->setEqLogic_id($this->getId());
			$reboot->setType('action');
			$reboot->setSubType('other');
			$reboot->setLogicalId('reboot');
			$reboot->setEventOnly(1);
			$reboot->setIsVisible(0);
			$reboot->save();
		}
		for ($compteurId = 0; $compteurId <= 15; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_A".$compteurId, 'ipx800_analogique')) ) {
				log::add('ipx800','debug','Creation analogique : '.$this->getId().'_A'.$compteurId);
				$eqLogic = new ipx800_analogique();
				$eqLogic->setLogicalId($this->getId().'_A'.$compteurId);
				$eqLogic->setName('Analogique ' . ($compteurId+1));
				$eqLogic->save();
			}
		}
		for ($compteurId = 0; $compteurId <= 31; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_R".$compteurId, 'ipx800_relai')) ) {
				log::add('ipx800','debug','Creation relai : '.$this->getId().'_R'.$compteurId);
				$eqLogic = new ipx800_relai();
				$eqLogic->setLogicalId($this->getId().'_R'.$compteurId);
				$eqLogic->setName('Relai ' . ($compteurId+1));
				$eqLogic->save();
			}
		}
		for ($compteurId = 0; $compteurId <= 31; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_B".$compteurId, 'ipx800_bouton')) ) {
				log::add('ipx800','debug','Creation bouton : '.$this->getId().'_B'.$compteurId);
				$eqLogic = new ipx800_bouton();
				$eqLogic->setLogicalId($this->getId().'_B'.$compteurId);
				$eqLogic->setName('Bouton ' . ($compteurId+1));
				$eqLogic->save();
			}
		}
		for ($compteurId = 0; $compteurId <= 7; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_C".$compteurId, 'ipx800_compteur')) ) {
				log::add('ipx800','debug','Creation compteur : '.$this->getId().'_C'.$compteurId);
				$eqLogic = new ipx800_compteur();
				$eqLogic->setLogicalId($this->getId().'_C'.$compteurId);
				$eqLogic->setName('Compteur ' . ($compteurId+1));
				$eqLogic->save();
			}
		}
	}

	public function postUpdate()
	{
		for ($compteurId = 0; $compteurId <= 15; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_A".$compteurId, 'ipx800_analogique')) ) {
				log::add('ipx800','debug','Creation analogique : '.$this->getId().'_A'.$compteurId);
				$eqLogic = new ipx800_analogique();
				$eqLogic->setLogicalId($this->getId().'_A'.$compteurId);
				$eqLogic->setName('Analogique ' . ($compteurId+1));
				$eqLogic->save();
			}
		}
		for ($compteurId = 0; $compteurId <= 31; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_R".$compteurId, 'ipx800_relai')) ) {
				log::add('ipx800','debug','Creation relai : '.$this->getId().'_R'.$compteurId);
				$eqLogic = new ipx800_relai();
				$eqLogic->setLogicalId($this->getId().'_R'.$compteurId);
				$eqLogic->setName('Relai ' . ($compteurId+1));
				$eqLogic->save();
			}
		}
		for ($compteurId = 0; $compteurId <= 31; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_B".$compteurId, 'ipx800_bouton')) ) {
				log::add('ipx800','debug','Creation bouton : '.$this->getId().'_B'.$compteurId);
				$eqLogic = new ipx800_bouton();
				$eqLogic->setLogicalId($this->getId().'_B'.$compteurId);
				$eqLogic->setName('Bouton ' . ($compteurId+1));
				$eqLogic->save();
			}
		}
		for ($compteurId = 0; $compteurId <= 7; $compteurId++) {
			if ( ! is_object(self::byLogicalId($this->getId()."_C".$compteurId, 'ipx800_compteur')) ) {
				log::add('ipx800','debug','Creation compteur : '.$this->getId().'_C'.$compteurId);
				$eqLogic = new ipx800_compteur();
				$eqLogic->setLogicalId($this->getId().'_C'.$compteurId);
				$eqLogic->setName('Compteur ' . ($compteurId+1));
				$eqLogic->save();
			}
		}

		$cmd = $this->getCmd(null, 'status');
		if ( ! is_object($cmd) ) {
			$cmd = new ipx800Cmd();
			$cmd->setName('Etat');
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('info');
			$cmd->setSubType('binary');
			$cmd->setLogicalId('status');
			$cmd->setIsVisible(1);
			$cmd->setEventOnly(1);
			$cmd->save();
		}
        $reboot = $this->getCmd(null, 'reboot');
        if ( ! is_object($reboot) ) {
            $reboot = new ipx800Cmd();
			$reboot->setName('Reboot');
			$reboot->setEqLogic_id($this->getId());
			$reboot->setType('action');
			$reboot->setSubType('other');
			$reboot->setLogicalId('reboot');
			$reboot->setIsVisible(0);
			$reboot->setEventOnly(1);
			$reboot->save();
		}
		$ipx800Cmd = $this->getCmd(null, 'updatetime');
		if ( ! is_object($ipx800Cmd)) {
			$ipx800Cmd = new ipx800Cmd();
			$ipx800Cmd->setName('Dernier refresh');
			$ipx800Cmd->setEqLogic_id($this->getId());
			$ipx800Cmd->setLogicalId('updatetime');
			$ipx800Cmd->setUnite('');
			$ipx800Cmd->setType('info');
			$ipx800Cmd->setSubType('string');
			$ipx800Cmd->setIsHistorized(0);
			$ipx800Cmd->setEventOnly(1);
			$ipx800Cmd->save();		
		}

	}

	public function preRemove()
	{
		foreach (self::byType('ipx800_compteur') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('ipx800','debug','Suppression compteur : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
		foreach (self::byType('ipx800_analogique') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('ipx800','debug','Suppression analogique : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
		foreach (self::byType('ipx800_relai') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('ipx800','debug','Suppression relai : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
		foreach (self::byType('ipx800_bouton') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				log::add('ipx800','debug','Suppression bouton : '.$eqLogic->getName());
				$eqLogic->remove();
			}
		}
	}

	public function configPush() {
		$pathjeedom = preg_replace("/plugins.*$/", "", $_SERVER['PHP_SELF']);
		if ( substr($pathjeedom, 0, 1) != "/" ) {
			$pathjeedom = "/".$pathjeedom;
		}
		if ( substr($pathjeedom, -1) != "/" ) {
			$pathjeedom = $pathjeedom."/";
		}
		if ( $this->getIsEnable() ) {
			log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $this->getUrl()));
			foreach (explode(',', init('eqLogicPush_id')) as $_eqLogic_id) {
				$eqLogic = eqLogic::byId($_eqLogic_id);
				if (!is_object($eqLogic)) {
					throw new Exception(__('Impossible de trouver l\'équipement : ', __FILE__) . $_eqLogic_id);
				}
				if ( method_exists($eqLogic, "configPush" ) ) {
					$eqLogic->configPush($this->getUrl(), $pathjeedom);
				}
			}
		}
	}

	public function event() {
		foreach (eqLogic::byType('ipx800') as $eqLogic) {
			if ( $eqLogic->getId() == init('id') ) {
				$eqLogic->pull();
			}
		}
	}

	public function pull() {
		if ( $this->getIsEnable() ) {
			log::add('ipx800','debug','pull '.$this->getName());
			$statuscmd = $this->getCmd(null, 'status');
			$url = $this->getUrl();
			log::add('ipx800','debug','get '.preg_replace("/:[^:]*@/", ":XXXX@", $url).'globalstatus.xml');
			$this->xmlstatus = simplexml_load_file($url. 'globalstatus.xml');
			$count = 0;
			while ( $this->xmlstatus === false && $count < 3 ) {
				log::add('ipx800','debug','reget '.preg_replace("/:[^:]*@/", ":XXXX@", $url).'globalstatus.xml');
				$this->xmlstatus = simplexml_load_file($url. 'globalstatus.xml');
				$count++;
			}
			if ( $this->xmlstatus === false ) {
				if ($statuscmd->execCmd() != 0) {
					$statuscmd->setCollectDate('');
					$statuscmd->event(0);
				}
				log::add('ipx800','error',__('L\'ipx ne repond pas.',__FILE__)." ".$eqLogic->getName()." get ".preg_replace("/:[^:]*@/", ":XXXX@", $url). 'globalstatus.xml');
				return false;
			}
			if ($statuscmd->execCmd() != 1) {
				$statuscmd->setCollectDate('');
				$statuscmd->event(1);
			}
			$eqLogic_cmd = $this->getCmd(null, 'updatetime');
			$eqLogic_cmd->event(time());
			foreach (self::byType('ipx800_relai') as $eqLogicRelai) {
				if ( $eqLogicRelai->getIsEnable() && substr($eqLogicRelai->getLogicalId(), 0, strpos($eqLogicRelai->getLogicalId(),"_")) == $this->getId() ) {
					$gceid = substr($eqLogicRelai->getLogicalId(), strpos($eqLogicRelai->getLogicalId(),"_")+2);
					$xpathModele = '//led'.$gceid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$eqLogic_cmd = $eqLogicRelai->getCmd(null, 'state');
						if ($eqLogic_cmd->execCmd(null, 2) != $eqLogic_cmd->formatValue($status[0])) {
							log::add('ipx800','debug',"Change state off ".$eqLogicRelai->getName());
							$eqLogic_cmd->setCollectDate('');
							$eqLogic_cmd->event($status[0]);
						}
					}
				}
			}
			foreach (self::byType('ipx800_bouton') as $eqLogicBouton) {
				if ( $eqLogicBouton->getIsEnable() && substr($eqLogicBouton->getLogicalId(), 0, strpos($eqLogicBouton->getLogicalId(),"_")) == $this->getId() ) {
					$gceid = substr($eqLogicBouton->getLogicalId(), strpos($eqLogicBouton->getLogicalId(),"_")+2);
					$xpathModele = '//btn'.$gceid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$eqLogic_cmd = $eqLogicBouton->getCmd(null, 'state');
						if ($eqLogic_cmd->execCmd(null, 2) != $eqLogic_cmd->formatValue($status[0])) {
							log::add('ipx800','debug',"Change state off ".$eqLogicBouton->getName());
							$eqLogic_cmd->setCollectDate('');
							$eqLogic_cmd->event($status[0]);
						}
					}
				}
			}
			foreach (self::byType('ipx800_analogique') as $eqLogicAnalogique) {
				if ( $eqLogicAnalogique->getIsEnable() && substr($eqLogicAnalogique->getLogicalId(), 0, strpos($eqLogicAnalogique->getLogicalId(),"_")) == $this->getId() ) {
					$gceid = substr($eqLogicAnalogique->getLogicalId(), strpos($eqLogicAnalogique->getLogicalId(),"_")+2);
					$xpathModele = '//analog'.$gceid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$eqLogic_cmd = $eqLogicAnalogique->getCmd(null, 'brut');
						if ($eqLogic_cmd->execCmd(null, 2) != $eqLogic_cmd->formatValue($status[0])) {
							log::add('ipx800','debug',"Change brut off ".$eqLogicAnalogique->getName());
							$eqLogic_cmd->setCollectDate('');
							$eqLogic_cmd->event($status[0]);
							$eqLogic_cmd = $eqLogicAnalogique->getCmd(null, 'reel');
							$eqLogic_cmd->event($eqLogic_cmd->execute());
						}
					}
				}
			}
			foreach (self::byType('ipx800_compteur') as $eqLogicCompteur) {
				if ( $eqLogicCompteur->getIsEnable() && substr($eqLogicCompteur->getLogicalId(), 0, strpos($eqLogicCompteur->getLogicalId(),"_")) == $this->getId() ) {
					$gceid = substr($eqLogicCompteur->getLogicalId(), strpos($eqLogicCompteur->getLogicalId(),"_")+2);
					$xpathModele = '//count'.$gceid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$nbimpulsion_cmd = $eqLogicCompteur->getCmd(null, 'nbimpulsion');
						$nbimpulsion = $nbimpulsion_cmd->execCmd(null, 2);
						$nbimpulsionminute_cmd = $eqLogicCompteur->getCmd(null, 'nbimpulsionminute');
						if ($nbimpulsion != $status[0]) {
							log::add('ipx800','debug',"Change nbimpulsion off ".$eqLogicCompteur->getName());
							if ( $nbimpulsion_cmd->getCollectDate() == '' ) {
								log::add('ipx800','debug',"Change nbimpulsionminute 0");
								$nbimpulsionminute = 0;
							} else {
								if ( $status[0] > $nbimpulsion ) {
									log::add('ipx800','debug',"Change nbimpulsionminute round ((".$status[0]." - ".$nbimpulsion.")/(".time()." - strtotime(".$nbimpulsion_cmd->getCollectDate()."))*60, 6) = ".round (($status[0] - $nbimpulsion)/(time() - strtotime($nbimpulsion_cmd->getCollectDate()))*60, 6));
									$nbimpulsionminute = round (($status[0] - $nbimpulsion)/(time() - strtotime($nbimpulsion_cmd->getCollectDate()))*60, 6);
								} else {
									log::add('ipx800','debug',"Change nbimpulsionminute round (".$status[0]."/(".time()." - strtotime(".$nbimpulsionminute_cmd->getCollectDate().")*60), 6) = ".round ($status[0]/(time() - strtotime($nbimpulsionminute_cmd->getCollectDate())*60), 6));
									$nbimpulsionminute = round ($status[0]/(time() - strtotime($nbimpulsionminute_cmd->getCollectDate())*60), 6);
								}
							}
							$nbimpulsionminute_cmd->setCollectDate(date('Y-m-d H:i:s'));
							$nbimpulsionminute_cmd->event($nbimpulsionminute);
						} else {
							$nbimpulsionminute_cmd->setCollectDate(date('Y-m-d H:i:s'));
							$nbimpulsionminute_cmd->event(0);
						}
						$nbimpulsion_cmd->setCollectDate(date('Y-m-d H:i:s'));
						$nbimpulsion_cmd->event($status[0]);
					}
				}
			}
			log::add('ipx800','debug','pull end '.$this->getName());
		}
	}
    /*     * **********************Getteur Setteur*************************** */
}

class ipx800Cmd extends cmd 
{
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*     * **********************Getteur Setteur*************************** */
    public function execute($_options = null) {
		$eqLogic = $this->getEqLogic();
        if (!is_object($eqLogic) || $eqLogic->getIsEnable() != 1) {
            throw new Exception(__('Equipement desactivé impossible d\éxecuter la commande : ' . $this->getHumanName(), __FILE__));
        }
		$url = $eqLogic->getUrl();
			
		if ( $this->getLogicalId() == 'all_on' )
		{
			$url .= 'preset.htm';
			for ($gceid = 0; $gceid <= 7; $gceid++) {
				$data['led'.($gceid+1)] =1;
			}
		}
		else if ( $this->getLogicalId() == 'all_off' )
		{
			$url .= 'preset.htm';
			for ($gceid = 0; $gceid <= 7; $gceid++) {
				$data['led'.($gceid+1)] =0;
			}
		}
		else if ( $this->getLogicalId() == 'reboot' )
		{
			$url .= "protect/settings/reboot.htm";
		}
		else
			return false;
		log::add('ipx800','debug','get '.preg_replace("/:[^:]*@/", ":XXXX@", $url).'?'.http_build_query($data));
		$result = file_get_contents($url.'?'.http_build_query($data));
		$count = 0;
		while ( $result === false )
		{
			$result = file_get_contents($url.'?'.http_build_query($data));
			if ( $count < 3 ) {
				log::add('ipx800','error',__('L\'ipx ne repond pas.',__FILE__)." ".$eqLogic->getName()." get ".preg_replace("/:[^:]*@/", ":XXXX@", $url)."?".http_build_query($data));
				throw new Exception(__('L\'ipx ne repond pas.',__FILE__)." ".$eqLogic->getName());
			}
			$count ++;
		}
        return false;
    }
}
?>
