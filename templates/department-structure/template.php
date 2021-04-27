<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
#echo '<pre>';print_r($arResult["ITEMS"]);echo '</pre>';
$isSection = false;
if(count($arResult["ITEMS"]) > 0):?>
	<div class="structure-list">

			<?foreach ($arResult['ITEMS'] as $key => $arItem):
			  if(isset($arItem["NAME"]) && !empty($arItem["NAME"])):
			  	$isSection = true;?>
			  	<button type="button" class="collapsible"><?=$arItem["NAME"]?></button>
			  	<div class="employers-list">
			  <?endif;
		  	if(!empty($arItem["ELEMENTS"])):
		  		foreach($arItem["ELEMENTS"] as $person):?>
						<div class="card mb-3">
						  <div class="row no-gutters">
						    <div class="col-3 col-md-auto">
						      <!-- <img src="<?=$person['CROP_IMAGE']['src']?>" class="card-img" alt="<?=$person['NAME']?>"> -->
						      <a href="/administration/<?=$person["ID"]?>.html" class="image card-img mt-3 mt-md-0" style="background-image:url('<?=$person['CROP_IMAGE']['src']?>')" title="<?=$person["NAME"]?>"></a>
						    </div>
						    <div class="col-9 col-md-10">
						      <div class="card-body">
						        <h5 class="card-title"><a href="/administration/<?=$person["ID"]?>.html"><?=$person['NAME']?></a></h5>
						        <?if(!empty($person['PROPERTY_POSITION_VALUE']))?>
						        	<p class="card-text"><?=$person['PROPERTY_POSITION_VALUE']?></p>
						        	<p class="card-text">
						        	<?if(!empty($person['PROPERTY_PHONE_CITY_VALUE'])):?>
						        		<small class="text-muted">Тел. <a href="tel:<?=$person['PROPERTY_PHONE_CITY_VALUE']?>"><?=$person['PROPERTY_PHONE_CITY_VALUE']?></a></small>
						        	<?endif;?>
						        	<?if(!empty($person['PROPERTY_PHONE_MOBILE_VALUE'])):?>
						        		<small class="text-muted">, вн. <?=$person['PROPERTY_PHONE_MOBILE_VALUE']?></small>
						        	<?endif;?>
						        	<?if(!empty($person['PROPERTY_ROOM_VALUE'])):?>
						        		<small class="text-muted">, каб. <?=$person['PROPERTY_ROOM_VALUE']?></small>
						        	<?endif;?>
						       	 </p>
						      </div>
						    </div>
						  </div>
						</div>
		  		<?endforeach;
		  		if($isSection):?>
						</div> <!-- /employers-list -->
					<?$isSection = false; endif;
		  	endif;
			endforeach;?>

	</div>
<?endif;
?>