<table width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align:top">
    <tr>
        <td class="leftmnu wide" valign="top">
            <div style="padding: 0 5px 0 5px;">
                <?php if(!empty($categoryList)) : ?>
                <table cellspacing="0" cellpadding="0" border="0" class="divider myheader">
                    <tbody>
                    <tr>
                        <td width="30" align="center"><img width="14" height="14" src="/pic1/arr2.gif"></td>
                        <td class="leftmnutitle1-table-txt">
                            <?=$ui->item('Choose a subcategory'); ?>:
                        </td>
                    </tr>
                    </tbody>
                </table>

                <ul class="left_list divider">
                <?php foreach($categoryList as $cat) : ?>
                    <li>
                        <a href="<?=Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey($entity), 'cid' => $cat['id'], 'title' => ProductHelper::ToAscii($cat['title_en']))); ?>"><?=ProductHelper::GetTitle($cat); ?></a>
<!--                        (--><?//=$cat['items_count']; ?><!-- / --><?//=$cat['avail_items_count']; ?><!--)-->
                    </li>
                <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php $this->renderPartial('/entity/_left_menu', array('entity' => $entity)); ?>
                <?php $this->renderPartial('/site/login_form', array('model' => new User, 'refresh' => true)); ?>

            </div>
        </td>
        <td valign="top" style="padding: 5px;">
            <!-- content -->

            <?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs, 'entity' => $entity)); ?>

            <?php if(!empty($info)): ?>
                <div class="text"><?=$info; ?></div>
                <div class="itemsep1"></div>
            <?php endif; ?>

            <?php $this->widget('SortAndPaging', array('paginatorInfo' => $paginatorInfo)); ?>

            <ul class="items">
                <?php foreach($items as $item) : ?>
                    <?php $item['entity'] = $entity;
                          $key = 'itemlist_'.$entity.'_'.$item['id']; ?>
                    <li>
                        <?php $this->renderPartial('_common_item_2', array('item' => $item, 'entity' => $entity, 'isList' => true)); ?>
                    </li>
                <?php endforeach; ?>
            </ul>


            <?php if(count($items) > 0) $this->widget('SortAndPaging', array('paginatorInfo' => $paginatorInfo)); ?>
            <!-- /content -->
        </td>
        
    </tr>

</table>