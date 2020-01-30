				...................
				$listPosads = getListOfElementsWithPropertiesAsArray(IBLOCK_ID_POSAD, array('!'.'PROPERTY_HIDE_DETAIL_VALUE' => 'Да'));
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
					if(!empty($arResult["DISPLAY_MAIN_PROPERTIES"]) || $strMainOffersProps) {?>
						<div class="catalog-detail-properties">
							<div class="h4"><?=GetMessage("CATALOG_ELEMENT_MAIN_PROPERTIES")?></div>
							<?//DETAIL_PROPERTIES//
							if(!empty($arResult["DISPLAY_MAIN_PROPERTIES"])) {
							    $urlToMainSection = $arResult['SECTION']['PATH'][0]['CODE'];
								foreach($arResult["DISPLAY_MAIN_PROPERTIES"] as $k => $v) {
                                    $neededURL = array();
								    $urlCheck = mb_strtolower($v['CODE']).'-is-';
								    $valuesXML = ($v['VALUE_XML_ID']) ? $v['VALUE_XML_ID'] : $v['VALUE'];
								    if (is_array($valuesXML)) {
                                        foreach ($valuesXML as $i => $valueXML) {
                                            $urlCheck2 = $urlCheck . $valueXML;
                                            foreach ($newListPosads as $newPosad) {
                                                if (in_array($urlCheck2, $newPosad['PARAMS']) && $urlToMainSection == $newPosad['MAIN_CATEGORY']) {
                                                    $neededURL[$i] = $newPosad['URL'];
                                                    break;
                                                }
                                            }
                                        }
                                    } else {
                                        $urlCheck2 = $urlCheck . $valuesXML;
                                        foreach ($newListPosads as $newPosad) {
                                            if (in_array($urlCheck2, $newPosad['PARAMS']) && $urlToMainSection == $newPosad['MAIN_CATEGORY']) {
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
                                            if(is_array($v['VALUE'])) {
                                                foreach ($v["VALUE"] as $id => $val) {
                                                    if (key_exists($id, $neededURL)) {
                                                        $valstr = $valstr . '<a href="' . $neededURL[$id] . '">' . $val . '</a>, ';
                                                    } else {
                                                        $valstr = $valstr . $val . ', ';
                                                    }
                                                }
                                                $valstr = substr($valstr, 0, strlen($valstr) - 2);
                                            } else {
                                                $valstr = $valstr . '<a href="' . $neededURL[0] . '">' . $v['DISPLAY_VALUE'] . '</a>';
                                            }
                                            ?>
										    <div class="val"><?=$valstr?></div>
										<?} else {?>
                                        <div class="val"><?=is_array($v["DISPLAY_VALUE"]) ? implode(", ", $v["DISPLAY_VALUE"]) : $v["DISPLAY_VALUE"];?></div>
									    <?}?>
                                    </div>
							.....................