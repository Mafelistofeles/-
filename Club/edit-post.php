<?php
	require_once('funs.php');
	$post_id = $_GET['id'];
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
	
	starter($id,$name,$role,$pic,$last_login,$total_members,$core_members,$total_sessions,$completed_sessions);
?>
<?php	

	$query = "SELECT * FROM blog_posts WHERE id = '$post_id'";
	$result = mysqli_query($con,$query);

	if (mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_assoc($result))
		{
            $postTitle = $row['postTitle'];
            $description = $row['description'];
            $content = $row['content'];
            $catinfo = $row['catinfo'];                    
		}	
	}
	else
	{
		echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Ошибка, не удалось получить информацию о публикации, повторите попытку</span></div>';
		die();
	}
?>
	
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
			<li><a href="blog-home.php">Блог</a></li>
			<li class="active">Редактировать пост</li>
		</ol>
	</div><!--/.row-->

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Редактировать пост</h1>
		</div>
	</div><!--/.row-->

	<div class="row">
		<div class="error">
			<?php edit_post($post_id); ?>
		</div>
		<div class="col-lg-12">
			<form class="form-signin" method="post" action="">
			<div class="col-lg-4">
				<label for="postTitle">Заголовок поста</label>
				<input type="text" value="<?php echo $postTitle; ?>" name="postTitle" placeholder="Заголовок" class="form-control" required autofocus>
				<br>
				<label for="description">Текст</label>
				<textarea name="description" rows="7" cols="60" maxlength="250" placeholder="Пометка" id="description" class="form-control space" required><?php echo $description; ?></textarea>
				<br>
				<label for="content">Тема</label><br>
				<select class="form-control" name="cats">
					<option name="<?php echo $catinfo; ?>" value="<?php echo $catinfo; ?>"><?php echo $catinfo; ?></option>
    				<option name="Uncategorised" value="Uncategorised">Другое</option>
				   	<option name="Technology" value="Technology">Технология</option>
				  	<option name="Lifestyle" value="Lifestyle">Образ жизни</option>
				   	<option name="News" value="News">Новости</option>
				   	<option name="Education" value="Education">Образование</option>
				   	<option name="Nature" value="Nature">Природа</option>
				   	<option name="Health" value="Health">Здоровье</option>
				   	<option name="Programming" value="Programming">Программирование</option>
  				</select>
			</div>
			<div class="col-lg-8">
					<label for="content">Содержимое поста</label>
					<textarea name="content" placeholder="Текст" id="content" class="form-control space" required><?php echo $content; ?></textarea>
					<div class="text-center">
				<button class="btn btn-lg btn-primary" name="update" type="submit" id="update">Обновить пост</button>
			</div>
			</div>
					
			</form>
	</div>
</div><!--/.row-->
<?php
	at_bottom();