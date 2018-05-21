<table width=100% cellspacing=0 cellpadding=5 border=0>
    <tr>
        <?php for($i=1; $i<=4; $i++) : ?>
        <?php $n = $step == $i ? '' : 'n'; ?>
        <td><img src="/pic1/order_step<?=$i; ?><?=$n; ?>.gif" alt="<?=$i; ?>"></td>
        <td class=maintxt><a class=orderpg_curr><?=$ui->item('MSG_ORDER_STEP_'.$i.'_TITLE'); ?></a></td>
        <?php endfor; ?>
    </tr>
</table>
