<?php
	require_once('funs.php');
	session_start();
	check_session();
	$session_name = $_SESSION['username'];
	$row = array();
	$row = get_member_data($session_name);
	$id = $row['id'];
	$name = $row['name'];
	$role = $row['role'];
	$pic = $row['pic'];
	$last_login = $row['last_login'];
	//$last_login = date('jS M Y H:i', strtotime($last_login));
	$timestamp = strtotime($last_login); // Преобразуем строку с датой в метку времени
	//$last_login = date('%e %B %Y %H:%M', strtotime($last_login));
// Форматируем дату и время вручную
	$day = date('j', $timestamp); // Получаем день месяца без ведущего нуля
	$month = date('n', $timestamp); // Получаем номер месяца без ведущего нуля
	$year = date('Y', $timestamp); // Получаем год
	$time = date('H:i', $timestamp); // Получаем время

// Месяцы на русском языке
	$months = [
		1 => 'Января',
		2 => 'Февраля',
		3 => 'Марта',
		4 => 'Апреля',
		5 => 'Мая',
		6 => 'Июня',
		7 => 'Июля',
		8 => 'Августа',
		9 => 'Сентября',
		10 => 'Октября',
		11 => 'Ноября',
		12 => 'Декабря',
	];

// Формируем отформатированную строку
	$last_login = $day . ' ' . $months[$month] . ' ' . $year . ' ' . $time;
	$total_members = get_all_status();
	$core_members = get_vip_status();
	$total_sessions = total_sessions();
	$completed_sessions = completed_sessions();
	$all_posts = get_all_posts();
	
	starter($id,$name,$role,$pic,$last_login,$total_members,$core_members,$total_sessions,$completed_sessions);
?>
			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-home" aria-hidden="true"></i></a></li>
				<li class="active">Центральная страница</li>
			</ol>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Центральная страница</h1>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<i class="fa fa-user fa-4x" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $total_members; ?></div>
							<div class="text-muted">Всего участников</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<i class="fa fa-user-secret fa-4x" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $core_members; ?></div>
							<div class="text-muted">Администраторы</div>
						</div>
					</div>
				</div>
			</div>

			<a href="schedule.php">
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<i class="fa fa-calendar fa-4x" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $total_sessions; ?></div>
							<div class="text-muted">Посещений</div>
						</div>
					</div>
				</div>
			</div>
			</a>
			
			<a href="blog-home.php">
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<i class="fa fa-pencil fa-4x" aria-hidden="true"></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $all_posts; ?></div>
							<div class="text-muted">Посты блога</div>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Последние записи</h1>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<?php show_home_posts(); ?>		
			</div>
		</div><!--/.row-->

		<script>
			$(document).ready(function()
			{
				$('.menu').on("click",".menu",function(e){ 
  				e.preventDefault(); // cancel click
  				var page = $(this).attr('href');   
  				$('.menu').load(page);
				});
			});
		</script>
<?php
	at_bottom();