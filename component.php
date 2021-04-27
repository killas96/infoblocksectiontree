<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 *  Компонент "Дерево инфоблока"
 * @author Николаев Константин <nikolaev@twozebras.ru>
 *
 * @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */



if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["ELEMENT_PROPERTIES"] = array_filter($arParams["ELEMENT_PROPERTIES"]);
$arParams["SECTION_USER_FIELDS"] = array_filter($arParams["SECTION_USER_FIELDS"]);

$arSelect = array('IBLOCK_ID', 'ID', 'IBLOCK_SECTION_ID');
if(!empty($arParams["ELEMENT_PROPERTIES"]))
	$arParams["ELEMENT_PROPERTIES"] = array_merge($arParams["ELEMENT_PROPERTIES"],$arSelect);

if($this->StartResultCache(false, array($arParams, $USER->GetGroups()))) {
	if( !$arParams["IBLOCK_SECTION_ID"] || $arParams["IBLOCK_SECTION_ID"]<0){
		$this->AbortResultCache();
		ShowError(GetMessage("CONFIG_ERROR"));
		return;
	}
	
	if(!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	
	if($arParams["IBLOCK_ID"]>0) {
		$rsIBlock = CIBlock::GetList(array(), array(
			"ACTIVE" => "Y",
			"ID" => $arParams["IBLOCK_ID"],
		));
	} else {
		$this->AbortResultCache();
		ShowError(GetMessage("CONFIG_ERROR"));
		return;
	}
	if($arResult = $rsIBlock->GetNext()) {
		$arResult["SECTIONS"] = array();
		$rsParentSection = CIBlockSection::GetByID($arParams["IBLOCK_SECTION_ID"]);
		if ($arParentSection = $rsParentSection->GetNext()) {
			$arFilter1 = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'], '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL'],"ACTIVE" => "Y",'GLOBAL_ACTIVE' => 'Y');
			if(isset($arParams['ACTIVE']) && $arParams['ACTIVE'] == "Y")
				$arFilter1['ACTIVE'] = "Y";
			$rsSect = CIBlockSection::GetList(array("SORT"=>"ASC", "left_margin" => "ASC"),$arFilter1, true, $arParams["SECTION_USER_FIELDS"]);
			while ($arSect = $rsSect->GetNext()) {
				$arResult["SECTIONS"][$arSect["ID"]] =$arSect;
			}		  
		} else {
			$this->AbortResultCache();
			ShowError(GetMessage("NO_SECTIONS"));
			return;
		}		
		$arResult["ELEMENTS"] = array();
		$arFilter['SECTION_ID'] = $arParams["IBLOCK_SECTION_ID"];
		$arFilter['IBLOCK_ID'] = $arParams["IBLOCK_ID"];
		$arFilter['INCLUDE_SUBSECTIONS'] = "Y";
		if(isset($arParams['ACTIVE']) && $arParams['ACTIVE'] == "Y")
			$arFilter['ACTIVE'] = "Y";
		$rs_element = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arParams["ELEMENT_PROPERTIES"]);
		while($ar_element = $rs_element->GetNext()) {   
			if(array_key_exists($ar_element["IBLOCK_SECTION_ID"], $arResult["ELEMENTS"])) {
				$arResult["ELEMENTS"][$ar_element["IBLOCK_SECTION_ID"]][$ar_element["ID"]] = $ar_element;
			} else {
				$arResult["ELEMENTS"][$ar_element["IBLOCK_SECTION_ID"]] = Array();
				$arResult["ELEMENTS"][$ar_element["IBLOCK_SECTION_ID"]][$ar_element["ID"]] = $ar_element;
			}   
		}
		if(!empty($arResult["SECTIONS"]) && !empty( $arResult["ELEMENTS"])) {
			$arResult["ITEMS"] = array();
			if(isset($arResult["ELEMENTS"][$arParams["IBLOCK_SECTION_ID"]]) && !empty($arResult["ELEMENTS"][$arParams["IBLOCK_SECTION_ID"]])){
				$arResult["ITEMS"][$arParams["IBLOCK_SECTION_ID"]]["ELEMENTS"] = $arResult["ELEMENTS"][$arParams["IBLOCK_SECTION_ID"]];
			}
			foreach($arResult["SECTIONS"] as $k=>$v) {
				$arResult["ITEMS"][$k] = $arResult["SECTIONS"][$k];
				$arResult["ITEMS"][$k]["ELEMENTS"] = $arResult["ELEMENTS"][$k];
			}
		}
		unset($arResult["SECTIONS"],$arResult["ELEMENTS"]);		
		
	} else {
		$this->AbortResultCache();
		\Bitrix\Iblock\Component\Tools::process404( 
			trim($arParams["MESSAGE_404"]) ?: GetMessage("T_TREE_IBLOCK_NA")
			,true
			,$arParams["SET_STATUS_404"] === "Y"
			,$arParams["SHOW_404"] === "Y"
			,$arParams["FILE_404"]
		);
	}
	$this->IncludeComponentTemplate();
}


?>