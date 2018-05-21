<table cellspacing="0" cellpadding="0" border="0" class="divider myheader">
    <tbody>
    <tr>
        <td width="30" align="center"><img width="14" height="14" src="/pic1/arr2.gif"></td>
        <td class="leftmnutitle1-table-txt"><?=$ui->item('LEFT_ADVANCED_FEATURES'); ?></td>
    </tr>
    </tbody>
</table>
<ul class="left_list divider text">
    <li>
        <a href="<?=Yii::app()->createUrl('entity/categorylist', array('entity' => Entity::GetUrlKey($entity))); ?>">
            <?=$ui->item('A_LEFT_AUDIO_CATTREE_PROPERTYLIST'); ?>
        </a>
    </li>

    <?php if($entity != Entity::PERIODIC) : ?>
    <li>
        <a href="<?=Yii::app()->createUrl('entity/publisherlist', array('entity' => Entity::GetUrlKey($entity))); ?>">
            <?=$ui->item('A_LEFT_AUDIO_AZ_PROPERTYLIST_PUBLISHERS'); ?>
        </a>
    </li>
    <?php endif; ?>

    <?php
        $series = array(Entity::BOOKS, Entity::SHEETMUSIC, Entity::SOFT, Entity::MUSIC);
        if(in_array($entity, $series)) : ?>
    <li>
        <a href="<?=Yii::app()->createUrl('entity/serieslist', array('entity' => Entity::GetUrlKey($entity))); ?>">
            <?=$ui->item('A_LEFT_BOOKS_SERIES_PROPERTYLIST'); ?>
        </a>
    </li>
    <?php endif; ?>


    <?php if($entity != Entity::PERIODIC) : ?>
    <li>
        <a href="<?=Yii::app()->createUrl('entity/authorlist', array('entity' => Entity::GetUrlKey($entity))); ?>">
            <?=$ui->item('A_LEFT_AUDIO_AZ_PROPERTYLIST_AUTHORS'); ?>
        </a>
    </li>
    <?php endif; ?>

    <?php $performers = array(Entity::AUDIO, Entity::MUSIC);
    if(in_array($entity, $performers)) : ?>
    <li>
        <a href="<?=Yii::app()->createUrl('entity/performerlist', array('entity' => Entity::GetUrlKey($entity))); ?>">
            <?=$ui->item('A_LEFT_AUDIO_AZ_PROPERTYLIST_PERFORMERS'); ?>
        </a>
    </li>
    <?php endif; ?>

    <?php if($entity == Entity::VIDEO) : ?>
        <li>
            <a href="<?=Yii::app()->createUrl('entity/actorlist', array('entity' => Entity::GetUrlKey($entity))); ?>">
                <?=$ui->item('A_LEFT_VIDEO_AZ_PROPERTYLIST_ACTORS'); ?>
            </a>
        </li>
        <li>
            <a href="<?=Yii::app()->createUrl('entity/directorlist', array('entity' => Entity::GetUrlKey($entity))); ?>">
                <?=$ui->item('A_LEFT_VIDEO_AZ_PROPERTYLIST_DIRECTORS'); ?>
            </a>
        </li>
    <?php endif; ?>
</ul>
