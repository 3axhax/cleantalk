<?php Yii::app()->clientScript->registerScriptFile('/js/jquery.autocolumnlist.js'); ?>
<table width="100%" cellspacing="0" cellpadding="0" border="0" style="vertical-align:top">
    <tr>
        <td class="leftmnu" width="20%" valign="top">
            <div style="padding: 0 5px 0 5px;">

                <?php $this->renderPartial('/entity/_left_menu', array('entity' => $entity)); ?>
                <?php $this->renderPartial('/site/login_form', array('model' => new User)); ?>

            </div>
        </td>
        <td valign="top" style="padding: 5px;">
            <?php $this->widget('TopBar', array('breadcrumbs' => $this->breadcrumbs)); ?>

            <div class="text">
                <ul class="list" id="al50">
                    <?php foreach($list as $item) : ?>
                        <?php $title = ProductHelper::GetTitle($item); ?>
                        <li style="margin-bottom: 10px;">
                            <a href="<?=Yii::app()->createUrl('entity/byseries',
                                array('entity' => Entity::GetUrlKey($entity),
                                      'sid' => $item['id'],
                                      'title' => ProductHelper::ToAscii($title)
                                 )); ?>"><?=$title;?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- /content -->
        </td>
        <td width="220" valign="top" style="padding-left: 10px; padding-right: 10px; padding-top: 10px;">
            <?php $this->widget('Banners', array('entity' => $entity)); ?>
        </td>
    </tr>

</table>

<script type="text/javascript">

    $(document).ready(function()
    {
        $('#al50').autocolumnlist({ columns: 2});
    });

</script>
