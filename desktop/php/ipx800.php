<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('ipx800');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byTypeAndSearhConfiguration($plugin->getId(), '"type":"carte"');
?>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une IPX800}}</a>
                <?php
               foreach ($eqLogics as $eqLogic) {
                    echo '<li>'."\n";
						echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="' . $eqLogic->getId() . '"></i>'."\n";
						echo '<a class="cursor li_eqLogic" style="display: inline;" data-eqLogic_id="' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">' . $eqLogic->getName() . '</a>'."\n";
						echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="' . $eqLogic->getId() . '" style="display: none;">'."\n";
							echo '<li>'."\n";
								echo '<i class="fa fa-line-chart cursor eqLogicAction" data-action="hide" data-eqLogic_id="analogique_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="analogique_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Entrée analogique}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="analogique_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									for ($compteurId = 0; $compteurId <= 3; $compteurId++) {
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_A".$compteurId, 'ipx800');
										if ( is_object($SubeqLogic) ) {
											echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
										}
									}
								echo '</ul>'."\n";
							echo '</li>'."\n";
							echo '<li>'."\n";
								echo '<i class="fa fa-plug cursor eqLogicAction" data-action="hide" data-eqLogic_id="relai_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="relai_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Relai}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="relai_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									for ($compteurId = 0; $compteurId <= 7; $compteurId++) {
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId, 'ipx800');
										if ( is_object($SubeqLogic) ) {
											echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
										}
									}
								echo '</ul>'."\n";
							echo '</li>'."\n";
							echo '<li>'."\n";
								echo '<i class="fa fa-twitch cursor eqLogicAction" data-action="hide" data-eqLogic_id="bouton_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="bouton_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Entrée numérique}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="bouton_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									for ($compteurId = 0; $compteurId <= 7; $compteurId++) {
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_B".$compteurId, 'ipx800');
										if ( is_object($SubeqLogic) ) {
											echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
										}
									}
								echo '</ul>'."\n";
							echo '</li>'."\n";
							echo '<li>'."\n";
								echo '<i class="fa fa-calculator cursor eqLogicAction" data-action="hide" data-eqLogic_id="compteur_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="compteur_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Compteur}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="compteur_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									for ($compteurId = 0; $compteurId <= 7; $compteurId++) {
										$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_C".$compteurId, 'ipx800');
										if ( is_object($SubeqLogic) ) {
											echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
										}
									}
								echo '</ul>'."\n";
							echo '</li>'."\n";
							echo '<li>'."\n";
								echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="extensionx800_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="extensionx800_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Extensions X800}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="extensionx800_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									echo '<li>'."\n";
										echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="x8001_' . $eqLogic->getId() . '"></i>'."\n";
										echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="x8001_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Carte X800 1}}</a>'."\n";
										echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="x8001_' . $eqLogic->getId() . '" style="display: none;">'."\n";
											echo '<li>'."\n";
												echo '<i class="fa fa-plug cursor eqLogicAction" data-action="hide" data-eqLogic_id="relai_x8001_' . $eqLogic->getId() . '"></i>'."\n";
												echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="relai_x8001_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Relai}}</a>'."\n";
												echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="relai_x8001_' . $eqLogic->getId() . '" style="display: none;">'."\n";
													for ($compteurId = 8; $compteurId <= 15; $compteurId++) {
														$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId, 'ipx800');
														if ( is_object($SubeqLogic) ) {
															echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
														}
													}
												echo '</ul>'."\n";
											echo '</li>'."\n";
											echo '<li>'."\n";
												echo '<i class="fa fa-twitch cursor eqLogicAction" data-action="hide" data-eqLogic_id="bouton_x8001_' . $eqLogic->getId() . '"></i>'."\n";
												echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="bouton_x8001_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Entrée numérique}}</a>'."\n";
												echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="bouton_x8001_' . $eqLogic->getId() . '" style="display: none;">'."\n";
													for ($compteurId = 8; $compteurId <= 15; $compteurId++) {
														$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_B".$compteurId, 'ipx800');
														if ( is_object($SubeqLogic) ) {
															echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
														}
													}
												echo '</ul>'."\n";
											echo '</li>'."\n";
										echo '</ul>'."\n";
									echo '</li>'."\n";
									echo '<li>'."\n";
										echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="x8002_' . $eqLogic->getId() . '"></i>'."\n";
										echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="x8002_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Carte X800 2}}</a>'."\n";
										echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="x8002_' . $eqLogic->getId() . '" style="display: none;">'."\n";
											echo '<li>'."\n";
												echo '<i class="fa fa-plug cursor eqLogicAction" data-action="hide" data-eqLogic_id="relai_x8002_' . $eqLogic->getId() . '"></i>'."\n";
												echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="relai_x8002_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Relai}}</a>'."\n";
												echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="relai_x8002_' . $eqLogic->getId() . '" style="display: none;">'."\n";
													for ($compteurId = 16; $compteurId <= 23; $compteurId++) {
														$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId, 'ipx800');
														if ( is_object($SubeqLogic) ) {
															echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
														}
													}
												echo '</ul>'."\n";
											echo '</li>'."\n";
											echo '<li>'."\n";
												echo '<i class="fa fa-twitch cursor eqLogicAction" data-action="hide" data-eqLogic_id="bouton_x8002_' . $eqLogic->getId() . '"></i>'."\n";
												echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="bouton_x8002_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Entrée numérique}}</a>'."\n";
												echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="bouton_x8002_' . $eqLogic->getId() . '" style="display: none;">'."\n";
													for ($compteurId = 16; $compteurId <= 23; $compteurId++) {
														$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_B".$compteurId, 'ipx800');
														if ( is_object($SubeqLogic) ) {
															echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
														}
													}
												echo '</ul>'."\n";
											echo '</li>'."\n";
										echo '</ul>'."\n";
									echo '</li>'."\n";
									echo '<li>'."\n";
										echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="x8003_' . $eqLogic->getId() . '"></i>'."\n";
										echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="x8003_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Carte X800 3}}</a>'."\n";
										echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="x8003_' . $eqLogic->getId() . '" style="display: none;">'."\n";
											echo '<li>'."\n";
												echo '<i class="fa fa-plug cursor eqLogicAction" data-action="hide" data-eqLogic_id="relai_x8003_' . $eqLogic->getId() . '"></i>'."\n";
												echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="relai_x8003_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Relai}}</a>'."\n";
												echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="relai_x8003_' . $eqLogic->getId() . '" style="display: none;">'."\n";
													for ($compteurId = 24; $compteurId <= 31; $compteurId++) {
														$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_R".$compteurId, 'ipx800');
														if ( is_object($SubeqLogic) ) {
															echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
														}
													}
												echo '</ul>'."\n";
											echo '</li>'."\n";
											echo '<li>'."\n";
												echo '<i class="fa fa-twitch cursor eqLogicAction" data-action="hide" data-eqLogic_id="bouton_x8003_' . $eqLogic->getId() . '"></i>'."\n";
												echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="bouton_x8003_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Entrée numérique}}</a>'."\n";
												echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="bouton_x8003_' . $eqLogic->getId() . '" style="display: none;">'."\n";
													for ($compteurId = 24; $compteurId <= 31; $compteurId++) {
														$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_B".$compteurId, 'ipx800');
														if ( is_object($SubeqLogic) ) {
															echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
														}
													}
												echo '</ul>'."\n";
											echo '</li>'."\n";
										echo '</ul>'."\n";
									echo '</li>'."\n";
								echo '</ul>'."\n";
							echo '</li>'."\n";
							echo '<li>'."\n";
								echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="extensionx400_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="extensionx400_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Extensions X440}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="extensionx400_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									echo '<li>'."\n";
										echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="x4401_' . $eqLogic->getId() . '"></i>'."\n";
										echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="x4401_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Carte X440 1}}</a>'."\n";
										echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="x4401_' . $eqLogic->getId() . '" style="display: none;">'."\n";
											for ($compteurId = 4; $compteurId <= 7; $compteurId++) {
												$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_A".$compteurId, 'ipx800');
												if ( is_object($SubeqLogic) ) {
													echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
												}
											}
										echo '</ul>'."\n";
									echo '</li>'."\n";
									echo '<li>'."\n";
										echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="x4402_' . $eqLogic->getId() . '"></i>'."\n";
										echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="x4402_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Carte X440 2}}</a>'."\n";
										echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="x4402_' . $eqLogic->getId() . '" style="display: none;">'."\n";
											for ($compteurId = 8; $compteurId <= 11; $compteurId++) {
												$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_A".$compteurId, 'ipx800');
												if ( is_object($SubeqLogic) ) {
													echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
												}
											}
										echo '</ul>'."\n";
									echo '</li>'."\n";
									echo '<li>'."\n";
										echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="x4403_' . $eqLogic->getId() . '"></i>'."\n";
										echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="x4403_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Carte X440 3}}</a>'."\n";
										echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="x4403_' . $eqLogic->getId() . '" style="display: none;">'."\n";
											for ($compteurId = 12; $compteurId <= 15; $compteurId++) {
												$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_A".$compteurId, 'ipx800');
												if ( is_object($SubeqLogic) ) {
													echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
												}
											}
										echo '</ul>'."\n";
									echo '</li>'."\n";
								echo '</ul>'."\n";
							echo '</li>'."\n";
							echo '<li>'."\n";
								echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="extensionx200ph_' . $eqLogic->getId() . '"></i>'."\n";
								echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="extensionx200ph_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Extensions X200ph}}</a>'."\n";
								echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="extensionx200ph_' . $eqLogic->getId() . '" style="display: none;">'."\n";
									echo '<li>'."\n";
										echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="x200ph1_' . $eqLogic->getId() . '"></i>'."\n";
										echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="x200ph1_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Carte X200ph 1}}</a>'."\n";
										echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="x200ph1_' . $eqLogic->getId() . '" style="display: none;">'."\n";
											for ($compteurId = 12; $compteurId <= 13; $compteurId++) {
												$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_A".$compteurId, 'ipx800');
												if ( is_object($SubeqLogic) ) {
													echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
												}
											}
										echo '</ul>'."\n";
									echo '</li>'."\n";
									echo '<li>'."\n";
										echo '<i class="fa fa-sitemap cursor eqLogicAction" data-action="hide" data-eqLogic_id="x200ph2_' . $eqLogic->getId() . '"></i>'."\n";
										echo '<a class="cursor eqLogicAction" data-action="hide" style="display: inline;" data-eqLogic_id="x200ph2_' . $eqLogic->getId() . '" data-eqLogic_type="ipx800">{{Carte X200ph 2}}</a>'."\n";
										echo '<ul id="ul_eqLogic" class="nav nav-list bs-sidenav sub-nav-list" data-eqLogic_id="x200ph2_' . $eqLogic->getId() . '" style="display: none;">'."\n";
											for ($compteurId = 14; $compteurId <= 15; $compteurId++) {
												$SubeqLogic = eqLogic::byLogicalId($eqLogic->getId()."_A".$compteurId, 'ipx800');
												if ( is_object($SubeqLogic) ) {
													echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $SubeqLogic->getId() . '" data-eqLogic_type="ipx800"><a>' . $SubeqLogic->getName() . '</a></li>'."\n";
												}
											}
										echo '</ul>'."\n";
									echo '</li>'."\n";
								echo '</ul>'."\n";
							echo '</li>'."\n";
						echo '</ul>'."\n";
					echo '</li>'."\n";
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="fa fa-plus-circle" style="font-size : 6em;color:#94ca02;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02">
					<center>{{Ajouter}}</center>
				</span>
			</div>
			<div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
				<center>
					<i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">
					<center>{{Configuration}}</center>
				</span>
			</div>
		</div>
        <legend>{{Mes IPX}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<center>
					<i class="fa fa-plus-circle" style="font-size : 7em;color:#94ca02;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02">
					<center>{{Ajouter}}</center>
				</span>
			</div>
			<?php
			if (count($eqLogics) == 0) {
				echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore d'IPX, cliquez sur Ajouter un équipement pour commencer}}</span></center>";
			} else {
                foreach ($eqLogics as $eqLogic) {
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                    echo "<center>";
                    echo '<img src="plugins/ipx800/plugin_info/ipx800_icon.png" height="105" width="95" />';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
                    echo '</div>';
                }
			} ?>
		</div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic ipx800" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
		<a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
		<a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
		<a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<form class="form-horizontal">
					<fieldset>
						<legend>
						   <i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}
						   <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i>
						</legend>
						<div class="form-group">
							<label class="col-lg-2 control-label">{{Nom d'equipement}}</label>
							<div class="col-lg-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'ipx800}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label" >{{Objet parent}}</label>
							<div class="col-lg-3">
								<select class="form-control eqLogicAttr" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
									foreach (object::all() as $object) {
										echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>'."\n";
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">{{Catégorie}}</label>
							<div class="col-lg-8">
								<?php
								foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
									echo '<label class="checkbox-inline">'."\n";
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
									echo '</label>'."\n";
								}
								?>

							</div>
						</div>
						<div class="form-group">
						  <label class="col-sm-2 control-label" ></label>
							<div class="col-sm-10">
							<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>Activer</label>
							<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>Visible</label>
							<a class="btn btn-default carte_only" id="bt_configPush" title='{{Configurer push}}'><i class="fa fa-wrench"></i></a>
							<a class="btn btn-default carte_only" id="bt_goCarte" title='{{Accéder à la carte}}'><i class="fa fa-cogs"></i></a>
							</div>
						</div>
						<div class="form-group carte_only">
							<label class="col-lg-2 control-label">{{IP de l'ipx800}}</label>
							<div class="col-lg-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ip"/>
							</div>
						</div>
						<div class="form-group carte_only">
							<label class="col-lg-2 control-label">{{Port de l'ipx800}}</label>
							<div class="col-lg-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port"/>
							</div>
						</div>
						<div class="form-group carte_only">
							<label class="col-lg-2 control-label">{{Compte de l'ipx800}}</label>
							<div class="col-lg-3">
								<input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username"/>
							</div>
						</div>
						<div class="form-group carte_only">
							<label class="col-lg-2 control-label">{{Password de l'ipx800}}</label>
							<div class="col-lg-3">
								<input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password"/>
							</div>
						</div>
					</fieldset> 
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th style="width: 50px;">#</th>
							<th style="width: 230px;">{{Nom}}</th>
							<th style="width: 110px;">{{Sous-Type}}</th>
							<th>{{Valeur}}</th>
							<th style="width: 100px;">{{Unité}}</th>
							<th style="width: 200px;">{{Paramètres}}</th>
							<th style="width: 100px;"></th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
		   </div>
		</div>
    </div>
</div>

<?php
include_file('desktop', 'ipx800', 'js', 'ipx800');
include_file('core', 'plugin.template', 'js');
?>
<script type="text/javascript">
if (getUrlVars('saveSuccessFull') == 1) {
    $('#div_alert').showAlert({message: '{{Sauvegarde effectuée avec succès}}<br>{{Utilisez l\'icône suivant pour voir le détail de l\'élément <i class="fa fa-sitemap"></i>}}', level: 'success'});
}
</script>