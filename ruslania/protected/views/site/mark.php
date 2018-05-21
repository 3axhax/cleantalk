<div data-bind="visible:  MarkItems().length > 0">
    <div style="margin-bottom: 6px; width: 100%; padding: 8px; color: #FFFFFF; background-color: #4D8152">
        <b>Товары, взятые на заметку:</b>
    </div>
    <table width="100%" cellspacing="1" cellpadding="5" border="0" class="cart1">
        <thead>
        <tr>
        <th width="100%" valign="middle" class="cart1header1">Название</th>
        <th valign="middle" nowrap="" align="center" class="cart1header1">Наличие</th>
        <th valign="middle" nowrap="" align="center" class="cart1header1">В корзину</th>
        <th valign="middle" nowrap="" align="center" class="cart1header1">В&nbsp;заявки</th>
        <th valign="middle" align="center" class="cart1header1">Удалить</th>
        </thead>

        <tbody data-bind="foreach: MarkItems">
        <tr>
            <td width="100%" valign="middle" class="cart1contents1">
                <img width="31" height="31"
                     align="middle" alt=""
                     style="vertical-align: middle"
                     src="/pic1/cart_ibook.gif">&nbsp;&nbsp;<a
                    title="Получить дополнительную информацию об этом товаре"
                    href="http://www.ruslania.com/context-321/entity-1/details-128410.html"
                    class="maintxt1" data-bind="text: Title"></a></td>
            <td valign="middle" align="center" class="cart1contents1">
                заканчивается в магазине
            </td>
            <td valign="middle" align="center" class="cart1contents1">
                <input type="checkbox" class="cart1contents1" name="cni[13660370]">
            </td>
            <td valign="middle" align="center" class="cart1contents1">
                <input type="checkbox" disabled="" class="cart1contents1" name="coi[13660370]">
            </td>
            <td valign="middle" align="center" class="cart1contents1">
                <input type="checkbox" class="cart1contents1" name="cdi[13660370]">
            </td>
        </tr>
        </tbody>
    </table>
</div>
