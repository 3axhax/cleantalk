/*
* Mootools Simple Modal
* Version 1.0
* Copyright (c) 2011 Marco Dell'Anna - http://www.plasm.it
*/
window.addEvent("domready", function(e){
  /* NO Header */
/*
  $("alert-noheader").addEvent("click", function(e){
    e.stop();
    var SM = new SimpleModal({"hideHeader":false, "closeButton":false, "btn_ok":"Закрыть окно", "width":600});
        SM.show({
          "model":"alert",
          "title":"Партнерская программа",
          "contents":"<p>Мы предлагаем сотрудничество всем зарегистрированным пользователям. Разместите на Вашем сайте партнерскую ссылку, баннер проекта Клинтолк или расскажите о нас друзьям в социальных сетях и у Вас появится возможность получать комиссионные отчисления <b>30% с первого платежа</b> подключившегося клиента.</p><p>Начисления происходят на партнерский счет, вывод осуществляется на WMZ-кошелек при накоплении средств более <b>1 тыс. руб.</b> Пользователи проекта имеют возможность <b>оплатить накопленными средствами</b> услуги сервиса Клинтолк.</p>"
        });
  })
 */
 	
	if ($("help_top_words")){
	  $("help_top_words").addEvent("click", function(e){
		e.stop();
		var SM = new SimpleModal({"hideHeader":false, "closeButton":false, "btn_ok":"Закрыть окно", "width":600});
			SM.show({
			  "model":"alert",
			  "title":"Словарь ключевых слов",
			  "contents":'<p>Словарь является вспомогательным механизмом определения релевантности комментариев поступающих от посетителей сайта. За каждое слово совпавшее с одним из ключевых слов сообщение пользователя получает дополнительный балл, за счет этого достигается автоматическая публикация сообщений которые относятся не только к теме обсуждения, но и затрагивают тематику сайта.</p><p>Минимальная длина ключевого слова 3 буквы, максимальная 128. Знаки пробела, тире, точки и запятые не допускаются.</p><p>Количетсво доступных слов - это количество ключевых слов которые используются для определения релевантности комментария относительно всего сайта. К примеру Автомодератор знает всего 559 ключевых слов для вашего сайта, но использует только 30, т.к. это ограничение по тарифу. Чем больше доступно ключевых слов автомодератору, тем точнее работает механизм определения релевантности комментария.</p>'
			});
	  
	  });
	}

	if ($("help_top_words_en")){
	  $("help_top_words_en").addEvent("click", function(e){
		e.stop();
		var SM = new SimpleModal({"hideHeader":false, "closeButton":false, "btn_ok":"Close", "width":600});
			SM.show({
			  "model":"alert",
			  "title":"Keywords dictionary",
			  "contents":'<p>The dictionary is an extra tool to help the service to find a relevance of the new comment. For each word wich matched with the dictionary, message get an extra points. This help automatic approve message that is relative to the site content.</p><p>Keyword should be 3 chars minimal length and 128 chars maximal length.</p><p>If you have any questions please ask us on <a href="mailto:welcome@cleantalk.ru">welcome@cleantalk.ru</a> or by phone +7 351 740 17 30.</p>'
			});
	  });
  	}
	
	if ($("help_stop_list")){
	  $("help_stop_list").addEvent("click", function(e){
		e.stop();
		var SM = new SimpleModal({"hideHeader":false, "closeButton":false, "btn_ok":"Закрыть окно", "width":600});
			SM.show({
			  "model":"alert",
			  "title":"Персональный список стоп-слов",
			  "contents":'<p>Персональный список стоп-слов предназначен для фильтрации сообщений содержащих запрещенные к публикации на сайте слова, к примеру такими словами могут - оскорбления, призывы к межнациональной розни и т.д.</p><p>Кроме персонального списка, сервис CleanTalk использует собственный список стоп-слов, которые содержит более 130 значений для русского и английских языков. Для фильтрации сообщений оба списка (персональный и сервисный) используются одновременно.</p><p>Если у Вас остались вопросы задайте их пожалуйста на <a href="mailto:welcome@cleantalk.ru">welcome@cleantalk.ru</a>, либо по телефону +7 351 740 17 30.</p>'
			});
	  
	  });
	}
	
	if ($("help_stop_list_en")){
	  $("help_stop_list_en").addEvent("click", function(e){
		e.stop();
		var SM = new SimpleModal({"hideHeader":false, "closeButton":false, "btn_ok":"Close", "width":600});
			SM.show({
			  "model":"alert",
			  "title":"Personal list of the stop-words for the site",
			  "contents":'<p>Personal list of the stop-words is designed to filter messages containing prohibited Publication words, for example the following words can - insult, incitement to ethnic hatred, etc.</p><p>In addition to the personal list, service CleanTalk uses its own list of stop words, which contains more than 130 values for the English and Russian languages. To filter both lists (personal and service) are used simultaneously.<p>If you have any questions please ask us on <a href="mailto:welcome@cleantalk.ru">welcome@cleantalk.ru</a> or by phone +7 351 740 17 30.</p> '
			});
	  
	  });
	}
	
    if ($("help_bill")){
	  $("help_bill").addEvent("click", function(e){
		e.stop();
		var SM = new SimpleModal({"hideHeader":false, "closeButton":false, "btn_ok":"Закрыть окно", "width":600});
			SM.show({
			  "model":"alert",
			  "title":"Справка по оплате тарифов CleanTalk",
			  "contents":'<p>При оплате услуг сервиса CleanTalk мы начисляем бонусы на Ваш счет в сервисе, накопленными бонусами можно оплачивать услуги сервиса <b>1 бонус = 1 рублю РФ</b>.</p><p>Виды бонусов:<ul><li><b>За пунктуальность</b> - 10% от суммы платежа за оплату сервиса до даты окочания текущей подписки на сервис.</li><li><b>За длительную подписку</b> - 5% за 6 месяцев подписки, 7% за 9 месяцев подписки, 10% за 12 месяцев подписки.</li></ul></p>'
			});
	  
	  });
	}
    
    if ($("not_connected_sm")){
	  $("not_connected_sm").addEvent("click", function(e){
		e.stop();
		var SM = new SimpleModal({"hideHeader":false, "closeButton":false, "btn_ok":"Закрыть окно", "width":800});
			SM.show({
			  "model":"alert",
			  "title":"Статус сайта",
			  "contents":'<p>Обычно,  с момент настройки антиспам плагина/модуля, требуется не более 1-5 минут для автоматического перехода сайта в статус "Подключен". Если прошло больше указанного времени, используйте служебный email <b>stop_email@example.com</b> для проверки формы комментариев/регистрации, либо обратитесь на форум <a href="/forum">технической поддержки</a>.</p>'
			});
	  
	  });
	}
    if ($("not_connected_sm_en")){
	  $("not_connected_sm_en").addEvent("click", function(e){
		e.stop();
		var SM = new SimpleModal({"hideHeader":false, "closeButton":false, "btn_ok":"Close", "width":700});
			SM.show({
			  "model":"alert",
			  "title":"Web-site status",
			  "contents":'<p>Usually, it takes 1-5 minutes to move the web-site to "Active". If the more time left, please use email <b>stop_email@example.com</b> at comment/signup form or contact the <a href="/forum">technical support forum</a>.</p>'
			});
	  
	  });
	}

});
