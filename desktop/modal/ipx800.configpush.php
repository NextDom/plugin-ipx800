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

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
if (init('id') == '') {
    throw new Exception('{{EqLogic ID ne peut etre vide}}');
}
$eqLogic = eqLogic::byId(init('id'));
if (!is_object($eqLogic)) {
    throw new Exception('{{EqLogic non trouvé}}');
}
?>

<fieldset>
<legend>{{Relai}}</legend>
<?php
echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="relai_' . $eqLogic->getId() . '">';
	for ($compteurId = 0; $compteurId <= 31; $compteurId++) {
		$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId, 'ipx800');
		if ( is_object($SubeqLogic) ) {
			echo '<label class="checkbox-inline">';
			echo '<input type="checkbox" class="configPusheqLogic" data-configPusheqLogic_id="' . $SubeqLogic->getId() . '" checked/>' . $SubeqLogic->getName();
			echo '</label><br>';
		}
	}
?>
</fieldset>
<fieldset>
<legend>{{Entrée numérique}}</legend>
<?php
echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="bouton_' . $eqLogic->getId() . '">';
	for ($compteurId = 0; $compteurId <= 31; $compteurId++) {
		$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_B".$compteurId, 'ipx800');
		if ( is_object($SubeqLogic) ) {
			echo '<label class="checkbox-inline">';
			echo '<input type="checkbox" class="configPusheqLogic" data-configPusheqLogic_id="' . $SubeqLogic->getId() . '" checked/>' . $SubeqLogic->getName();
			echo '</label><br>';
		}
	}
echo '</ul>';
?>
</fieldset>
<fieldset>
<legend>{{Entrée analogique}}</legend>
<?php
echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="bouton_' . $eqLogic->getId() . '">';
	$url_serveur = $eqLogic->getUrl();
	echo '<div class="row">';
	echo '<div class="col-sm-3 control-label"></div>';
	echo '<div class="col-sm-1 control-label">Seuil bas de déclanchement</div>';
	echo '<div class="col-sm-1 control-label">Seuil haut de déclanchement</div>';
	echo '</div>';
	for ($compteurId = 0; $compteurId <= 15; $compteurId++) {
		$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_A".$compteurId, 'ipx800');
		if ( is_object($SubeqLogic) ) {
			list($SeuilBas, $SeuilHaut) = $SubeqLogic->configPushGet($url_serveur);
			echo '<div class="row">';
			echo '<label class="col-sm-3">';
			echo '<input type="checkbox" class="configPusheqLogic" data-configPusheqLogic_id="' . $SubeqLogic->getId() . '"';
			if ( $SeuilBas != "0" || $SeuilHaut != "0" )
			{
				echo 'checked';
			}
			echo '/>' . $SubeqLogic->getName() . '</label>';
			echo '<input type="text" class="col-sm-1 configPusheqLogic" id="SeuilBas_' . $SubeqLogic->getId() . '" value="'.$SeuilBas.'"/>';
			echo '<input type="text" class="col-sm-1 configPusheqLogic" id="SeuilHaut_' . $SubeqLogic->getId() . '" value="'.$SeuilHaut.'"/>';
			echo '</div>';
		}
	}
echo '</ul>';
?>
</fieldset>
<div class="form-group alert alert-warning">
Attention, chaque case validée renseignera le Push settings concerné de l'ipx800 et effacera la configuration existante. Pour les entrées analogique, les associations à des relais pourront être perdu.
</div>
<div id='div_configurePush' style="display: none;"></div>
<a class="btn btn-warning pull-right" id="bt_ApplyconfigPush" style="color : white;"><i class="fa fa-wrench"></i> {{Appliquer}}</a>
<a class="btn btn-success pull-right" id="bt_UnCheckAll" style="color : white;"><i class="fa fa-square-o"></i> {{Tout décocher}}</a>
<a class="btn btn-success pull-right" id="bt_CheckAll" style="color : white;"><i class="fa fa-check-square-o"></i> {{Tout cocher}}</a>
<?php include_file('desktop', 'ipx800', 'js', 'ipx800'); ?>