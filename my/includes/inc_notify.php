<?php
// Проверка доступа
if (!$this->check_access(null, true)) {
    if (!$this->app_mode) {
        $this->url_redirect('session', null, true);
    }
}

$this->get_lang($this->ct_lang, 'Antispam');
$this->items_per_page = array(10,100);
$this->smarty_template = 'includes/general.html';
$this->page_info['template'] = 'antispam/notify.html';
$this->page_info['container_fluid'] = true;
$this->page_info['scripts'] = array(
    '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js',
    '//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js',
    '/my/js/notify.js?16.05.2018'
);
$this->page_info['head']['title'] = $this->lang['l_site_notification'];
if($this->user_info['moderate']==0){
    $this->page_info['show_modal'] = 1;
}

if ($this->cp_mode != 'antispam') {
	$this->cp_mode = 'antispam';
	$this->page_info['cp_mode'] = $this->cp_mode;
	setcookie('cp_mode', $this->cp_mode, strtotime("+365 day"), '/');
}

// Фильтры
$filters = array();
$url = array();

// Фильтр по текущему пользователю
$filters[0] = sprintf('n.user_id=%d', $this->user_id);

// Фильтр по сайту
if(!empty($_GET['service'])){

	// Выбираем список делегированных сайтов
    $granted_services = $this->get_granted_services($this->user_id);
    $this->page_info['granted_services'] = $granted_services;

    // Массив с id делегированных сервисов
    $this->granted_services_ids = array();
    if($granted_services){
        foreach($granted_services as $onegrservice)
            $this->granted_services_ids[] = $onegrservice['service_id'];
    }
    
    if(in_array($_GET['service'], $this->granted_services_ids)){
        unset($filters[0]); // Уберем фильтр по текущему пользователю, для сайтов с доступом.
    }
    $filters[] = sprintf('n.service_id=%d',intval($_GET['service']));
    $url[] = 'service='.intval($_GET['service']);
}

// Фильтр по событию
if(isset($_GET['event'])){
	if($_GET['event']=='allowed'){
		$filters[] = 'n.allow=1';
	}
	if($_GET['event']=='denied'){
		$filters[] = 'n.allow=0';
	}
	$url[] = 'event='.$_GET['event'];
}

// Фильтр по способу уведомлений
if(!empty($_GET['method'])){
	if($_GET['method']=='email'){
		$filters[] = "n.type='EMAIL'";
	}
	if($_GET['method']=='url'){
		$filters[] = "n.type='URL'";
	}
	if($_GET['method']=='apn'){
		$filters[] = "n.type='APN'";
	}
	$url[] = 'method='.$_GET['method'];
}

// Соберем все фильтры
$sql_filter = implode(' AND ', $filters);

 // Постраничная навигация                            
$pages = array();
$current_page = 1; // Страница по умолчанию
$visible_pages = 5; // Количество отображаемых страниц
$page_from = 1; // Номер первой страницы
$this->page_info['items_per_page_list'] = $this->items_per_page;
// Количество записей читаем из куки
if(isset($_COOKIE['nas_ipp']) && in_array($_COOKIE['nas_ipp'], $this->items_per_page)){
    $items_per_page = intval($_COOKIE['nas_ipp']);
}else{
    $items_per_page = 100; // Количество записей по умолчанию
}
$this->page_info['items_per_page'] = $items_per_page;


// Установим количество записей на странице, если значение из списка разрешенных
if(isset($_GET['items_per_page']) && in_array($_GET['items_per_page'], $this->items_per_page)){
    $items_per_page = intval($_GET['items_per_page']);
    setcookie('nas_ipp', $items_per_page, time() + 365*24*60*60, '/', $this->cookie_domain);
}

// Установим текущую страницу
if(isset($_GET['page'])){
    $current_page = intval($_GET['page']);
    if($current_page<1){
        $current_page = 1;
    }
}

// Считаем кол-во записей в таблице
$sql = 'SELECT count(*) as count FROM antispam_notifications n WHERE '.$sql_filter;
$row = $this->db->select($sql);
$records_count = $row['count'];

// Максимальное кол-во страниц
$total_pages_num = ceil($records_count / $items_per_page);

// Переопределяем текущую страницу, если она больше максимальной
if($total_pages_num<$current_page && $total_pages_num>0){
    $current_page = $total_pages_num;
}
$this->page_info['total_pages'] = $total_pages_num;
$this->page_info['current_page'] = $current_page;
$this->page_info['records_found'] = sprintf($this->lang['l_records_found'], number_format($records_count, 0, ',', ' '));
// Определяем номера страниц для показа
if ($current_page > floor($visible_pages/2)){
    $page_from = max(1, $current_page-floor($visible_pages/2));
}
if ($current_page > $total_pages_num-ceil($visible_pages/2)){
    $page_from = max(1, $total_pages_num-$visible_pages+1);
}                            
$page_to = min($page_from+$visible_pages-1, $total_pages_num);
for ($i = $page_from; $i<=$page_to; $i++){
    $pages[]=$i;
}
$sql_limit = sprintf(' LIMIT %d, %d', $current_page*$items_per_page-$items_per_page, $items_per_page);
$sql = 'SELECT n.notification_id, n.user_id, n.service_id, n.allow, n.type, n.period, n.notification_url, IF(n.last_sent, DATE_FORMAT(n.last_sent,"%b %d, %Y %H:%i:%s"),"&ndash;") as last_sent, n.notifications_sent, n.status, s.name, s.hostname, s.favicon_url, s.favicon_update
        FROM antispam_notifications n 
        LEFT JOIN services s ON s.service_id = n.service_id 
        WHERE '.$sql_filter.'
        ORDER BY n.notification_id DESC '.$sql_limit;
