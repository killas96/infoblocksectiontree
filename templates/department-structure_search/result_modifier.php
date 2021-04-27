<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


  foreach($arResult["ITEMS"] as $key=>$arItem):
    if(!empty($arItem["ELEMENTS"])):
      foreach($arItem["ELEMENTS"] as $jey => $person):
        if(!empty($person["DETAIL_PICTURE"])):
          $renderImage = CFile::ResizeImageGet($person["DETAIL_PICTURE"],Array("width" => "120", "height" => "150"),BX_RESIZE_IMAGE_EXACT);
        else:
          $renderImage['src'] = SITE_TEMPLATE_PATH."/images/150x200.png"; #Заглушка
        endif;
        $arResult["ITEMS"][$key]["ELEMENTS"][$jey]["CROP_IMAGE"] = $renderImage;
      endforeach;
    endif;
  endforeach;