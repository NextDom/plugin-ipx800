<?php


/*
 * This file is part of the NextDom software (https://github.com/NextDom or http://nextdom.github.io).
 * Copyright (c) 2018 NextDom.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 2.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

    if (init('action') == 'configPush') {
        $eqLogic = ipx800::byId(init('id'));
        if (!is_object($eqLogic)) {
            throw new Exception(__('ipx800 eqLogic non trouvé : ', __FILE__) . init('id'));
        }
		if ( strpos(init('ip'), '/') !== false ) {
            throw new Exception(__('L\'IP de Jeedom ne doit pas contenir de "/" : ', __FILE__) . init('ip'));
		}
		$eqLogic->configPush(init('ip'), init('path'));
        ajax::success(__('Url de push sur ipx800 configuré', __FILE__));
    }

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>
