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
	
	starter($id,$name,$role,$pic,$last_login,$total_members,$core_members,$total_sessions,$completed_sessions);
?>
			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
				<li class="active">Изображение профиля</li>
			</ol>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Изменить изображение профиля</h1>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="error">
				<?php update_pic($id); ?>
			</div>
			<div class="col-lg-offset-2 col-lg-4">
				<img src="<?php echo $pic; ?>" height="200px" width="200px" class="img-responsive img-circle"><br>
				<h4><?php echo $name; ?></h4>
			</div>
			<div class="col-lg-4">
			<div class="panel panel-info">
					<div class="panel-heading">
						Загрузить картинку
					</div>
					<div class="panel-body">
				<form action="" role="form" method="POST" class="form-signin" enctype="multipart/form-data">
					<label for="file">Выберите изображение</label><br>
	         		<input type="file" name="image"><br>
	         		</div>
					<div class="panel-footer">
	         		<button class="btn btn-primary" name="add_event" type="submit">Применить</button>&nbsp;&nbsp;
					<a type="button" class="btn btn-default" href="home.php" class="btn btn-default">Отмена</a>
					</div>
      			</form>
			</div>
		</div>
<?php
	at_bottom();
	function update_pic($id)
	{
		global $con;
		if(isset($_FILES['image']))
		{
	      $errors= array();
	      $file_name = $_FILES['image']['name'];
	      $file_size =$_FILES['image']['size'];
	      $file_tmp =$_FILES['image']['tmp_name'];
	      $file_type=$_FILES['image']['type'];
	      
	      if($file_size > 2097152)
	      {
	         $errors[]='File size must be excately 2 MB';
	      }
	      
	      if(empty($errors)==true)
	      {
	        move_uploaded_file($file_tmp,"imgs/".$file_name);
	        $addr = 'imgs/'.$file_name;
	  		$query = "UPDATE userinfo SET pic='$addr' WHERE id='$id'";
			$result = mysqli_query($con,$query);
			$rows = mysqli_affected_rows($con);
			
			if($rows == 1)
			{
				echo '<div class="text-center alert bg-success"><span>Success! Profile Pic updated</span></div>';
				echo '<script>setTimeout(function () { window.location.href = "update_pic.php";}, 1000);</script>';
			}
			else
			{
				echo '<div class="text-center alert bg-danger"><span>problem while updating profile pic</span></div>';	
			}
	 		
	    }
    	else
      	{
        	print_r($errors);
      	}
		
		return false;
	}
	}