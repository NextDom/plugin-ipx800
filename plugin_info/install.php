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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function ipx800_install() {
    $cron = cron::byClassAndFunction('ipx800', 'pull');
	if ( ! is_object($cron)) {
        $cron = new cron();
        $cron->setClass('ipx800');
        $cron->setFunction('pull');
        $cron->setEnable(1);
        $cron->setDeamon(0);
        $cron->setSchedule('* * * * *');
        $cron->save();
	}
	jeedom::getApiKey('ipx800');
	if (config::byKey('api::ipx800::mode') == '') {
		config::save('api::ipx800::mode', 'enable');
	}
}

function ipx800_update() {
	config::remove('listChildren', 'ipx800');
	config::remove('subClass', 'ipx800');
    $cron = cron::byClassAndFunction('ipx800', 'pull');
	if ( ! is_object($cron)) {
        $cron = new cron();
        $cron->setClass('ipx800');
        $cron->setFunction('pull');
        $cron->setEnable(1);
        $cron->setDeamon(0);
        $cron->setSchedule('* * * * *');
        $cron->save();
	}
    $cron = cron::byClassAndFunction('ipx800', 'cron');
	if (is_object($cron)) {
		$cron->stop();
		$cron->remove();
	}
	$FlagBasculeClass = false;
	foreach (eqLogic::byType('ipx800_bouton') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'bouton');
		$SubeqLogic->setEqType_name('ipx800');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('ipx800');
			$cmd->save();
		}
		$FlagBasculeClass = true;
	}
	foreach (eqLogic::byType('ipx800_analogique') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'analogique');
		$SubeqLogic->setEqType_name('ipx800');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('ipx800');
			$cmd->save();
		}
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('ipx800_relai') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'relai');
		$SubeqLogic->setEqType_name('ipx800');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('ipx800');
			$cmd->save();
		}
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('ipx800_compteur') as $SubeqLogic) {
		$SubeqLogic->setConfiguration('type', 'compteur');
		$SubeqLogic->setEqType_name('ipx800');
		$SubeqLogic->save();
		foreach (cmd::byEqLogicId($SubeqLogic->getId()) as $cmd) {
			$cmd->setEqType('ipx800');
			$cmd->save();
		}
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('ipx800') as $eqLogic) {
		if ( $eqLogic->getConfiguration('type', '') == '' )
		{
			$eqLogic->setConfiguration('type', 'carte');
			$eqLogic->save();
			$FlagBasculeClass = true;
		}
		foreach (cmd::byEqLogicId($eqLogic->getId()) as $cmd) {
			if ( $cmd->getEqType() != 'ipx800')
			{
				$cmd->setEqType('ipx800');
				$cmd->save();
				$FlagBasculeClass = true;
			}
		}
	}
	if ( $FlagBasculeClass )
	{
		log::add('wes','error',__('Les Urls de push ont changer. Pensez à les reconfigurer pour chaque carte.',__FILE__));
	}
	jeedom::getApiKey('ipx800');
	if (config::byKey('api::ipx800::mode') == '') {
		config::save('api::ipx800::mode', 'enable');
	}
	foreach (array("bouton", "relai", "compteur", "analogique") as $type)
	{
		if (file_exists (dirname(__FILE__) . '/../core/class/ipx800_'.$type.'.class.php'))
			unlink(dirname(__FILE__) . '/../core/class/ipx800_'.$type.'.class.php');
		if (file_exists (dirname(__FILE__) . '/../desktop/php/ipx800_'.$type.'.php'))
			unlink(dirname(__FILE__) . '/../desktop/php/ipx800_'.$type.'.php');
	}
}

function ipx800_remove() {
    $cron = cron::byClassAndFunction('ipx800', 'pull');
    if (is_object($cron)) {
		$cron->stop();
        $cron->remove();
    }
    $cron = cron::byClassAndFunction('ipx800', 'cron');
    if (is_object($cron)) {
		$cron->stop();
        $cron->remove();
    }
	config::remove('listChildren', 'ipx800');
	config::remove('subClass', 'ipx800');
}
?>
