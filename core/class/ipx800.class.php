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

class ipx800 extends eqLogic {
    /*     * *************************Attributs****************************** */

    /*     * ***********************Methode static*************************** */

	public static function pull() {
		log::add('ipx800','debug','cron start');
		foreach (eqLogic::byTypeAndSearhConfiguration('ipx800', '"type":"carte"') as $eqLogic) {
			$eqLogic->scan();
		}
		log::add('ipx800','debug','cron stop');
	}

	public function getUrl() {
		if ( $this->getConfiguration('type', '') == 'carte' )
		{
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
		else
		{
			$IPXeqLogic = eqLogic::byId(substr ($this->getLogicalId(), 0, strpos($this->getLogicalId(),"_")));
			return $IPXeqLogic->getUrl();
		}
	}

	public function preInsert()
	{
		switch ($this->getConfiguration('type', '')) {
			case "carte":
				$this->setIsVisible(0);
				break;
			case "bouton":
				$this->setIsEnable(0);
				$this->setIsVisible(0);
				break;
			case "relai":
				$this->setIsEnable(0);
				$this->setIsVisible(0);
				break;
			case "compteur":
				$this->setIsEnable(0);
				$this->setIsVisible(0);
				break;
			case "analogique":
				$this->setIsEnable(0);
				$this->setIsVisible(0);
				break;
		}
	}

	public function postInsert()
	{
		switch ($this->getConfiguration('type', '')) {
			case "carte":
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
					$ipx800Cmd->setDisplay('generic_type','GENERIC_INFO');
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
					$cmd->setDisplay('generic_type','GENERIC_INFO');
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
					$all_on->setDisplay('generic_type','GENERIC_ACTION');
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
					$all_off->setDisplay('generic_type','GENERIC_ACTION');
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
					$reboot->setDisplay('generic_type','GENERIC_ACTION');
					$reboot->save();
				}
				for ($compteurId = 0; $compteurId <= 15; $compteurId++) {
					if ( ! is_object(self::byLogicalId($this->getId()."_A".$compteurId, 'ipx800')) ) {
						log::add('ipx800','debug','Creation analogique : '.$this->getId().'_A'.$compteurId);
						$eqLogic = new ipx800();
						$eqLogic->setConfiguration('type', 'analogique');
						$eqLogic->setLogicalId($this->getId().'_A'.$compteurId);
						$eqLogic->setName('Analogique ' . ($compteurId+1));
						$eqLogic->save();
					}
				}
				for ($compteurId = 0; $compteurId <= 31; $compteurId++) {
					if ( ! is_object(self::byLogicalId($this->getId()."_R".$compteurId, 'ipx800')) ) {
						log::add('ipx800','debug','Creation relai : '.$this->getId().'_R'.$compteurId);
						$eqLogic = new ipx800();
						$eqLogic->setConfiguration('type', 'relai');
						$eqLogic->setLogicalId($this->getId().'_R'.$compteurId);
						$eqLogic->setName('Relai ' . ($compteurId+1));
						$eqLogic->save();
					}
				}
				for ($compteurId = 0; $compteurId <= 31; $compteurId++) {
					if ( ! is_object(self::byLogicalId($this->getId()."_B".$compteurId, 'ipx800')) ) {
						log::add('ipx800','debug','Creation bouton : '.$this->getId().'_B'.$compteurId);
						$eqLogic = new ipx800();
						$eqLogic->setConfiguration('type', 'bouton');
						$eqLogic->setLogicalId($this->getId().'_B'.$compteurId);
						$eqLogic->setName('Bouton ' . ($compteurId+1));
						$eqLogic->save();
					}
				}
				for ($compteurId = 0; $compteurId <= 7; $compteurId++) {
					if ( ! is_object(self::byLogicalId($this->getId()."_C".$compteurId, 'ipx800')) ) {
						log::add('ipx800','debug','Creation compteur : '.$this->getId().'_C'.$compteurId);
						$eqLogic = new ipx800();
						$eqLogic->setConfiguration('type', 'compteur');
						$eqLogic->setLogicalId($this->getId().'_C'.$compteurId);
						$eqLogic->setName('Compteur ' . ($compteurId+1));
						$eqLogic->save();
					}
				}
				break;
			case "bouton":
				$state = $this->getCmd(null, 'state');
				if ( ! is_object($state) ) {
					$state = new ipx800Cmd();
					$state->setName('Etat');
					$state->setEqLogic_id($this->getId());
					$state->setType('info');
					$state->setSubType('binary');
					$state->setLogicalId('state');
					$state->setEventOnly(1);
					$state->setDisplay('generic_type','LIGHT_STATE');
					$state->setTemplate('dashboard', 'light');
					$state->setTemplate('mobile', 'light');
					$state->save();
				}
				$btn_on = $this->getCmd(null, 'btn_on');
				if ( ! is_object($btn_on) ) {
					$btn_on = new ipx800Cmd();
					$btn_on->setName('On');
					$btn_on->setEqLogic_id($this->getId());
					$btn_on->setType('action');
					$btn_on->setSubType('other');
					$btn_on->setLogicalId('btn_on');
					$btn_on->setEventOnly(1);
					$btn_on->setIsVisible(0);
					$btn_on->setDisplay('generic_type','LIGHT_ON');
					$btn_on->save();
				}
				$btn_off = $this->getCmd(null, 'btn_off');
				if ( ! is_object($btn_off) ) {
					$btn_off = new ipx800Cmd();
					$btn_off->setName('Off');
					$btn_off->setEqLogic_id($this->getId());
					$btn_off->setType('action');
					$btn_off->setSubType('other');
					$btn_off->setLogicalId('btn_off');
					$btn_off->setEventOnly(1);
					$btn_off->setIsVisible(0);
					$btn_off->setDisplay('generic_type','LIGHT_OFF');
					$btn_off->save();
				}
				break;
			case "relai":
				$state = $this->getCmd(null, 'state');
				if ( ! is_object($state) ) {
					$state = new ipx800Cmd();
					$state->setName('Etat');
					$state->setEqLogic_id($this->getId());
					$state->setType('info');
					$state->setSubType('binary');
					$state->setLogicalId('state');
					$state->setEventOnly(1);
					$state->setDisplay('generic_type','LIGHT_STATE');
					$state->setTemplate('dashboard', 'light');
					$state->setTemplate('mobile', 'light');      
					$state->save();
				}
				$btn_on = $this->getCmd(null, 'btn_on');
				if ( ! is_object($btn_on) ) {
					$btn_on = new ipx800Cmd();
					$btn_on->setName('On');
					$btn_on->setEqLogic_id($this->getId());
					$btn_on->setType('action');
					$btn_on->setSubType('other');
					$btn_on->setLogicalId('btn_on');
					$btn_on->setEventOnly(1);
					$btn_on->setDisplay('generic_type','LIGHT_ON');
					$btn_on->save();
				}
				$btn_off = $this->getCmd(null, 'btn_off');
				if ( ! is_object($btn_off) ) {
					$btn_off = new ipx800Cmd();
					$btn_off->setName('Off');
					$btn_off->setEqLogic_id($this->getId());
					$btn_off->setType('action');
					$btn_off->setSubType('other');
					$btn_off->setLogicalId('btn_off');
					$btn_off->setEventOnly(1);
					$btn_off->setDisplay('generic_type','LIGHT_OFF');
					$btn_off->save();
				}
				$commute = $this->getCmd(null, 'commute');
				if ( ! is_object($commute) ) {
					$commute = new ipx800Cmd();
					$commute->setName('Commute');
					$commute->setEqLogic_id($this->getId());
					$commute->setType('action');
					$commute->setSubType('other');
					$commute->setLogicalId('commute');
					$commute->setEventOnly(1);
					$commute->setDisplay('generic_type','LIGHT_TOGGLE');
					$commute->save();
				}
				$impulsion = $this->getCmd(null, 'impulsion');
				if ( ! is_object($impulsion) ) {
					$impulsion = new ipx800Cmd();
					$impulsion->setName('Impulsion');
					$impulsion->setEqLogic_id($this->getId());
					$impulsion->setType('action');
					$impulsion->setSubType('other');
					$impulsion->setLogicalId('impulsion');
					$impulsion->setEventOnly(1);
					$impulsion->setDisplay('generic_type','GENERIC_ACTION');
					$impulsion->save();
				}
				break;
			case "compteur":
				$nbimpulsion = $this->getCmd(null, 'nbimpulsion');
				if ( ! is_object($nbimpulsion) ) {
					$nbimpulsion = new ipx800Cmd();
					$nbimpulsion->setName('Nombre d impulsion');
					$nbimpulsion->setEqLogic_id($this->getId());
					$nbimpulsion->setType('info');
					$nbimpulsion->setSubType('numeric');
					$nbimpulsion->setLogicalId('nbimpulsion');
					$nbimpulsion->setEventOnly(1);
					$nbimpulsion->setDisplay('generic_type','GENERIC_INFO');
					$nbimpulsion->save();
				}
				$nbimpulsionminute = $this->getCmd(null, 'nbimpulsionminute');
				if ( ! is_object($nbimpulsionminute) ) {
					$nbimpulsionminute = new ipx800Cmd();
					$nbimpulsionminute->setName('Nombre d impulsion par minute');
					$nbimpulsionminute->setEqLogic_id($this->getId());
					$nbimpulsionminute->setType('info');
					$nbimpulsionminute->setSubType('numeric');
					$nbimpulsionminute->setLogicalId('nbimpulsionminute');
					$nbimpulsionminute->setUnite("Imp/min");
					$nbimpulsionminute->setEventOnly(1);
					$nbimpulsionminute->setConfiguration('calcul', '#brut#');
					$nbimpulsionminute->setDisplay('generic_type','GENERIC_INFO');
					$nbimpulsionminute->save();
				}
				break;
			case "analogique":
				$brut = $this->getCmd(null, 'brut');
				if ( ! is_object($brut) ) {
					$brut = new ipx800Cmd();
					$brut->setName('Brut');
					$brut->setEqLogic_id($this->getId());
					$brut->setType('info');
					$brut->setSubType('numeric');
					$brut->setLogicalId('brut');
					$brut->setIsVisible(false);
					$brut->setEventOnly(1);
					$brut->setDisplay('generic_type','GENERIC_INFO');
					$brut->save();
				}
				$reel = $this->getCmd(null, 'reel');
				if ( ! is_object($reel) ) {
					$reel = new ipx800Cmd();
					$reel->setName('Réel');
					$reel->setEqLogic_id($this->getId());
					$reel->setType('info');
					$reel->setSubType('numeric');
					$reel->setLogicalId('reel');
					$reel->setEventOnly(1);
					$reel->setConfiguration('calcul', '#' . $brut->getId() . '#');
					$reel->setDisplay('generic_type','GENERIC_INFO');
					$reel->save();
				}
				break;
		}
	}

