	<!--     MAIN     -->
	<div class="table_title"><h1>MAIN</h1>
	</div>
	<div class="search_input"><lebel for="search_fio">Поиск студента </lebel><input type="text" id="search_fio" /></div>
<!-- <div class="main_form_wrapper"> -->
	<div class="main_form">
		<form name="save2table" action="javascript:void(null);" method="post" onsubmit="call()" id="form">
			<table  class="main_table default_table">
				<tr>
					<th>id</th>
					<th>fio</th>
					<th>dog_num</th>
					<th>Создать уровень</th>
					<!-- <th>Отметить посещение</th> -->
					<th>Принять проплату</th>
					<th>Удалить</th>
				</tr>
				<?php
					$dataFromDb = $data->fetchAll(PDO::FETCH_ASSOC);
					foreach($dataFromDb as $row) {
						if(is_array($row)){extract($row);
				?>
						<tr class="tr_<?php echo $id; ?>">
							<td><input type="text" name="id" value="<?php echo $id; ?>" readonly></td>
							<td><a href ='http://test.ru/bs_mvc/person?id=<?php echo $id; ?>' target='_self' class="fio_links"><?php echo $fio; ?></a></td>
							<td><input type="text" name="dog_num" size="5"  onchange="call2(this.value,<?php echo $id; ?>,'dog_num')" class="id_<?php echo $id; ?>"  value="<?php echo $dog_num; ?>"></td>
							<td><p class="lgtt" onclick="lgtt(<?php echo $id; ?>);takedown3();">Создать уровень</p></td>
							<td><p class="take" onclick="take(<?php echo $id; ?>);takedown();">Принять проплату</p></td>
							<td><p class="del" onclick="del(<?php echo $id.", '".$fio."'"; ?>)">Удалить</p></td>
						</tr>
						<?php }else{echo 'Нет студентов';} ?>
					<?php } ?>
			</table>
		</form>
	</div>
<!-- </div> -->
	<!--     /MAIN     -->

