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
$isSection = false;
if(count($arResult["ITEMS"]) > 0):?>
    <div class="form-group">
        <input type="text" class="form-control input-sm" placeholder="Введите любую часть фамилии / имени / должности / телефона" name="keyword" id="keyword">
		<div class="resultS">
		</div>
    </div>
	<div class="structure-list">

			<?foreach ($arResult['ITEMS'] as $key => $arItem):?>
			  <div class="employers-list">
			  	<?
			  if(isset($arItem["NAME"]) && !empty($arItem["NAME"])):$isSection = true;?>
			  	
					<h3><?=$arItem["NAME"]?></h3>
				
			  <?endif;
				if(!empty($arItem["ELEMENTS"])):
					foreach($arItem["ELEMENTS"] as $person):?>
							<div class="card mb-3">
							  <div class="row no-gutters">
								<div class="col-3 col-md-auto">
								  <a href="/administration/<?=$person["ID"]?>.html" class="image card-img mt-3 mt-md-0" style="background-image:url('<?=$person['CROP_IMAGE']['src']?>')" title="<?=$person["NAME"]?>"></a>
								</div>
								<div class="col-9 col-md-10">
								  <div class="card-body">
									<h5 class="card-title"><a href="/administration/<?=$person["ID"]?>.html" class="searchable"><?=$person['NAME']?></a></h5>
									<?if(!empty($person['PROPERTY_POSITION_VALUE']))?>
										<p class="card-text searchable"><?=$person['PROPERTY_POSITION_VALUE']?></p>
										<p class="card-text ">
										<?if(!empty($person['PROPERTY_PHONE_CITY_VALUE'])):?>
											<small class="text-muted searchable">Тел. <a href="tel:<?=$person['PROPERTY_PHONE_CITY_VALUE']?>" ><?=$person['PROPERTY_PHONE_CITY_VALUE']?></a></small>
										<?endif;?>
										<?if(!empty($person['PROPERTY_PHONE_MOBILE_VALUE'])):?>
											<small class="text-muted searchable">, вн. <?=$person['PROPERTY_PHONE_MOBILE_VALUE']?></small>
										<?endif;?>
										<?if(!empty($person['PROPERTY_ROOM_VALUE'])):?>
											<small class="text-muted searchable">, каб. <?=$person['PROPERTY_ROOM_VALUE']?></small>
										<?endif;?>
									 </p>
								  </div>
								</div>
							  </div>
							</div>
					<?endforeach;
					if($isSection):?>
							
						<?$isSection = false;
					endif;?>
					</div> 
				<?endif;
			endforeach;?>
	</div>
	<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH.?>/js/jcfilter.js?ver=1.0.0"></script>
	<script>
		jQuery(document).ready(function($) {
			$('#keyword').jcOnPageFilter({
				parentSearchClass:'structure-list',
				parentSectionClass:'employers-list',
				parentLookupClass:'card',
				childBlockClass:'searchable',
				resultBlockClass:'resultS',
				addClassElems:true,
				hideNegatives: true,
				highlightColor:'unset',
				textColorForHighlights:'#028ac8',
			});		
		});
	</script>
<?endif;
?>