$rows = $this->db->select($sql, true);
if(is_array($rows)){
    foreach ($rows as &$row) {
    	$row['favicon'] = Favicon::get_icon_url($row); 
        $row['service_name'] = $this->get_service_visible_name($row);
    }
}

$this->page_info['url'] = '/my/notify';
if(!empty($url)){
    $this->page_info['url'] .= '?'.implode('&', $url);
}

// Выборка сайтов пользователя для фильтра
$services = $this->db->select(sprintf("SELECT service_id, hostname, favicon_url, favicon_update FROM services WHERE user_id = %d AND product_id = %d", $this->user_id, cfg::product_antispam), true);
if(is_array($services)){
	foreach ($services as &$service) {
		$service['favicon'] = Favicon::get_icon_url($service);
		$user_hostnames[] = $service['hostname'];
	}
}
$this->page_info['services'] = $services;
if(count($services)>1){
	$this->page_info['l_url_hint'] = sprintf($this->lang['l_url_hint'], 'one of your '.count($services).' sites');
}else{
	$this->page_info['l_url_hint'] = sprintf($this->lang['l_url_hint'], $services[0]['hostname']);
}

if(isset($_GET['ajax'])){
	header("Content-type: application/json; charset=UTF-8");
    $result = new stdClass();

	if($_GET['ajax']=='new'){
		$records_exist = 0;
		$records_added = 0;
		if(!empty($_POST['service'])){
			$add_services[] = intval($_POST['service']);
		}else{
			foreach ($services as $service) {
				$add_services[] = intval($service['service_id']);
			}
		}
		if(!empty($_POST['event'])){
			if($_POST['event']=='allowed'){
				$add_events[] = 1;
			}else{
				$add_events[] = 0;
			}
		}else{
			$add_events = array(1,0);
		}
		if(!empty($_POST['method'])){
			if($_POST['method']=='email'){
				$add_type = 'EMAIL';
				$url = '';
			}elseif($_POST['method']=='apn'){
				$add_type = 'APN';
				$url = '';
			}elseif($_POST['method']=='url'){
				$add_type = 'URL';
				if(!empty($_POST['url'])){
					$url = filter_var($_POST['url'], FILTER_VALIDATE_URL);
					if($url){
						$parse_url = parse_url($url);
						if(!in_array($parse_url['host'], $user_hostnames)){
							$result->error = $this->lang['l_url_error_host'];
							echo json_encode($result);
						    exit();
						}
					}else{
						$result->error = $this->lang['l_url_error'];
						echo json_encode($result);
					    exit();
					}
				}else{
					$result->error = $this->lang['l_url_error'];
					echo json_encode($result);
				    exit();
				}
			}else{
				exit;
			}
		}else{
			exit;
		}
		
		foreach ($add_services as $add_service) {
			foreach ($add_events as $add_event) {
				$sql = sprintf("SELECT count(*) as 'count' FROM antispam_notifications 
						WHERE user_id=%d AND service_id=%d AND allow=%d AND type=%s",
					$this->user_id,
					$add_service,
					$add_event,
					$this->stringToDB($add_type)
				);
				$row = $this->db->select($sql);
				if(!empty($row['count'])){
					$records_exist++;
				}else{
					$sql = sprintf("INSERT INTO antispam_notifications SET user_id=%d, service_id=%d, allow=%d, type=%s, notification_url=%s, status='ACTIVE';",
						$this->user_id,
						$add_service,
						$add_event,
						$this->stringToDB($add_type),
						$this->stringToDB($url)
					);
					$this->db->run($sql);
					$records_added++;
				}
				
			}
			
		}
		$result->records_exist = $records_exist;
		$result->records_added = $records_added;
	}elseif($_GET['ajax']=='delete'){
		if(!empty($_POST['ids'])){
			$ids = array_map('intval', explode(',', $_POST['ids']));
			$sql = sprintf('SELECT notification_id FROM antispam_notifications WHERE user_id=%d AND notification_id IN (%s)',$this->user_id, implode(',', $ids));
			$rows = $this->db->select($sql,true);
			if(is_array($rows)){
				$ids = array();
				foreach ($rows as $row) {
					$ids[]=$row['notification_id'];
				}
				$sql = sprintf('DELETE FROM antispam_notifications WHERE notification_id IN (%s);', implode(',', $ids));
				$this->db->run($sql);
				$result = 'ok';
			}
		}

	}elseif($_GET['ajax']=='enable' || $_GET['ajax']=='disable'){
		if($_GET['ajax']=='enable'){
			$status = 'ACTIVE';
		}
		if($_GET['ajax']=='disable'){
			$status = 'DISABLED';
		}
		if(!empty($_POST['ids'])){
			$ids = array_map('intval', explode(',', $_POST['ids']));
			$sql = sprintf('SELECT notification_id FROM antispam_notifications WHERE user_id=%d AND notification_id IN (%s)',$this->user_id, implode(',', $ids));
			$rows = $this->db->select($sql,true);
			if(is_array($rows)){
				$ids = array();
				foreach ($rows as $row) {
					$ids[]=$row['notification_id'];
				}
				$sql = sprintf('UPDATE antispam_notifications SET status=%s WHERE notification_id IN (%s);', $this->stringToDB($status), implode(',', $ids));
				$this->db->run($sql);
				$result = 'ok';
			}
		}
		
	}else{
	    $result->rows = $rows;
	    $result->total_pages = $this->page_info['total_pages'];
	    $result->page = $this->page_info['current_page'];
	    $result->records_count = $this->page_info['records_found'];
	    $result->pages = $pages;
	    $result->url = $this->page_info['url'];
	}
    echo json_encode($result);
    exit();
}else{
    $this->page_info['rows'] = $rows;
    $this->page_info['pages'] = $pages;
}

