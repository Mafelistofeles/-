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
			<li><a href="blog-home.php">Блог</a></li>
			<li class="active">Новая запись</li>
		</ol>
	</div><!--/.row-->

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Новая запись</h1>
		</div>
	</div><!--/.row-->
	
	<div class="row">
		<div class="error">
			<?php new_post(); ?>
		</div>
		<div class="col-lg-12">
			<form class="form-signin" method="post" action="">
			<div class="col-lg-4">
				<label for="postTitle">Заголовок</label>
				<input type="text" name="postTitle" placeholder="Заголовок поста" class="form-control" required autofocus>
				<br>
				<label for="description">Текст</label>
				<textarea name="description" rows="7" cols="60" maxlength="250" placeholder="О посте" id="description" class="form-control space" required></textarea>
				<br>
				<label for="content">Тема</label><br>
				<select class="form-control" name="cats">
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
					<textarea name="content" placeholder="Текст" id="content" class="form-control space" required></textarea>
					<div class="text-center">
					<button class="btn btn-lg btn-primary" name="publish" type="submit" id="publish">Опубликовать</button>
					</div>
			</div>			
			</form>
	</div>
</div><!--/.row-->

<script>
$(document).ready(function() {
          $('#content').summernote({
                height: 450,   
                onImageUpload:function(files, editor, welEditable) {
                  sendFile(files[0], editor, welEditable);
              }

          });
          function sendFile(file, editor, welEditable) {
              data = new FormData();
              data.append("file",file);
              $.ajax({
                  data: data,
                  type: "POST",
                  url: 'summer-upload.php',
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function(url) {
                     $('#content').summernote('editor.insertImage',url);
                  }
              });
          } 
      });
        
    </script>
<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
<?php
	at_bottom();