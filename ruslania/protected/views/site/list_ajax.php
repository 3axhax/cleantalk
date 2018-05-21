<h1 class="titlename"><?=((!$cid) ? $ui->item('A_NEW_GOODS_RAZD_TITLE') . ': ' . Entity::GetTitle($entity) : $ui->item('A_NEW_GOODS_CAT_TITLE') . ': ' . $title_cat); ?></h1>

<div class="sortbox" style="float: right;">
                <form method="GET">
                    <?php $value = SortOptions::GetDefaultSort(@$_GET['sort']) ?>
                    <?= CHtml::dropDownList('sort', $value, SortOptions::GetSortData(), array('onchange' => '$(this).parent().submit()', 'style'=>'width: auto;')); ?>
					
					<? if ($_GET['lang']) : ?>
					
					<input type="hidden" name="lang" value="<?=$_GET['lang']?>"/>
					
					<? endif; ?>
					
                </form>
            </div>
			
			<div class="sortbox langsel">
                <form method="GET">
				
                    <select name="lang" onchange="$(this).parent().submit()"><option value=""><?=$ui->item('A_NEW_FILTER_TITLE_LANG')?><?=$ui->item('A_NEW_FILTER_ALL')?></option>
					
					<?
					
					
					
					$entities = Entity::GetEntitiesList();
					$tbl = $entities[$entity]['site_table'];
					
					$sql = 'SELECT ln.id as lnid, ln.title_'.Yii::app()->language.' AS lntitle FROM `all_items_languages` AS ail, `languages` AS ln, `'.$tbl.'` AS t WHERE ln.id = ail.language_id AND
					ail.entity = '.$entity.' AND
					ail.item_id = t.id';
					
					if ($cat_id) {
					
						$sql .= ' AND (t.code = '.$cat_id['id'].' OR t.subcode = '.$cat_id['id'].')';
					
					}
					
					$arrFirstLang = array(1,2,3,4);
					$arrMoreLang = array();
					$sql .= ' GROUP BY ln.id ORDER BY ln.title_'.Yii::app()->language.' ASC';
			 
					$rows = Yii::app()->db->createCommand($sql)->queryAll();
					
					foreach ($rows as $row)
					{
						$sel='';
						if ($_GET['lang'] == $row['lnid']) {
							$sel = ' selected="selected"';
						}
						
						switch ($row['lnid']) {
							
							case 7: $arrFirstLang[0] = '<option value="'.$row['lnid'].'"'.$sel.'>'.$ui->item('A_NEW_FILTER_TITLE_LANG').$row['lntitle'].'</option>'; break;
							case 14: $arrFirstLang[1] = '<option value="'.$row['lnid'].'"'.$sel.'>'.$ui->item('A_NEW_FILTER_TITLE_LANG').$row['lntitle'].'</option>'; break;
							case 9: $arrFirstLang[2] = '<option value="'.$row['lnid'].'"'.$sel.'>'.$ui->item('A_NEW_FILTER_TITLE_LANG').$row['lntitle'].'</option>'; break;
							case 8: $arrFirstLang[3] = '<option value="'.$row['lnid'].'"'.$sel.'>'.$ui->item('A_NEW_FILTER_TITLE_LANG').$row['lntitle'].'</option>'; break;
							default: 

							$arrMoreLang[] = '<option value="'.$row['lnid'].'"'.$sel.'>'.$ui->item('A_NEW_FILTER_TITLE_LANG').$row['lntitle'].'</option>';
							
							break;
						}
						
					}
					
					$arrlangs = array_merge($arrFirstLang, $arrMoreLang);
					
					echo implode("\n", $arrlangs);
					
					
					?>
					
					</select>
					
					<? if ($_GET['sort']) : ?>
					
					<input type="hidden" name="sort" value="<?=$_GET['sort']?>"/>
					
					<? endif; ?>
					
                </form>
            </div>

<ul class="items">
    <?php foreach ($items as $item) : ?>
        <?php
        $item['entity'] = $entity;
        $key = 'itemlist_' . $entity . '_' . $item['id'];
        ?>
        <li>
            <?php $this->renderPartial('_common_item_2', array('item' => $item, 'entity' => $entity, 'isList' => true)); ?>
        </li>
    <?php endforeach; ?>
</ul>


<?php if (count($items) > 0) $this->widget('SortAndPaging', array('paginatorInfo' => $paginatorInfo)); ?>
<!--</div>-->
<!--</div>-->
