				...................
				<div id="<?=$arItemIDs['MAIN_PROPERTIES']?>">					
					<?$strMainOffersProps = false;
					if(isset($arResult["OFFERS"]) && !empty($arResult["OFFERS"]) && $arSetting["OFFERS_VIEW"]["VALUE"] != "LIST") {
						foreach($arResult["OFFERS"] as $key => $arOffer) {
							if(!empty($arOffer["DISPLAY_MAIN_PROPERTIES"])) {
								$strMainOffersProps = true;
								break;
							}
						}
					}
                    $listPosads = getListOfElementsWithPropertiesAsArray(IBLOCK_ID_POSAD);
                    $newListPosads = array();
                    $ii = 0;
                    foreach ($listPosads as $posad) {
                        $newListPosads[$ii]['URL'] = $posad['PROPS']['FILTER_URL']['VALUE'];
                        $existPosadUrl = $posad['PROPS']['FILTER_URL']['VALUE'];
                        preg_match('/\/catalog\/(.*)\/filter\/(.*)\/apply\//', $existPosadUrl, $reg);
                        $ar = explode('/', $reg[2]);
                        $newListPosads[$ii]['PARAMS'] = $ar;
                        $newListPosads[$ii]['MAIN_CATEGORY'] = $reg[1];
                        $ii++;
                    }
                    //pre($newListPosads, 1);
					if(!empty($arResult["DISPLAY_MAIN_PROPERTIES"]) || $strMainOffersProps) {?>
						<div class="catalog-detail-properties">
							<div class="h4"><?=GetMessage("CATALOG_ELEMENT_MAIN_PROPERTIES")?></div>
							<?//DETAIL_PROPERTIES//
							if(!empty($arResult["DISPLAY_MAIN_PROPERTIES"])) {
							    $urlToMainSection = $arResult['SECTION']['PATH'][0]['CODE'];
							    //pre($urlToMainSection, 1);
							    //$urlWithFilter = $urlToMainSection.'filter/';
								foreach($arResult["DISPLAY_MAIN_PROPERTIES"] as $k => $v) {
                                    $neededURL = array();
								    //$urlCheck = $urlWithFilter.mb_strtolower($v['CODE']).'-is-';
								    $urlCheck = mb_strtolower($v['CODE']).'-is-';
								    $valuesXML = $v['VALUE_XML_ID'];
								    foreach ($valuesXML as $i => $valueXML) {
                                        $urlCheck2 = $urlCheck.$valueXML;
                                        foreach ($newListPosads as $newPosad) {
                                            if(in_array($urlCheck2, $newPosad['PARAMS']) && $urlToMainSection == $newPosad['MAIN_CATEGORY']) {
                                                $neededURL[$i] = $newPosad['URL'];
                                                break;
                                            }
                                        }
                                    }
                                    ?>
									<div class="catalog-detail-property">
										<div class="name"><?=$v["NAME"]?></div>
										<?if(!empty($v["FILTER_HINT"])) {?>
											<div class="hint-wrap">
												<a class="hint" href="javascript:void(0);" onclick="showDetailPropertyFilterHint(this, '<?=$v['FILTER_HINT']?>');"><i class="fa fa-question-circle-o"></i></a>
											</div>
										<?}?>
										<div class="dots"></div>
                                        <? if(!empty($neededURL)){
                                            $valstr = '';
                                            foreach ($v["VALUE"] as $id => $val) {
                                                if (key_exists($id, $neededURL)) {
                                                    $valstr = $valstr . '<a href="' . $neededURL[$id] . '">' . $val . '</a>, ';
                                                }
                                                else {
                                                    $valstr = $valstr . $val . ', ';
                                                }
                                            }
                                            $valstr = substr($valstr, 0, strlen($valstr) - 2);
                                            ?>
										    <div class="val"><?=$valstr?></div>
										<?} else {?>
                                        <div class="val"><?=is_array($v["DISPLAY_VALUE"]) ? implode(", ", $v["DISPLAY_VALUE"]) : $v["DISPLAY_VALUE"];?></div>
									    <?}?>
                                    </div>
								<?}
								unset($k, $v);
							}
							.....................