	public function preUpdate()
	{
		switch ($this->getConfiguration('type', '')) {
			case "carte":
				if ( $this->getIsEnable() )
				{
					log::add('ipx800','debug','get '.preg_replace("/:[^:]*@/", ":XXXX@", $this->getUrl()). 'status.xml');
					$this->xmlstatus = @simplexml_load_file($this->getUrl(). 'status.xml');
					if ( $this->xmlstatus === false )
						throw new Exception(__('L\'ipx800 ne repond pas.',__FILE__));
				}
				break;
			case "bouton":
				$nbimpulsion = $this->getCmd(null, 'nbimpulsion');
				if ( is_object($nbimpulsion) ) {
					$nbimpulsion->remove();
				}
				$state = $this->getCmd(null, 'etat');
				if ( is_object($state) ) {
					$state->setLogicalId('state');
					$state->save();
				}
				$state = $this->getCmd(null, 'state');
				if ( $state->getDisplay('generic_type') == "" )
				{
					$state->setDisplay('generic_type','LIGHT_STATE');
					$state->save();
				}			
				if ( $state->getTemplate('dashboard') == "" )
				{
					$state->setTemplate('dashboard', 'light');
					$state->save();
				}			
				if ( $state->getTemplate('mobile') == "" )
				{
					$state->setTemplate('mobile', 'light');
					$state->save();
				}			
				$btn_on = $this->getCmd(null, 'btn_on');
				if ( ! is_object($btn_on) ) {
					$btn_on = new ipx800Cmd();
					$btn_on->setName('On');
					$btn_on->setEqLogic_id($this->getId());
					$btn_on->setType('action');
					$btn_on->setSubType('other');
					$btn_on->setLogicalId('btn_on');
					$btn_on->setEventOnly(1);
					$btn_on->setIsVisible(0);
					$btn_on->setDisplay('generic_type','LIGHT_ON');
					$btn_on->save();
				}
				else
				{
					if ( $btn_on->getDisplay('generic_type') == "" )
					{
						$btn_on->setDisplay('generic_type','LIGHT_ON');
						$btn_on->save();
					}			
				}
				$btn_off = $this->getCmd(null, 'btn_off');
				if ( ! is_object($btn_off) ) {
					$btn_off = new ipx800Cmd();
					$btn_off->setName('Off');
					$btn_off->setEqLogic_id($this->getId());
					$btn_off->setType('action');
					$btn_off->setSubType('other');
					$btn_off->setLogicalId('btn_off');
					$btn_off->setEventOnly(1);
					$btn_off->setIsVisible(0);
					$btn_off->save();
				}
				else
				{
					if ( $btn_off->getDisplay('generic_type') == "" )
					{
						$btn_off->setDisplay('generic_type','LIGHT_OFF');
						$btn_off->save();
					}			
				}
				break;
			case "relai":
				$switch = $this->getCmd(null, 'switch');
				if ( is_object($switch) ) {
					$switch->remove();
				}
				$impulsion = $this->getCmd(null, 'impulsion');
				if ( ! is_object($impulsion) ) {
					$impulsion = new ipx800Cmd();
					$impulsion->setName('Impulsion');
					$impulsion->setEqLogic_id($this->getId());
					$impulsion->setType('action');
					$impulsion->setSubType('other');
					$impulsion->setLogicalId('impulsion');
					$impulsion->setEventOnly(1);
					$impulsion->setDisplay('generic_type','GENERIC_ACTION');
					$impulsion->save();
				}
				else
				{
					if ( $impulsion->getDisplay('generic_type') == "" )
					{
						$impulsion->setDisplay('generic_type','GENERIC_ACTION');
						$impulsion->save();
					}
				}
				$state = $this->getCmd(null, 'etat');
				if ( is_object($state) ) {
					$state->setLogicalId('state');
					$state->save();
				}
				$state_old = $this->getCmd(null, 'state');
				if ( is_object($state_old) && get_class ($state_old) != "ipx800Cmd" ) {
					$state = new ipx800Cmd();
					$state->setName($state_old->getName());
					$state->setEqLogic_id($this->getId());
					$state->setType('info');
					$state->setSubType('binary');
					$state->setLogicalId('state');
					$state->setEventOnly(1);
					$state->setIsHistorized($state_old->getIsHistorized());
					$state->setIsVisible($state_old->getIsVisible());
					$state->setDisplay('generic_type','LIGHT_STATE');
					$state->setTemplate('dashboard', 'light');
					$state->setTemplate('mobile', 'light');      
					$state->save();
					$state_old->remove();
				}
				elseif ( is_object($state_old) )
				{
					if ( $state_old->getDisplay('generic_type') == "" )
					{
						$state_old->setDisplay('generic_type','LIGHT_STATE');
						$state_old->save();
					}			
					if ( $state_old->getTemplate('dashboard') == "" )
					{
						$state_old->setTemplate('dashboard', 'light');
						$state_old->save();
					}			
					if ( $state_old->getTemplate('mobile') == "" )
					{
						$state_old->setTemplate('mobile', 'light');
						$state_old->save();
					}			
				}
				$btn_on_old = $this->getCmd(null, 'btn_on');
				if ( is_object($btn_on_old) && get_class ($btn_on_old) != "ipx800Cmd" ) {
					$btn_on = new ipx800Cmd();
					$btn_on->setName($btn_on_old->getName());
					$btn_on->setEqLogic_id($this->getId());
					$btn_on->setType('action');
					$btn_on->setSubType('other');
					$btn_on->setLogicalId('btn_on');
					$btn_on->setEventOnly(1);
					$btn_on->setIsHistorized($btn_on_old->getIsHistorized());
					$btn_on->setIsVisible($btn_on_old->getIsVisible());
					$btn_on->setDisplay('generic_type','LIGHT_ON');
					$btn_on->save();
					$btn_on_old->remove();
				}
				elseif ( is_object($btn_on_old) )
				{
					if ( $btn_on_old->getDisplay('generic_type') == "" )
					{
						$btn_on_old->setDisplay('generic_type','LIGHT_ON');
						$btn_on_old->save();
					}			
				}
				$btn_off_old = $this->getCmd(null, 'btn_off');
				if ( is_object($btn_off_old) && get_class ($btn_off_old) != "ipx800Cmd" ) {
					$btn_off = new ipx800Cmd();
					$btn_off->setName($btn_off_old->getName());
					$btn_off->setEqLogic_id($this->getId());
					$btn_off->setType('action');
					$btn_off->setSubType('other');
					$btn_off->setLogicalId('btn_off');
					$btn_off->setEventOnly(1);
					$btn_off->setIsHistorized($btn_off_old->getIsHistorized());
					$btn_off->setIsVisible($btn_off_old->getIsVisible());
					$btn_off->setDisplay('generic_type','LIGHT_OFF');
					$btn_off->save();
					$btn_off_old->remove();
				}
				elseif ( is_object($btn_off_old) )
				{
					if ( $btn_off_old->getDisplay('generic_type') == "" )
					{
						$btn_off_old->setDisplay('generic_type','LIGHT_OFF');
						$btn_off_old->save();
					}			
				}
				$commute_old = $this->getCmd(null, 'commute');
				if ( is_object($commute_old) && get_class ($commute_old) != "ipx800Cmd" ) {
					$commute = new ipx800Cmd();
					$commute->setName($commute_old->getName());
					$commute->setEqLogic_id($this->getId());
					$commute->setType('action');
					$commute->setSubType('other');
					$commute->setLogicalId('commute');
					$commute->setEventOnly(1);
					$commute->setIsHistorized($commute_old->getIsHistorized());
					$commute->setIsVisible($commute_old->getIsVisible());
					$commute->setDisplay('generic_type','LIGHT_TOGGL');
					$commute->save();
					$commute_old->remove();
				}
				elseif ( is_object($commute_old) )
				{
					if ( $commute_old->getDisplay('generic_type') == "" )
					{
						$commute_old->setDisplay('generic_type','LIGHT_TOGGL');
						$commute_old->save();
					}			
				}
				break;
			case "compteur":
				$nbimpulsion = $this->getCmd(null, 'nbimpulsion');
				if ( ! is_object($nbimpulsion) ) {
					$nbimpulsion = new ipx800Cmd();
					$nbimpulsion->setName('Nombre d impulsion');
					$nbimpulsion->setEqLogic_id($this->getId());
					$nbimpulsion->setType('info');
					$nbimpulsion->setSubType('numeric');
					$nbimpulsion->setLogicalId('nbimpulsion');
					$nbimpulsion->setEventOnly(1);
					$nbimpulsion->setDisplay('generic_type','GENERIC_INFO');
					$nbimpulsion->save();
				}
				else
				{
					if ( $nbimpulsion->getDisplay('generic_type') == "" )
					{
						$nbimpulsion->setDisplay('generic_type','GENERIC_INFO');
						$nbimpulsion->save();
					}
				}
				$nbimpulsionminute = $this->getCmd(null, 'nbimpulsionminute');
				if ( ! is_object($nbimpulsionminute) ) {
					$nbimpulsionminute = new ipx800Cmd();
					$nbimpulsionminute->setName('Nombre d impulsion par minute');
					$nbimpulsionminute->setEqLogic_id($this->getId());
					$nbimpulsionminute->setType('info');
					$nbimpulsionminute->setSubType('numeric');
					$nbimpulsionminute->setLogicalId('nbimpulsionminute');
					$nbimpulsionminute->setUnite("Imp/min");
					$nbimpulsionminute->setConfiguration('calcul', '#brut#');
					$nbimpulsionminute->setEventOnly(1);
					$nbimpulsionminute->setDisplay('generic_type','GENERIC_INFO');
					$nbimpulsionminute->save();
				}
				else
				{
					if ( $nbimpulsionminute->getDisplay('generic_type') == "" )
					{
						$nbimpulsionminute->setDisplay('generic_type','GENERIC_INFO');
						$nbimpulsionminute->save();
					}
				}
				break;
			case "analogique":
				$brut = $this->getCmd(null, 'voltage');
				if ( is_object($brut) ) {
					$brut->setLogicalId('brut');
					$brut->save();
				} else {
					$brut = $this->getCmd(null, 'brut');
				}
				$brut = $this->getCmd(null, 'brut');
				if ( ! is_object($brut) ) {
					$brut = new ipx800Cmd();
					$brut->setName('Brut');
					$brut->setEqLogic_id($this->getId());
					$brut->setType('info');
					$brut->setSubType('numeric');
					$brut->setLogicalId('brut');
					$brut->setIsVisible(false);
					$brut->setEventOnly(1);
					$brut->setDisplay('generic_type','GENERIC_INFO');
					$brut->save();
				}
				else
				{
					if ( $brut->getDisplay('generic_type') == "" )
					{
						$brut->setDisplay('generic_type','GENERIC_INFO');
						$brut->save();
					}
				}
				$reel = $this->getCmd(null, 'reel');
				if ( ! is_object($reel) ) {
					$reel = new ipx800Cmd();
					$reel->setName('Réel');
					$reel->setEqLogic_id($this->getId());
					$reel->setType('info');
					$reel->setSubType('numeric');
					$reel->setLogicalId('reel');
					$reel->setEventOnly(1);
					$reel->setConfiguration('calcul', '#' . $brut->getId() . '#');
					$reel->setDisplay('generic_type','GENERIC_INFO');
					$reel->save();
				}
				else
				{
					if ( $reel->getConfiguration('type') == "" )
					{
						switch ($reel->getConfiguration('calcul')) {
							case '#brut# * 0.323':
								$reel->setConfiguration('type', 'LM35Z');
								break;
							case '#brut# * 0.323 - 50':
								$reel->setConfiguration('type', 'T4012');
								break;
							case '#brut# * 0.00323':
								$reel->setConfiguration('type', 'Voltage');
								break;
							case '#brut# * 0.09775':
								$reel->setConfiguration('type', 'SHT-X3L');
								break;
							case '( #brut# * 0.00323 - 1.63 ) / 0.0326':
								$reel->setConfiguration('type', 'SHT-X3T');
								break;
							case '( ( #brut# * 0.00323 / 3.3 ) - 0.1515 ) / 0.00636 / 1.0546':
								$reel->setConfiguration('type', 'SHT-X3H');
								break;
							case '( ( #brut# * 0.00323 ) - 0.25 ) / 0.028':
								$reel->setConfiguration('type', 'TC100');
								break;
							case '#brut# * 0.00646':
								$reel->setConfiguration('type', 'CT20A');
								break;
							case '#brut# * 0.01615':
								$reel->setConfiguration('type', 'CT50A');
								break;
							case '#brut# / 100':
								$reel->setConfiguration('type', 'Ph');
								break;
							default:
								if ( preg_match('!\( \( #brut# \* 0.00323 / 3.3 \) - 0.1515 \) / 0.00636 / \( 1.0546 - \( 0.00216 \* .* \) \)!', $reel->getConfiguration('calcul')) )
									$reel->setConfiguration('type', 'SHT-X3HC');
								else
									$reel->setConfiguration('type', 'Autre');
								break;
						}
					}
					switch ($reel->getConfiguration('type')) {
						case 'LM35Z':
							$reel->setDisplay('generic_type','TEMPERATURE');
							break;
						case 'T4012':
							$reel->setDisplay('generic_type','TEMPERATURE');
							break;
						case 'Voltage':
							$reel->setDisplay('generic_type','VOLTAGE');
							break;
						case 'SHT-X3L':
							$reel->setDisplay('generic_type','BRIGHTNESS');
							break;
						case 'SHT-X3T':
							$reel->setTemplate('dashboard', 'thermometre');
							$reel->setTemplate('mobile', 'thermometre');
							$reel->setDisplay('generic_type','TEMPERATURE');
							break;
						case 'SHT-X3H':
							$reel->setDisplay('generic_type','HUMIDITY');
							break;
						case 'TC100':
							$reel->setTemplate('dashboard', 'thermometre');
							$reel->setTemplate('mobile', 'thermometre');
							$reel->setDisplay('generic_type','TEMPERATURE');
							break;
						case 'CT10A':
							$reel->setDisplay('generic_type','CONSUMPTION');
							break;
						case 'CT20A':
							$reel->setDisplay('generic_type','CONSUMPTION');
							break;
						case 'CT50A':
							$reel->setDisplay('generic_type','CONSUMPTION');
							break;
						case 'Ph':
							$reel->setDisplay('generic_type','GENERIC_INFO');
							break;
						case 'SHT-X3HC':
							$reel->setDisplay('generic_type','HUMIDITY');
							break;
						default:
							$reel->setDisplay('generic_type','GENERIC_INFO');
							break;
					}
					$reel->save();
				}
				break;
		}
	}

