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
    $cron = cron::byClassAndFunction('ipx800', 'cron');
    if (!is_object($cron)) {
        $cron = new cron();
        $cron->setClass('ipx800');
        $cron->setFunction('cron');
        $cron->setEnable(1);
        $cron->setDeamon(0);
        $cron->setSchedule('* * * * *');
        $cron->save();
    }
}

function ipx800_update() {
    $cron = cron::byClassAndFunction('ipx800', 'pull');
	if (is_object($cron)) {
		$cron->stop();
		$cron->remove();
	}
    $cron = cron::byClassAndFunction('ipx800', 'cron');
    if (!is_object($cron)) {
        $cron = new cron();
        $cron->setClass('ipx800');
        $cron->setFunction('pull');
        $cron->setEnable(1);
        $cron->setDeamon(0);
        $cron->setSchedule('* * * * *');
        $cron->save();
    }
	else
	{
        $cron->setEnable(1);
        $cron->setDeamon(0);
        $cron->setSchedule('* * * * *');
        $cron->save();
	}
	foreach (eqLogic::byType('ipx800_bouton') as $SubeqLogic) {
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('ipx800_analogique') as $SubeqLogic) {
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('ipx800_relai') as $SubeqLogic) {
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('ipx800_compteur') as $SubeqLogic) {
		$SubeqLogic->save();
	}
	foreach (eqLogic::byType('ipx800') as $eqLogic) {
		$eqLogic->save();
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
	foreach (eqLogic::byType('ipx800_compteur') as $SubeqLogic) {
		$SubeqLogic->remove();
	}
	foreach (eqLogic::byType('ipx800_bouton') as $SubeqLogic) {
		$SubeqLogic->remove();
	}
	foreach (eqLogic::byType('ipx800_analogique') as $SubeqLogic) {
		$SubeqLogic->remove();
	}
	foreach (eqLogic::byType('ipx800_relai') as $SubeqLogic) {
		$SubeqLogic->remove();
	}
	foreach (eqLogic::byType('ipx800') as $eqLogic) {
		$eqLogic->remove();
	}
}
?>
