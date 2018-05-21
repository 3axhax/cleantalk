<div class="container content_books">
    <div class="row">
        <div class="span10 listgoods" style="float: right;">

            <h1 class="titlename">Товары раздела: <?=((!$cid) ? Entity::GetTitle($entity) : $title_cat); ?></h1>  

            <div class="sortbox">
                <form method="GET">
                    <?php $value = SortOptions::GetDefaultSort(@$_GET['sort']) ?>
                    <?= CHtml::dropDownList('sort', $value, SortOptions::GetSortData(), array('onchange' => '$(\'.sortbox form\').submit()', 'style'=>'width: auto;')); ?>
                </form>
            </div>

            <ul class="items">
                <?php foreach ($items as $row) : ?>
                    <?php
					$item = Product::GetProduct($entity, $row['id']);
                    $item['entity'] = $entity;
                    $key = 'itemlist_' . $entity . '_' . $item['id'];
                    ?>
                    <li>
                        <?php $this->renderPartial('test123_item', array('item' => $item, 'entity' => $entity, 'isList' => true)); ?>
                    </li>
                <?php endforeach; ?>
            </ul>


            <?php if (count($items) > 0) $this->widget('SortAndPaging', array('paginatorInfo' => $paginatorInfo)); ?>
        </div>
        <div class="span2">
            <?php if (!empty($categoryList)) : ?>
                <h2 class="cattitle">Категории:</h2>
                <ul class="left_list divider">
                    <?php

                    function getSubCategoryes($entity, $cid, $lvl = 1) {

                        $rows = Category::exists_subcategoryes($entity, $cid);

                        if (count($rows)) {

                            echo '<ul style="margin-left: ' . ($lvl * 10) . 'px" class="subcat sc' . $cid . '" rel="' . $cid . '">';
                            foreach ($rows as $cat) :
                                echo '<li>';
                                if (count(Category::exists_subcategoryes($entity, $cat['id']))) {
                                    echo '<a href="javascript:;" class="open_subcat" onclick="show_sc($(\'ul.sc' . $cat['id'] . '\'), $(this))"></a>';
                                }
                                echo '<a href="' . Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey($entity), 'cid' => $cat['id'], 'title' => ProductHelper::ToAscii($cat['title_en']))) . '">' . ProductHelper::GetTitle($cat) . '</a>';



                                echo '</li>';
                                getSubCategoryes($entity, $cat['id'], $lvl + 1);
                            endforeach;

                            echo '</ul>';
                        }
                    }

                    foreach ($categoryList as $cat) :
                        ?>
                        <li>
                            <? if (count(Category::exists_subcategoryes($entity, $cat['id']))) {?>
                            <a href="javascript:;" class="open_subcat" onclick="show_sc($('ul.sc<?= $cat['id'] ?>'), $(this))"></a>
                            <?} ?>
                            <a href="<?= Yii::app()->createUrl('entity/list', array('entity' => Entity::GetUrlKey($entity), 'cid' => $cat['id'], 'title' => ProductHelper::ToAscii($cat['title_en']))); ?>"><?= ProductHelper::GetTitle($cat); ?></a>



                            <!--                        (--><?//=$cat['items_count']; ?><!-- / --><?//=$cat['avail_items_count']; ?><!--)-->
                        </li>
                        <?getSubCategoryes($entity, $cat['id'], 1);?>
                    <?php endforeach; ?>
                </ul>

				<a href="<?=Yii::app()->createUrl('entity/categorylist', array('entity' => Entity::GetUrlKey($entity))); ?>" class="order_start" style="width: 100%">Показать все категории</a>

                <div style="height: 47px"></div>
            <?php endif; ?>

            <h2 class="filter">Параметры поиска:</h2>

            <form method="post" action="" class="filter">
                <input type="hidden" name="entity_val" class="entity_val" value="<?= $entity ?>"/>
                <input type="hidden" name="cid_val" class="cid_val" value="<?= $cid ?>"/>
                <input type="hidden" name="sort" class="sort" value="<?= $_GET['sort'] ?>"/>
                <div class="form-row">
                    <label class="title">Поиск в разделе <?= Entity::GetTitle($entity) ?></label>
                    <input type="text" class="search inp" placeholder="По названию,ISBN" name="name_search" onkeyup="if ($(this).val().length > 2) { show_result_count($(this)); } else { $('.box_select_result_count').hide(1); }"/>
                    <div class="box_select_result_count">
                        <div class="arrow"><img src="/new_img/arrow_select.png" alt=""></div> Выбрано: <span class="res_count">20</span><a  href="javascript:;" onclick="show_items()">Показать</a>
                    </div>
                </div>
                <?php if (!empty($authors)) { ?>
                    <div class="form-row">
                        <div class="box_select_result_count">
                            <div class="arrow"><img src="/new_img/arrow_select.png" alt=""></div> <div class="close" onclick="$(this).parent().hide()">x</div>Выбрано: <span class="res_count">20</span><a href="javascript:;" onclick="show_items()">Показать</a>
                        </div>
                        <label class="title">Автор</label>

                        <div class="dd_box_select" style="z-index: 9">

                            <div class="arrow_d" onclick="$('.list_dd', $(this).parent()).toggle()"></div>
                            <input type="hidden" name="author" value="0">
                            <div class="text" onclick="$('.list_dd', $(this).parent()).toggle()">
                                <span><?if ($filter_data['author'] == '' OR $filter_data['author'] == '0') { echo 'Все'; } else { $row = CommonAuthor::GetById($filter_data['author']); echo $row['title_' . Yii::app()->language]; }?></span>
                            </div>
                            <div class="list_dd authors_dd">
                                <div class="items">
                                    <div class="rows">
                                        <div class="item" rel="0" onclick="select_item($(this), 'author')">Все</div>
                                        <?php
                                        foreach ($authors as $author => $binfo) {
                                            $row = CommonAuthor::GetById($binfo['author_id']);
                                            if (!$row['id'] OR $row['id'] == '0')
                                                continue;
                                            $name_publ = $row['title_' . Yii::app()->language];
                                            if (!trim($name_publ))
                                                continue;

											$selact = ' selact';

											if ($row['id'] != $filter_data['author']) {
												$selact = '';
											}

                                            echo '<div class="item'.$selact.'" rel="' . $row['id'] . '" onclick="select_item($(this), \'author\')">' . $name_publ . '</div>';
                                        }
                                        ?>

                                    </div>
                                    <div class="load_items"></div>
                                </div>
                            </div>
                        </div>

                    </div> <?php } ?>
                <div class="form-row"><div class="box_select_result_count">
                        <div class="arrow"><img src="/new_img/arrow_select.png" alt=""></div> <div class="close" onclick="$(this).parent().hide()">x</div>Выбрано: <span class="res_count">20</span><a  href="javascript:;" onclick="show_items()">Показать</a>
                    </div>
                    <label class="title">Наличие</label>
                    <div class="dd_box_select" style="z-index: 8">

                        <div class="arrow_d" onclick="$('.list_dd', $(this).parent()).toggle()"></div>
                        <input type="hidden" name="avail" value="0">
                        <div class="text" onclick="$('.list_dd', $(this).parent()).toggle()"><span><?=(($filter_data['avail'] == 0) ? 'Все' : 'В наличии' ); ?></span></div>
                        <div class="list_dd">
                            <div class="items">
                                <div class="rows">

                                    <div class="item<?=(($filter_data['avail'] == 0) ? ' selact' : '' ); ?>" rel="0" onclick="select_item($(this), 'avail')">Все</div>
                                    <div class="item<?=(($filter_data['avail'] == 1) ? ' selact' : '' ); ?>" rel="1" onclick="select_item($(this), 'avail')">В наличии</div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div><? if ($filter_year[1]) : ?>
                <div class="form-row"><div class="box_select_result_count">
                        <div class="arrow"><img src="/new_img/arrow_select.png" alt=""></div> <div class="close" onclick="$(this).parent().hide()">x</div>Выбрано: <span class="res_count">20</span> <a  href="javascript:;" onclick="show_items()">Показать</a>
                    </div>

                    <label class="title">Год</label>

                    <input type="text" value="" class="inp_mini year_inp_mini inp" name="year_min" />-<input type="text" value="" name="year_max" class="inp_max year_inp_max inp" />

                    <div id="slider_year"></div>
					<?
						$max_year = $filter_year[1];
						$min_year = $filter_year[0];



						if ($filter_data['ymin'] != '') {

							$filter_year[0] = $filter_data['ymin'];

						}


						if ($filter_data['ymax'] != '') {

							$filter_year[1] = $filter_data['ymax'];

						}
					?>
                    <script>

                        $(document).ready(function () {

                            var slider = document.getElementById('slider_year');

                            noUiSlider.create(slider, {
                                start: [<?= $filter_year[0] ?>, <?= $filter_year[1] ?>],
                                connect: true,
                                range: {
                                    'min': <?= $min_year ?>,
                                    'max': <?= $max_year ?>
                                }
                            });

                            slider.noUiSlider.on('set', function () {

                                show_result_count($(slider));

                            });

                            slider.noUiSlider.on('update', function (values, handle) {

                                var value = values[handle];

                                if (handle) {
                                    $('.year_inp_max').val(Math.round(value));
                                } else {
                                    $('.year_inp_mini').val(Math.round(value));
                                }


                            });

                        })

                    </script>

                </div>
				<? endif;?>
                <?php if (!empty($pubs)) { ?>
                    <div class="form-row"><div class="box_select_result_count">
                            <div class="arrow"><img src="/new_img/arrow_select.png" alt=""></div> <div class="close" onclick="$(this).parent().hide()">x</div>Выбрано: <span class="res_count">20</span><a  href="javascript:;" onclick="show_items()">Показать</a>
                        </div>
                        <label class="title">Издательтство</label>

                        <div class="dd_box_select" style="z-index: 7">

                            <div class="arrow_d" onclick="$('.list_dd', $(this).parent()).toggle()"></div>
                            <input type="hidden" name="izda" value="0">
                            <div class="text" onclick="$('.list_dd', $(this).parent()).toggle()">
                                <span><?if ($filter_data['izda'] == '' OR $filter_data['izda'] == '0') { echo 'Все'; } else { $row = Publisher::GetByID($entity, $filter_data['izda']); echo $row['title_' . Yii::app()->language]; }?></span>
                            </div>
                            <div class="list_dd izda_dd">
                                <div class="items">
                                    <div class="rows">
                                        <div class="item" rel="0" onclick="select_item($(this), 'izda')">Все</div>
                                        <?php
                                        foreach ($pubs as $pub => $binfo) {
                                            $row = Publisher::GetByID($entity, $binfo['publisher_id']);
                                            if (!$row['id'] OR $row['id'] == '0')
                                                continue;
                                            $name_publ = $row['title_' . Yii::app()->language];

                                            if (!$name_publ) {
                                                $name_publ = $row['title_en'];
                                            }

											$selact = ' selact';

											if ($row['id'] != $filter_data['izda']) {
												$selact = '';
											}

                                            echo '<div class="item'.$selact.'" rel="' . $row['id'] . '" onclick="select_item($(this), \'izda\')">' . $name_publ . '</div>';
                                        }
                                        ?>

                                    </div>
                                    <div class="load_items"></div>
                                </div>
                            </div>
                        </div>
                    </div> <?php } if (!empty($series)) { ?>

                    <div class="form-row"><div class="box_select_result_count">
                            <div class="arrow"><img src="/new_img/arrow_select.png" alt=""></div> <div class="close" onclick="$(this).parent().hide()">x</div>Выбрано: <span class="res_count">20</span><a  href="javascript:;" onclick="show_items()">Показать</a>
                        </div>
                        <label class="title">Серия</label>

                        <div class="dd_box_select" style="z-index: 6">

                            <div class="arrow_d" onclick="$('.list_dd', $(this).parent()).toggle()"></div>
                            <input type="hidden" name="seria" value="0">
                            <div class="text" onclick="$('.list_dd', $(this).parent()).toggle()">
                                <span><?if ($filter_data['seria'] == '' OR $filter_data['seria'] == '0') { echo 'Все'; } else { $row = Series::GetByIds($entity, array($entity, $filter_data['seria'])); echo $row[0]['title_' . Yii::app()->language]; }?></span>
                            </div>
                            <div class="list_dd seria_dd">
                                <div class="items">
                                    <div class="rows">
                                        <div class="item" rel="0" onclick="select_item($(this), 'seria')">Все</div>
                                        <?php
                                        foreach ($series as $seria => $binfo) {
                                            $row = Series::GetByIds($entity, array($binfo['series_id']));
                                            if (!$row[0]['id'] OR $row[0]['id'] == '0')
                                                continue;
                                            $name_publ = $row[0]['title_' . Yii::app()->language];

                                            if (!$name_publ) {
                                                $name_publ = $row[0]['title_en'];
                                            }

											$selact = ' selact';

											if ($row[0]['id'] != $filter_data['seria']) {
												$selact = '';
											}

                                            echo '<div class="item'.$selact.'" rel="' . $row[0]['id'] . '" onclick="select_item($(this), \'seria\')">' . $name_publ . '</div>';
                                        }
                                        ?>

                                    </div>
                                    <div class="load_items"></div>
                                </div>
                            </div>
                        </div>

                    </div> <?php } ?>

					<? if ($filter_year[3]) : ?>


                <div class="form-row"><div class="box_select_result_count">
                        <div class="arrow"><img src="/new_img/arrow_select.png" alt=""></div> <div class="close" onclick="$(this).parent().hide()">x</div>Выбрано: <span class="res_count">20</span><a  href="javascript:;" onclick="show_items()">Показать</a>
                    </div>
                    <label class="title">Цена</label>
                    <input type="text" value="" class="inp_mini cost_inp_mini inp" name="min_cost" />-<input type="text" value="" class="inp_max cost_inp_max inp" name="max_cost"/>
                    <div id="slider_cost"></div>

                    <script>

						<?
							$max_cost = $filter_year[3];
							$min_cost = $filter_year[2];



							if ($filter_data['cmin'] != '') {

								$filter_year[2] = $filter_data['cmin'];

							}


							if ($filter_data['cmax'] != '') {

								$filter_year[3] = $filter_data['cmax'];

							}
						?>

                        $(document).ready(function () {

                            var slider_cost = document.getElementById('slider_cost');

                            noUiSlider.create(slider_cost, {
                                start: [<?= $filter_year[2] ?>, <?= $filter_year[3] ?>],
                                connect: true,
                                range: {
                                    'min': <?=$min_cost; ?>,
                                    'max': <?=$max_cost; ?>
                                }
                            });

                            slider_cost.noUiSlider.on('set', function () {

                                show_result_count($(slider_cost));

                            });

                            slider_cost.noUiSlider.on('update', function (values, handle) {

                                var value = values[handle];

                                if (handle) {
                                    $('.cost_inp_max').val(value);
                                } else {
                                    $('.cost_inp_mini').val(value);
                                }


                            });

                        })

                    </script>
                </div>
				<? endif; ?>
                <?php if (!empty($bgs)) { ?>
                    <div class="form-row bindings">
                        <div class="box_select_result_count">
                            <div class="arrow"><img src="/new_img/arrow_select.png" alt=""></div>
                            <div class="close" onclick="$(this).parent().hide()">x</div>
                            Выбрано: <span class="res_count">20</span><a  href="javascript:;" onclick="show_items()">Показать</a>
                        </div>
                        <label class="title">Тип</label>


                        <?php
                        foreach ($bgs as $bg => $binfo) {
                            $row = Binding::GetBinding($entity, $binfo['binding_id']);
                            $title = 'title_' . Yii::app()->language;
                            if ($entity == 22 OR $entity == 24) {
                                $row = Media::GetMedia($entity, $binfo['media_id']);
                                $title = 'title';
                            }
                            if (!$row['id'])
                                continue;

							$sel = '';

							if (in_array($row['id'], $filter_data['binding_id'])) {
								$sel = 'checked="checked"';
							}

                            echo '<label><input '.$sel.' type="checkbox" class="" name="binding_id[]" value="' . $row['id'] . '" onchange="show_result_count($(this))"/> ' . str_replace('/', ' / ', $row[$title]) . '</label>';
                        }
                        ?>


                    </div><?php } ?>
            </form>
        </div>
    </div>
</div>