	public function postUpdate()
	{
		switch ($this->getConfiguration('type', '')) {
			case "carte":
				for ($compteurId = 0; $compteurId <= 15; $compteurId++) {
					if ( ! is_object(self::byLogicalId($this->getId()."_A".$compteurId, 'ipx800')) ) {
						log::add('ipx800','debug','Creation analogique : '.$this->getId().'_A'.$compteurId);
						$eqLogic = new ipx800();
						$eqLogic->setConfiguration('type', 'analogique');
						$eqLogic->setLogicalId($this->getId().'_A'.$compteurId);
						$eqLogic->setName('Analogique ' . ($compteurId+1));
						$eqLogic->save();
					}
				}
				for ($compteurId = 0; $compteurId <= 31; $compteurId++) {
					if ( ! is_object(self::byLogicalId($this->getId()."_R".$compteurId, 'ipx800')) ) {
						log::add('ipx800','debug','Creation relai : '.$this->getId().'_R'.$compteurId);
						$eqLogic = new ipx800();
						$eqLogic->setConfiguration('type', 'relai');
						$eqLogic->setLogicalId($this->getId().'_R'.$compteurId);
						$eqLogic->setName('Relai ' . ($compteurId+1));
						$eqLogic->save();
					}
				}
				for ($compteurId = 0; $compteurId <= 31; $compteurId++) {
					if ( ! is_object(self::byLogicalId($this->getId()."_B".$compteurId, 'ipx800')) ) {
						log::add('ipx800','debug','Creation bouton : '.$this->getId().'_B'.$compteurId);
						$eqLogic = new ipx800();
						$eqLogic->setConfiguration('type', 'bouton');
						$eqLogic->setLogicalId($this->getId().'_B'.$compteurId);
						$eqLogic->setName('Bouton ' . ($compteurId+1));
						$eqLogic->save();
					}
				}
				for ($compteurId = 0; $compteurId <= 7; $compteurId++) {
					if ( ! is_object(self::byLogicalId($this->getId()."_C".$compteurId, 'ipx800')) ) {
						log::add('ipx800','debug','Creation compteur : '.$this->getId().'_C'.$compteurId);
						$eqLogic = new ipx800();
						$eqLogic->setConfiguration('type', 'compteur');
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
					$cmd->setDisplay('generic_type','GENERIC_INFO');
					$cmd->save();
				}
				else
				{
					if ( $cmd->getDisplay('generic_type') == "" )
					{
						$cmd->setDisplay('generic_type','GENERIC_INFO');
						$cmd->save();
					}
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
					$reboot->setDisplay('generic_type','GENERIC_ACTION');
					$reboot->save();
				}
				else
				{
					if ( $reboot->getDisplay('generic_type') == "" )
					{
						$reboot->setDisplay('generic_type','GENERIC_ACTION');
						$reboot->save();
					}
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
				else
				{
					if ( $ipx800Cmd->getDisplay('generic_type') == "" )
					{
						$ipx800Cmd->setDisplay('generic_type','GENERIC_INFO');
						$ipx800Cmd->save();
					}
				}

				$all_on = $this->getCmd(null, 'all_on');
				if ( ! is_object($all_on)) {
					$all_on = new ipx800Cmd();
					$all_on->setName('All On');
					$all_on->setEqLogic_id($this->getId());
					$all_on->setType('action');
					$all_on->setSubType('other');
					$all_on->setLogicalId('all_on');
					$all_on->setEventOnly(1);
					$all_on->setDisplay('generic_type','GENERIC_ACTION');
					$all_on->save();
				}
				else
				{
					if ( $all_on->getDisplay('generic_type') == "" )
					{
						$all_on->setDisplay('generic_type','GENERIC_ACTION');
						$all_on->save();
					}
				}

				$all_off = $this->getCmd(null, 'all_off');
				if ( ! is_object($all_off)) {
					$all_off = new ipx800Cmd();
					$all_off->setName('All Off');
					$all_off->setEqLogic_id($this->getId());
					$all_off->setType('action');
					$all_off->setSubType('other');
					$all_off->setLogicalId('all_off');
					$all_off->setEventOnly(1);
					$all_off->setDisplay('generic_type','GENERIC_ACTION');
					$all_off->save();
				}
				else
				{
					if ( $all_off->getDisplay('generic_type') == "" )
					{
						$all_off->setDisplay('generic_type','GENERIC_ACTION');
						$all_off->save();
					}
				}
				break;
			case "bouton":
				break;
			case "relai":
				break;
			case "compteur":
				break;
			case "analogique":
				break;
		}
	}

	public function getChildEq()
	{
		$ChildList = array();
		foreach (self::byType('ipx800') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				array_push($ChildList, $eqLogic->getId());
			}
		}
		foreach (self::byType('ipx800') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				array_push($ChildList, $eqLogic->getId());
			}
		}
		foreach (self::byType('ipx800') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				array_push($ChildList, $eqLogic->getId());
			}
		}
		foreach (self::byType('ipx800') as $eqLogic) {
			if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
				array_push($ChildList, $eqLogic->getId());
			}
		}
		return $ChildList;
	}

	public function preRemove()
	{
		switch ($this->getConfiguration('type', '')) {
			case "carte":
				foreach (self::byType('ipx800') as $eqLogic) {
					if ( substr($eqLogic->getLogicalId(), 0, strpos($eqLogic->getLogicalId(),"_")) == $this->getId() ) {
						log::add('ipx800','debug','Suppression : '.$eqLogic->getName());
						$eqLogic->remove();
					}
				}
				break;
			case "bouton":
				break;
			case "temperature":
				break;
			case "relai":
				break;
			case "compteur":
				break;
			case "teleinfo":
				break;
			case "pince":
				break;
			case "analogique":
				break;
		}
	}

	public function configPush($url_serveur = null, $pathjeedom = null, $ipjeedom = null, $portjeeom = null, $seuil_base = null, $seuil_haut = null) {
		if ( ! defined($pathjeedom) )
		{
			if ( config::byKey("internalAddr") == "" || config::byKey("internalPort") == "" )
			{
				throw new Exception(__('L\'adresse IP ou le port local de jeedom ne sont pas définit (Administration => Configuration réseaux => Accès interne).', __FILE__));
			}
			$pathjeedom = config::byKey("internalComplement", "/");
			if ( strlen($pathjeedom) == 0 ) {
				$pathjeedom = "/";
			}
			if ( substr($pathjeedom, 0, 1) != "/" ) {
				$pathjeedom = "/".$pathjeedom;
			}
			if ( substr($pathjeedom, -1) != "/" ) {
				$pathjeedom = $pathjeedom."/";
			}
		}
		switch ($this->getConfiguration('type', '')) {
			case "carte":
				if ( $this->getIsEnable() ) {
					log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $this->getUrl()));
					$liste_seuil_bas = explode(',', init('seuil_bas'));
					$liste_seuil_haut = explode(',', init('seuil_haut'));
					
					foreach (explode(',', init('eqLogicPush_id')) as $_eqLogic_id) {
						$eqLogic = eqLogic::byId($_eqLogic_id);
						if (!is_object($eqLogic)) {
							throw new Exception(__('Impossible de trouver l\'équipement : ', __FILE__) . $_eqLogic_id);
						}
						if ( method_exists($eqLogic, "configPush" ) ) {
							if ( get_class ($eqLogic) == "ipx800" )
							{
								$eqLogic->configPush($this->getUrl(), $pathjeedom, config::byKey("internalAddr"), config::byKey("internalPort"), array_shift($liste_seuil_bas), array_shift($liste_seuil_haut));
							}
							else
							{
								$eqLogic->configPush($this->getUrl(), $pathjeedom, config::byKey("internalAddr"), config::byKey("internalPort"));
							}
						}
					}
				}
				break;
			case "bouton":
				$cmd = $this->getCmd(null, 'state');
				$gceid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2);
				$url_serveur .= 'protect/settings/push1.htm?channel='.$gceid;
				$url = $url_serveur .'&server='.$ipjeedom.'&port='.$portjeeom.'&pass=&enph=1';
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$result = @file_get_contents($url);
				if ( $result === false )
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
				$url = $url_serveur .'&cmd1='.urlencode($pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('ipx800').'&plugin=ipx800&type=ipx800&id='.$cmd->getId().'&value=1');
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$result = @file_get_contents($url);
				if ( $result === false )
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
				$url = $url_serveur .'&cmd2='.urlencode($pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('ipx800').'&plugin=ipx800&type=ipx800&id='.$cmd->getId().'&value=0');
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$result = @file_get_contents($url);
				if ( $result === false )
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
				break;
			case "relai":
				$cmd = $this->getCmd(null, 'state');
				$gceid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2);
				$url_serveur .= 'protect/settings/push2.htm?channel='.($gceid+32);
				$url = $url_serveur .'&server='.$ipjeedom.'&port='.$portjeeom.'&pass=&enph=1';
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$result = @file_get_contents($url);
				if ( $result === false ) {
					log::add('ipx800','error',__('L\'ipx ne repond pas.',__FILE__)." get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
				}
				$url = $url_serveur .'&cmd1='.urlencode($pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('ipx800').'&plugin=ipx800&type=ipx800&id='.$cmd->getId().'&value=1');
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$result = @file_get_contents($url);
				if ( $result === false ) {
					log::add('ipx800','error',__('L\'ipx ne repond pas.',__FILE__)." get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
				}
				$url = $url_serveur .'&cmd2='.urlencode($pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('ipx800').'&plugin=ipx800&type=ipx800&id='.$cmd->getId().'&value=0');
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$result = @file_get_contents($url);
				if ( $result === false ) {
					log::add('ipx800','error',__('L\'ipx ne repond pas.',__FILE__)." get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
				}
				break;
			case "compteur":
				break;
			case "analogique":
				$gceid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2);
				$cmd = $this->getCmd(null, 'brut');
				$url_serveur .= 'protect/assignio/analog'.($gceid+1).'.htm';
				$url = $url_serveur .'?analog='.$gceid.'&hi='.$seuil_haut.'&lo='.$seuil_base;
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$result = @file_get_contents($url);
				if ( $result === false )
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
				$url = $url_serveur .'?ch='.$gceid.'&svr='.$ipjeedom.'&port='.$portjeeom.'&log=user%3Apass&en=1';
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$result = @file_get_contents($url);
				if ( $result === false )
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
				$url = $url_serveur .'?ch='.$gceid.'&cmd1='.urlencode($pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('ipx800').'&plugin=ipx800&type=ipx800&id='.$cmd->getId().'&value=$A'.($gceid+1));
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$result = @file_get_contents($url);
				if ( $result === false )
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
				$url = $url_serveur .'?ch='.$gceid.'&cmd2='.urlencode($pathjeedom.'core/api/jeeApi.php?api='.jeedom::getApiKey('ipx800').'&plugin=ipx800&type=ipx800&id='.$cmd->getId().'&value=$A'.($gceid+1));
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$result = @file_get_contents($url);
				if ( $result === false )
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
				break;
		}
	}

	public function configPushGet($url_serveur) {
		$gceid = substr($this->getLogicalId(), strpos($this->getLogicalId(),"_")+2);
		$url_serveur .= 'protect/assignio/analog'.($gceid+1).'.htm';
		log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url_serveur));
		$result = @file_get_contents($url_serveur);
		if ( $result === false )
			throw new Exception(__('L\'ipx ne repond pas.',__FILE__));
		preg_match ("/var GetDataH = *([0-9]*);/", $result, $GetDataH);
		preg_match ("/var GetDataL = *([0-9]*);/", $result, $GetDataL);
		return array($GetDataL[1], $GetDataH[1]);
	}

	public function event() {
        $cmd = ipx800Cmd::byId(init('id'));
        if (!is_object($cmd)) {
            throw new Exception('Commande ID inconnu : ' . init('id'));
        }
		if ($cmd->execCmd() != $cmd->formatValue(init('value'))) {
			$cmd->setCollectDate('');
			$cmd->event(init('value'));
		}
	}

	public function scan() {
		if ( $this->getIsEnable() ) {
			log::add('ipx800','debug','scan '.$this->getName());
			$statuscmd = $this->getCmd(null, 'status');
			$url = $this->getUrl();
			log::add('ipx800','debug','get '.preg_replace("/:[^:]*@/", ":XXXX@", $url).'globalstatus.xml');
			$this->xmlstatus = @simplexml_load_file($url. 'globalstatus.xml');
			$count = 0;
			while ( $this->xmlstatus === false && $count < 3 ) {
				log::add('ipx800','debug','reget '.preg_replace("/:[^:]*@/", ":XXXX@", $url).'globalstatus.xml');
				$this->xmlstatus = @simplexml_load_file($url. 'globalstatus.xml');
				$count++;
			}
			if ( $this->xmlstatus === false ) {
				if ($statuscmd->execCmd() != 0) {
					$statuscmd->setCollectDate('');
					$statuscmd->event(0);
				}
				log::add('ipx800','error',__('L\'ipx ne repond pas.',__FILE__)." ".$this->getName()." get ".preg_replace("/:[^:]*@/", ":XXXX@", $url). 'globalstatus.xml');
				return false;
			}
			if ($statuscmd->execCmd() != 1) {
				$statuscmd->setCollectDate('');
				$statuscmd->event(1);
			}
			$eqLogic_cmd = $this->getCmd(null, 'updatetime');
			$eqLogic_cmd->event(time());
			foreach (eqLogic::byTypeAndSearhConfiguration('ipx800', '"type":"relai"') as $eqLogicRelai) {
				if ( $eqLogicRelai->getIsEnable() && substr($eqLogicRelai->getLogicalId(), 0, strpos($eqLogicRelai->getLogicalId(),"_")) == $this->getId() ) {
					$gceid = substr($eqLogicRelai->getLogicalId(), strpos($eqLogicRelai->getLogicalId(),"_")+2);
					$xpathModele = '//led'.$gceid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$eqLogic_cmd = $eqLogicRelai->getCmd(null, 'state');
						if ($eqLogic_cmd->execCmd() != $eqLogic_cmd->formatValue($status[0])) {
							log::add('ipx800','debug',"Change state off ".$eqLogicRelai->getName());
							$eqLogic_cmd->setCollectDate('');
							$eqLogic_cmd->event($status[0]);
						}
					}
				}
			}
			foreach (eqLogic::byTypeAndSearhConfiguration('ipx800', '"type":"bouton"') as $eqLogicBouton) {
				if ( $eqLogicBouton->getIsEnable() && substr($eqLogicBouton->getLogicalId(), 0, strpos($eqLogicBouton->getLogicalId(),"_")) == $this->getId() ) {
					$gceid = substr($eqLogicBouton->getLogicalId(), strpos($eqLogicBouton->getLogicalId(),"_")+2);
					$xpathModele = '//btn'.$gceid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$eqLogic_cmd = $eqLogicBouton->getCmd(null, 'state');
						if ($eqLogic_cmd->execCmd() != $eqLogic_cmd->formatValue($status[0])) {
							log::add('ipx800','debug',"Change state off ".$eqLogicBouton->getName());
							$eqLogic_cmd->setCollectDate('');
							$eqLogic_cmd->event($status[0]);
						}
					}
				}
			}
			foreach (eqLogic::byTypeAndSearhConfiguration('ipx800', '"type":"analogique"') as $eqLogicAnalogique) {
				if ( $eqLogicAnalogique->getIsEnable() && substr($eqLogicAnalogique->getLogicalId(), 0, strpos($eqLogicAnalogique->getLogicalId(),"_")) == $this->getId() ) {
					$gceid = substr($eqLogicAnalogique->getLogicalId(), strpos($eqLogicAnalogique->getLogicalId(),"_")+2);
					$xpathModele = '//analog'.$gceid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$value = intval($status[0]);
						$eqLogic_cmd = $eqLogicAnalogique->getCmd(null, 'brut');
						if ($eqLogic_cmd->execCmd() != $eqLogic_cmd->formatValue($value)) {
							log::add('ipx800','debug',"Change brut off ".$eqLogicAnalogique->getName()." : ".$value);
						}
						$eqLogic_cmd->setCollectDate('');
						$eqLogic_cmd->event($value);
						$eqLogic_cmd = $eqLogicAnalogique->getCmd(null, 'reel');
						$eqLogic_cmd->event($eqLogic_cmd->execute());
					}
				}
			}
			foreach (eqLogic::byTypeAndSearhConfiguration('ipx800', '"type":"compteur"') as $eqLogicCompteur) {
				if ( $eqLogicCompteur->getIsEnable() && substr($eqLogicCompteur->getLogicalId(), 0, strpos($eqLogicCompteur->getLogicalId(),"_")) == $this->getId() ) {
					$gceid = substr($eqLogicCompteur->getLogicalId(), strpos($eqLogicCompteur->getLogicalId(),"_")+2);
					$xpathModele = '//count'.$gceid;
					$status = $this->xmlstatus->xpath($xpathModele);
					
					if ( count($status) != 0 )
					{
						$nbimpulsion_cmd = $eqLogicCompteur->getCmd(null, 'nbimpulsion');
						$nbimpulsion = $nbimpulsion_cmd->execCmd();
						$nbimpulsionminute_cmd = $eqLogicCompteur->getCmd(null, 'nbimpulsionminute');
						if ( $nbimpulsion != $status[0] ) {
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
			log::add('ipx800','debug','scan end '.$this->getName());
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
		$gceid = substr($eqLogic->getLogicalId(), strpos($eqLogic->getLogicalId(),"_")+2);
			
		switch ($eqLogic->getConfiguration('type', '')) {
			case "carte":
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
				$result = @file_get_contents($url.'?'.http_build_query($data));
				$count = 0;
				while ( $result === false )
				{
					$result = @file_get_contents($url.'?'.http_build_query($data));
					if ( $count < 3 ) {
						log::add('ipx800','error',__('L\'ipx ne repond pas.',__FILE__)." ".$this->getName()." get ".preg_replace("/:[^:]*@/", ":XXXX@", $url)."?".http_build_query($data));
						throw new Exception(__('L\'ipx ne repond pas.',__FILE__)." ".$this->getName());
					}
					$count ++;
				}
				return false;
				break;
			case "bouton":
				if ( $this->getLogicalId() == 'btn_on' )
					$url .= 'leds.cgi?set='.$gceid;
				else if ( $this->getLogicalId() == 'btn_off' )
					$url .= 'leds.cgi?clear='.$gceid;
				else
					return false;
					
				$result = @file_get_contents($url);
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$count = 0;
				while ( $result === false && $count < 3 ) {
					$result = @file_get_contents($url);
					$count++;
				}
				if ( $result === false ) {
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__)." ".$IPXeqLogic->getName());
				}
				return false;
				break;
			case "relai":
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
					
				$result = @file_get_contents($url);
				log::add('ipx800','debug',"get ".preg_replace("/:[^:]*@/", ":XXXX@", $url));
				$count = 0;
				while ( $result === false && $count < 3 ) {
					$result = @file_get_contents($url);
					$count++;
				}
				if ( $result === false ) {
					throw new Exception(__('L\'ipx ne repond pas.',__FILE__)." ".$IPXeqLogic->getName());
				}
				return false;
				break;
			case "compteur":
				break;
			case "analogique":
				if ($this->getLogicalId() == 'reel') {
					try {
						$calcul = $this->getConfiguration('calcul');
						if ( preg_match("/#brut#/", $calcul) ) {
							$EqLogic = $this->getEqLogic();
							$brut = $EqLogic->getCmd(null, 'brut');
							$calcul = preg_replace("/#brut#/", "#".$brut->getId()."#", $calcul);
						}
						$calcul = scenarioExpression::setTags($calcul);
						$result = jeedom::evaluateExpression($calcul);
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
						$EqLogic = $this->getEqLogic();
						log::add('ipx800', 'error', $EqLogic->getName()." error in ".$this->getConfiguration('calcul')." : ".$e->getMessage());
						return scenarioExpression::setTags(str_replace('"', '', cmd::cmdToValue($this->getConfiguration('calcul'))));
					}
				} else {
					return $this->getConfiguration('value');
				}
				break;
		}
	}

    public function preSave() {
		$eqLogic = $this->getEqLogic();
		switch ($eqLogic->getConfiguration('type', '')) {
			case "carte":
				break;
			case "bouton":
				break;
			case "relai":
				break;
			case "compteur":
				if ( $this->getLogicalId() == 'nbimpulsionminute' ) {
					$calcul = $this->getConfiguration('calcul');
					if ( ! preg_match("/#brut#/", $calcul) ) {
						throw new Exception(__('La formule doit contenir une référecence à #brut#.',__FILE__));
					}
				}
				break;
			case "analogique":
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
				break;
		}
    }

    public function event($_value, $_datetime = NULL, $_loop = 1) {
        if ($this->getLogicalId() == 'nbimpulsionminute') {
			try {
				$calcul = $this->getConfiguration('calcul');
				$calcul = preg_replace("/#brut#/", $_value, $calcul);
				$calcul = scenarioExpression::setTags($calcul);
				$result = evaluate($calcul);
				parent::event($result, $_datetime, $_loop);
			} catch (Exception $e) {
				$EqLogic = $this->getEqLogic();
				log::add('ipx800', 'error', $EqLogic->getName()." error in ".$this->getConfiguration('calcul')." : ".$e->getMessage());
				return scenarioExpression::setTags(str_replace('"', '', cmd::cmdToValue($this->getConfiguration('calcul'))));
			}
		} else {
			parent::event($_value, $_datetime, $_loop);
		}
    }

    public function imperihomeCmd() {
 		if ( $this->getLogicalId() == 'reel' ) {
			return true;
		}
 		elseif ( $this->getLogicalId() == 'state' ) {
			return true;
		}
		elseif ( $this->getLogicalId() == 'impulsion' ) {
			return true;
		}
		elseif ( $this->getLogicalId() == 'commute' ) {
			return true;
		}
		else {
			return false;
		}
	}

    public function formatValue($_value, $_quote = false) {
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
                    return $_value;
            }
        }
        return $_value;
    }

	public function imperihomeGenerate($ISSStructure) {
		$eqLogic = $this->getEqLogic(); // Récupération de l'équipement de la commande
		if ( $this->getLogicalId() == 'state' ) { // Sauf si on est entrain de traiter la commande "Mode", à ce moment là on indique un autre type
			$btn_on = $eqLogic->getCmd(null, 'btn_on');
			if ( $btn_on->getIsVisible() )
			{
				$type = 'DevSwitch'; // Le type Imperihome qui correspond le mieux à la commande
			}
			else
			{
				$type = 'DevDoor'; // Le type Imperihome qui correspond le mieux à la commande
			}
		}
		elseif ( $this->getLogicalId() == 'state' ) { // Sauf si on est entrain de traiter la commande "Mode", à ce moment là on indique un autre type
			$type = 'DevSwitch'; // Le type Imperihome qui correspond le mieux à la commande
		}
		elseif ( $this->getLogicalId() == 'impulsion' ) { // Sauf si on est entrain de traiter la commande "Mode", à ce moment là on indique un autre type
			$type = 'DevScene'; // Le type Imperihome qui correspond le mieux à la commande
			$type = 'DevSwitch'; // Le type Imperihome qui correspond le mieux à la commande
		}
		elseif ( $this->getLogicalId() == 'commute' ) { // Sauf si on est entrain de traiter la commande "Mode", à ce moment là on indique un autre type
			$type = 'DevScene'; // Le type Imperihome qui correspond le mieux à la commande
			$type = 'DevSwitch'; // Le type Imperihome qui correspond le mieux à la commande
		}
		else {
			return $info_device;
		}
		$object = $eqLogic->getObject(); // Récupération de l'objet de l'équipement

		// Construction de la structure de base
		$info_device = array(
		'id' => $this->getId(), // ID de la commande, ne pas mettre autre chose!
		'name' => $eqLogic->getName()." - ".$this->getName(), // Nom de l'équipement que sera affiché par Imperihome: mettre quelque chose de parlant...
		'room' => (is_object($object)) ? $object->getId() : 99999, // Numéro de la pièce: ne pas mettre autre chose que ce code
		'type' => $type, // Type de l'équipement à retourner (cf ci-dessus)
		'params' => array(), // Le tableau des paramètres liés à ce type (qui sera complété aprés.
		);
		#$info_device['params'] = $ISSStructure[$info_device['type']]['params']; // Ici on vient copier la structure type: laisser ce code

		if ( defined($btn_on) ) {
			if ( $btn_on->getIsVisible() )
			{
				array_push ($info_device['params'], array("value" =>  '#' . $eqLogic->getCmd(null, 'state')->getId() . '#', "key" => "status", "type" => "infoBinary", "Description" => "Current status : 1 = On / 0 = Off"));
				$info_device['actions']["setStatus"]["item"]["0"] = $eqLogic->getCmd(null, 'btn_off')->getId();
				$info_device['actions']["setStatus"]["item"]["1"] = $eqLogic->getCmd(null, 'btn_on')->getId();
			}
			else
			{
				array_push ($info_device['params'], array("value" =>  '#' . $eqLogic->getCmd(null, 'state')->getId() . '#', "key" => "tripped", "type" => "infoBinary", "Description" => "Is the sensor tripped ? (0 = No / 1 = Tripped)"));
				array_push ($info_device['params'], array("value" =>  '0', "key" => "armable", "type" => "infoBinary", "Description" => "Ability to arm the device : 1 = Yes / 0 = No"));
				array_push ($info_device['params'], array("value" =>  '0', "key" => "ackable", "type" => "infoBinary", "Description" => "Ability to acknowledge alerts : 1 = Yes / 0 = No"));
			}
		}
		elseif ( $this->getLogicalId() == 'state' ) { // Sauf si on est entrain de traiter la commande "Mode", à ce moment là on indique un autre type
			array_push ($info_device['params'], array("value" =>  '#' . $eqLogic->getCmd(null, 'state')->getId() . '#', "key" => "status", "type" => "infoBinary", "Description" => "Current status : 1 = On / 0 = Off"));
			$info_device['actions']["setStatus"]["item"]["0"] = $eqLogic->getCmd(null, 'btn_off')->getId();
			$info_device['actions']["setStatus"]["item"]["1"] = $eqLogic->getCmd(null, 'btn_on')->getId();
		}
		elseif ( $this->getLogicalId() == 'impulsion' ) { // Sauf si on est entrain de traiter la commande "Mode", à ce moment là on indique un autre type
			array_push ($info_device['params'], array("value" =>  '#' . $eqLogic->getCmd(null, 'state')->getId() . '#', "key" => "status", "type" => "infoBinary", "Description" => "Current status : 1 = On / 0 = Off"));
			$info_device['actions']["launchScene"] = $eqLogic->getCmd(null, 'impulsion')->getId();
		}
		elseif ( $this->getLogicalId() == 'commute' ) { // Sauf si on est entrain de traiter la commande "Mode", à ce moment là on indique un autre type
			array_push ($info_device['params'], array("value" =>  '#' . $eqLogic->getCmd(null, 'state')->getId() . '#', "key" => "status", "type" => "infoBinary", "Description" => "Current status : 1 = On / 0 = Off"));
			$info_device['actions']["launchScene"] = $eqLogic->getCmd(null, 'commute')->getId();
		}
		// Ici on traite les autres commandes (hors "Mode")
		return $info_device;
	}
}
?>
