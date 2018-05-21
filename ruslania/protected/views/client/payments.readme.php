Payments type to string:


<xsl:choose>
    <xsl:when test="CustomerOrder/PaymentTypeID=1">Инвойс</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=2">Банк SAMPO</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=3">Кредитка</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=4">Банк NORDEA</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=5">Кредитка</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=6">Банк OKO</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=7">Инвойс</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=8">PayPal</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=10">Pay by check</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=11">Pay by check</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=12">PayPal</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=13">Предоплата на банковский счет Руслании</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=14">Оплата на банковский счет в России</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=15">Предоплата на банковский счет Руслании</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=16">Оплата на банковский счет в России</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=17">Оплата на банковский счет в Великобритании</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=18">Оплата на банковский счет в Великобритании</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=19">Оплата на банковский счет на Украине</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=20">Оплата на банковский счет на Украине</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=21">Предоплата на банковский счет в Германии</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=22">Предоплата на банковский счет в Германии</xsl:when>
    <xsl:when test="CustomerOrder/PaymentTypeID=23">Google Checkout</xsl:when>
    <xsl:otherwise>
        <xsl:value-of select="CustomerOrder/PaymentTypeID"/>
    </xsl:otherwise>
</xsl:choose>

