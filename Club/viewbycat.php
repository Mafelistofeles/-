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
	$getcategory = $_GET['cat'];
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
<li class="active">
<?php
// Преобразование латинских названий категорий в русские
switch ($getcategory) {
    case "Uncategorised":
        echo "Другое";
        break;
    case "Technology":
        echo "Технология";
        break;
    case "Lifestyle":
        echo "Образ жизни";
        break;
    case "News":
        echo "Новости";
        break;
    case "Education":
        echo "Образование";
        break;
    case "Nature":
        echo "Природа";
        break;
    case "Health":
        echo "Здоровье";
        break;
    case "Programming":
        echo "Программирование";
        break;
    default:
        echo $getcategory; // Если категория не известна, оставляем как есть
}
?>
</li>

			</ol>
		</div><!--/.row-->

		<div class="row">
		<div class="col-lg-12">
    <h1 class="page-header">
        <?php
        // Преобразование латинских названий категорий в русские
        switch ($getcategory) {
            case "Uncategorised":
                echo "Категория: Другое";
                break;
            case "Technology":
                echo "Категория: Технология";
                break;
            case "Lifestyle":
                echo "Категория: Образ жизни";
                break;
            case "News":
                echo "Категория: Новости";
                break;
            case "Education":
                echo "Категория: Образование";
                break;
            case "Nature":
                echo "Категория: Природа";
                break;
            case "Health":
                echo "Категория: Здоровье";
                break;
            case "Programming":
                echo "Категория: Программирование";
                break;
            default:
                echo "Категория: " . $getcategory; // Если категория не известна, оставляем как есть
        }
        ?>
    </h1>
</div>

		</div><!--/.row-->
		
		<div class="row">
	<?php
	$query = "SELECT * FROM blog_posts WHERE catinfo='$getcategory' ORDER BY id DESC";
	$result = mysqli_query($con,$query);

	if(mysqli_num_rows($result) > 0) {
		$select = 1;
		while($row = mysqli_fetch_assoc($result)) {
			if($select % 2 == 1) {
				$css = 'panel-primary';
			} else {
				$css = 'panel-info';
			}
	?>
			<div class="col-lg-5">
				<div class="panel <?php echo $css; ?>">
					<div class="panel-heading">
						<?php echo $row['postTitle']; ?>
					</div>
					<div class="panel-body">
						<i>Дата <?php echo date('j', strtotime($row['post_date'])); ?> <?php echo $months[(int)date('n', strtotime($row['post_date']))]; ?> 
						<?php echo date('Y H:i:s', strtotime($row['post_date'])); ?></i> создал <?php echo $row['auther']; ?> категория
						<a href="viewbycat.php?cat=<?php echo $row['catinfo']; ?>">
						<?php
						// Преобразование латинских названий категорий в русские
						switch ($row['catinfo']) {
							case "Uncategorised":
								echo "Другое";
								break;
							case "Technology":
								echo "Технология";
								break;
							case "Lifestyle":
								echo "Образ жизни";
								break;
							case "News":
								echo "Новости";
								break;
							case "Education":
								echo "Образование";
								break;
							case "Nature":
								echo "Природа";
								break;
							case "Health":
								echo "Здоровье";
								break;
							case "Programming":
								echo "Программирование";
								break;
							default:
								echo $row['catinfo']; // Если категория не известна, оставляем как есть
						}
						?>
						</a>
						<br><br>
						<p><?php echo $row['description']; ?></p>
					</div>               
					<div class="panel-footer">
						<?php
						if ($session_name == $row['auther'] || $role == 'President') {
						?>
							<a class="btn btn-warning" href="edit-post.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>">Редактировать</a>
							<a class="btn btn-danger" href="delete-post.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>">Удалить</a> 
						<?php } ?>
						<a class="btn btn-primary" href="viewpost.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>">Читать</a>      
					</div>
				</div>
			</div>
	<?php
			$select++;
		} // Post list while closed.        
	} // Post list if closed.
	else {
		echo '<div class="text-center alert bg-warning col-md-offset-4 col-md-4" role="alert"><span>нет постов</span></div>';
	}
	
	echo '</div>';
	at_bottom();
	?>