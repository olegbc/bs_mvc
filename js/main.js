$(document).ready(function(){
	$('.fio_links').each(function(){
		var before = $(this).attr('href');
		var after = location.origin + before;
	});

	$('.btn_main').click(function(){
        var origin = window.location.origin;
        var pathname = "/bs_mvc/";
        window.location.href = origin + pathname;
    });

    $('.btn_attendance_table').click(function(){
        var origin = window.location.origin;
        var pathname = "/bs_mvc/Attendance";
        window.location.href = origin + pathname;
	});

    $('#search_fio').keyup(function(){
        var searchRequest = $(this).val();
        $('tr').prop('hidden',true);
        $('.fio_links').each(function(){
            var existedStudent = $(this).text();
            var re=new RegExp (searchRequest,"i");
            var match = existedStudent.match(re);
            if(match){
                var parents = $(this).parents('tr').attr('class');
                parents = "."+parents;
                $('tr:first').prop('hidden',false);
                $(parents).prop('hidden',false);
            }
        });
    });

    if(location.pathname == "/bigstep/level_culculation.php"){
        $("#level_start_date" ).datepicker({
            showOn: "button",
            buttonImage: "images/calendar.gif",
            buttonImageOnly: true,
            buttonText: "Select date",
            dateFormat: "yy-mm-dd",
            firstDay: 1,
        });
    }

    $('.btn_level_culculation').click(function(){
		var path = "/bigstep/level_culculation.php";
		var direct = location.origin + path;
		location.href = direct;
	});
	$('.btn_number_of_students').click(function(){
		var path = "/bigstep/Number_of_students.php";
		var direct = location.origin + path;
		location.href = direct;
	});
	$('.btn_amount_of_money').click(function(){
		var path = "/bigstep/amount_of_money.php";
		var direct = location.origin + path;
		location.href = direct;
	});
	$('.btn_edit_levels').click(function(){
		var path = "/bigstep/level_culculation.php";
		var direct = location.origin + path;
		location.href = direct;
	});
	$('.btn_bad_days').click(function(){
		var path = "/bigstep/bad_days.php";
		var direct = location.origin + path;
		location.href = direct;
	});
	$('.btn_freeze_table').click(function(){
		var id = getParameterByName('id');
		var path = "/bs_mvc/Freeze?id="+id;
		var direct = location.origin + path;
		location.href = direct;

	});

	// teacher_calculate();
	// timetable_calculate();
	$('#guess_check').click(function(){
		guess_check();
	});

	$('.back_gray').click(function(){$(this).hide();$('.add_form').hide();});
	$('.cancel_btn').click(function(){$('.back_gray').hide();$('.add_form').hide();});
	$('.close_cross').click(function(){
		$('.back_gray').hide();
		$('.add_form').hide();
		$('.take_form').hide();
		$('.visit_form').hide();
		$('.level_person_form').hide();
		});
	$('.back_gray').click(function(){
		$('.back_gray').hide();
		$('.add_form').hide();
		$('.take_form').hide();
		$('.visit_form').hide();
		$('.level_person_form').hide();
		});

	$('.btn_add').click(
		function (){
			$('.add_form').show();
			$('.back_gray').show();
			$("#add_form")[0].reset();
		}
	);
});
/*------------------- /ready -----------------*/

function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	results = regex.exec(location.search);
	return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function add_fn() {
	var name = $('#fio_add').val();
	$.ajax({
		type: 'POST',
		url: './Main/AddStudent',
        dataType: 'json',
		data: {name:name},
		success: function(data) {
			console.log(data);
			if(data['studentExisted']){
				alert("Студент с таким именем и фамилией уже зарегестрирован");
			}else{
				$('.add_form').hide();
				//$(".main_table").children('tbody').children('tr:last-child ').after(data);
                var id = data['id'];
                var name = data['fio'];
                var nameQuoted = '"'+name+'"';
                var dog_num = data['dog_num'];
                //var date = data['date'];
				$(".main_table").children('tbody').children('tr:last-child ').after("<tr class='tr_"+id+"'><td><input type='text' name='id' value='"+id+"' /></td><td><input type='text' name='fio' onchange='call2(this.value,"+id+",'fio')'><a href='http://test.ru/bs_mvc/person?id="+id+"' target='_self'>"+name+"</a></td><td><input type='text' name='dog_num' size='5'  onchange='call2(this.value,"+id+",'dog_num')'  value= "+dog_num+"></td><td><p class='fillInNameAndIdInForm' onclick='fillInNameAndIdInForm("+id+");showDivWrapperOfFormShowGrayBackgroundResetForm();'>Создать уровень</p></td><td><p class='take' onclick='take("+id+");takedown();'>Принять проплату</p></td><td><p class='del' onclick='deleteStudent("+id+","+nameQuoted+")'>Удалить</p></td></tr>");
				alert("Студент зарегестрирован");
				$('.back_gray').hide();
                //<tr class='tr_'+$data["id"]>
                    //<td><input type='text' name='id' value='$row[0]' /></td>
                    //<td><input type='text' name='fio' onchange='call2(this.value,$row[0],'fio')'>
                    //    <a href="http://test.ru/bigstep/person.php?person=$row[0]" target="_self">$row[1]</a>
                    //</td>
                    //    <td><input type='text' name='dog_num' size='5'  onchange='call2(this.value,$row[0],'dog_num')' value=$row[2]></td>
                    //        <td><p class='fillInNameAndIdInForm' onclick='fillInNameAndIdInForm($row[0]);showDivWrapperOfFormShowGrayBackgroundResetForm();'>Создать уровень</p></td>
                    //        <td><p class='take' onclick='take($row[0]);takedown();'>Принять проплату</p></td>
                    //        <td><p class='del' onclick='del($row[0])'>Удалить</p></td>
                    //    </tr>
			}
		},
		error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});
}	

function payment_add() {
	var msg   = $('#payment_form').serialize();
		$.ajax({
			type: 'POST',
			url: './oldphpfiles/saveorder.php',
			data: msg,
			success: function(data) {
				console.log(data);
			/*
				$(".main_table").children('tbody').children('tr:last-child ').after(data);
				alert("Запись добавлена");
			*/
				$('.add_form').hide();
				$('.back_gray').hide();
				
		},
		error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});
}

function call2(info,id,info_type){
	var info = info;
	var id = id;
	var info_type = info_type;
		$.ajax({
			type: 'POST',
			url: './oldphpfiles/edit.php',
			data: 'info='+info+'&id='+id+'&info_type='+info_type,
			success: function(data) {
			//	alert( data );
				$('.id_'+id+':input[name="'+info_type+'"]').val(data);
			},
			error:  function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});		
}

function lpupdate(info,id,info_type){
	var info = info;
	var id = id;
	var info_type = info_type;
		$.ajax({
			type: 'POST',
			url: './oldphpfiles/lgttedit.php',
			data: 'info='+info+'&id='+id+'&info_type='+info_type,
			success: function(data) {
			//	alert( data );
				$('.id_'+id+':input[name="'+info_type+'"]').val(data);
			},
			error:  function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});		
}

function deleteStudent(id,fio){
	if(confirm('Вы действительно хотите удалить '+fio+' из базы учеников?')){
		var id = id;
		$.ajax({
			type: 'POST',
			url: './Main/DeleteStudent',
			data: {id:id},
			success: function(data) {
				$( ".tr_"+id ).remove();
				alert( "Запись удалена" );
			},
			error:  function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}
}

function remove_pers_soch(id,fio,tr){
	// console.log(id,fio,tr);
	var r = $(".brick[style*='blue']").children('[name="teacher_choose"]').val();
	var t = $(".brick[style*='blue']").children('[name="timetable_choose"]').val();
	var u = $(".brick[style*='blue']").children('[name="level_start_choose"]').val();
	// return false;
	if(confirm('Вы действительно хотите удалить '+fio+' из данного сочетания?')){
		var id = id;
		$.ajax({
			type: 'POST',
			url: './oldphpfiles/remove_pers_soch.php',
			data: {id:id,teacher:r,timetable:t,level_start:u},
			success: function(data) {
				$("#tr"+tr).remove();
			},
			error:  function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}
}

//function take(id){
//	var id = id;
//	$.ajax({
//		type: 'POST',
//		url: './oldphpfiles/take.php',
//		data: 'id='+id,
//		dataType: 'json',
//		success: function(data) {
//		//	alert( data );
//			// var sp = data.split('|');
//			$('#fio_take').val(data[0]);
//			for(i=1;i<data.length;i++){
//				// $('#teacher_take').append("<option value="+data[i][0]+"|"+data[i][1]+"|"+data[i][2]+"|">data[i][0]+"|"+data[i][1]+"|"+data[i][2]+"|</option>");
//				$('#combination_take').append("<option value="+data[i][0]+"|"+data[i][1]+"|"+data[i][2]+">"+data[i][0]+", "+data[i][1]+", "+data[i][2]+"</option>");
//				// $('#timetable_take').append("<option value="+data[i][1]+">"+data[i][1]+"</option>");
//				// $('#level_start_take').append("<option value="+data[i][2]+">"+data[i][2]+"</option>");
//			}
//			// $('#timetable_take').val(sp[2]);
//			// $('#level_start_take').val(sp[3]);
//			$('#id_take').val(id);
//		//	alert( "Запись удалена" );
//		},
//		error:  function(xhr, str){
//			alert('Возникла ошибка: ' + xhr.responseCode);
//		}
//	});
//}

function fillInNameAndIdInForm(id,name){
	if($('.timetable_soch')){$('.timetable_soch').remove();}
	if($('.level_start_soch')){$('.level_start_soch').remove();}
	if($('.level_soch')){$('.level_soch').remove();}
	if($('.person_start_soch')){$('.person_start_soch').remove();}
	if($('.person_stop_soch')){$('.person_stop_soch').remove();}
    $('#fio_person').val(name);
    $('#id_person').val(id);
	//$.ajax({
	//	type: 'POST',
	//	url: './Main/fillInNameAndIdInForm.php',
	//	data: {id:id},
	//	success: function(data) {
	//		$('#fio_person').val(data);
	//		$('#id_person').val(id);
	//	},
	//	error:  function(xhr, str){
	//		alert('Возникла ошибка: ' + xhr.responseCode);
	//	}
	//});
}

function ShowTakeForm(id,name){
	$('.take_form').show();
	$('.back_gray').show();
	if($("#take_to_bd_form")){$("#take_to_bd_form")[0].reset();}
    $('#id_take').val(id);
    $('#fio_take').val(name);
}

function takedown2(){
	$('.visit_form').show();
	$('.back_gray').show();
	$("#visit_form")[0].reset();
}

function showDivWrapperOfFormShowGrayBackgroundResetForm() {
    $('.level_person_form').show();
    $('.back_gray').show();
    $("#level_person_form")[0].reset();
}

function saveAmountOfMoney(){
	var amount   = $('#take_person_money').val();
    var name = $('#fio_take').val();
    var id = $('#id_take').val();
		$.ajax({
			type: 'POST',
			url: './Main/SaveAmountOfMoney.php',
			data: {amount:amount,name:name,id:id},
			success: function(data) {
				console.log(data);
				$('.take_form').hide();
				$("#take_to_bd_form")[0].reset();
                alert( "принято:"+amount+" грн \nот: "+name+" \n");
                $('.back_gray').hide();
		},
		error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});
	
}

function dateofvisit(id){		
	var id = id;
	$.ajax({
		type: 'POST',
		url: './oldphpfiles/dateofvisit.php',
		data: 'id='+id,
		dataType: 'json',
		success: function(data) {
			//	console.log( data );
			$('#fio_visit').val(data[0]);
			$('#person_start').val(data[1]);
			$('#id_visit').val(id);
		},
		error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});			
}

function dateofvisittobd(){	
	var msg   = $('#visit_form').serialize();
		$.ajax({
			type: 'POST',
			url: './oldphpfiles/date_of_visit_to_bd.php',
			data: msg,
			success: function(data) {
					console.log( data );
			
				$('.visit_form').hide();
				$('.back_gray').hide();
				$("#visit_form")[0].reset();
				
							
				var sp = data.split('|');
				if(sp[5]==3){alert("Вы ввели дату ранее чем дата начала курса для студента "+sp[1]);}else{
					if(data!="" && sp[5]!=0){
						$(".visit_table").children('tbody').children('tr:last-child ').after("<td><input type='text' name='id' value='"+sp[3]+"'></td><td><input type='text' name='fio_id' size='45' onblur='call2(this.value,<?php echo $row[0]; ?>,'fio_id')' class='id_<?php echo $row[0]; ?>'  value='"+sp[2]+"'></td><td><input type='text' name='given' size='7' onchange='call2(this.value,<?php echo $row[0]; ?>,'given')' class='id_<?php echo $row[0]; ?>' value='"+sp[0]+"'></td>");
						$('.back_gray').show();alert( sp[1]+" посетил занятие: "+sp[0]);$('.back_gray').hide();}
					if(sp[5]==0){alert("данное посещение уже отмечено");}				
				}				
				
		},
		error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});
	
}

function lgtt_match_fn2(){	
	//	alert("fghj");
	var obj = {
		a: 12,
		b: 'olololo',
		name: 'Victor'
	};
	var k = obj.a;
	//	alert(obj.name);
	var obj = {
		a: 12,
		b: 'olololo',
		name: 'Victor',
		z: {
			satan: 666
		}
	};
	alert(obj.z.satan);
}

function dump(obj) {
	var out = "";
	if(obj && typeof(obj) == "object"){
		for (var i in obj) {
			out += obj[i] + "\n";
		}
	} else {
		out = obj;
	}
	alert(out);
}	

function dump_vloj(obj) {
	var out = "";
	if(obj && typeof(obj) == "object"){
		for (var i in obj) {
			for (var j in obj[i]) {
		
				out += i + ":" + obj[i][j] + "\n";
			//	out += obj[i][j] + "\n";
			
			}
		}
	} else {
		out = obj;
	}
	alert(out);
}
function submit_enable(){
//	$('#lgtt_form input:submit').attr("disabled", false);
}

var first_time = 0;

//ПОСТРОЕНИЕ ТАБЛИЦЫ
function building_blocks(teacher_now,timetable_now,level_start_now){
		if(teacher_now && timetable_now && level_start_now){
			// console.log(teacher_now,timetable_now,level_start_now);
		}
		$.ajax({
			type: 'POST',
			async: false,
			//url: './oldphpfiles/building_blocks.php',
			url: './Attendance/BuildingBlocks.php',
			dataType: 'json',
			success: function(data) {
				for(var i in data){
					// console.log(data[i][4]);
					
					if(data[i][4]== null){
						
						var teacher_remove = "'"+data[i][0]+"'";
						var timetable_remove = "'"+data[i][1]+"'";
						var level_start_remove = "'"+data[i][2]+"'";
						$('.peresent_combinations').append('<div class="brick"><div class="remove_combination remove_combination_'+i+'"><button class="btn_remove_combination" onclick="remove_combination('+teacher_remove+','+timetable_remove+','+level_start_remove+')" disabled>x</button></div><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_'+i+'" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_'+i+'" disabled /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_'+i+'" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_'+i+'" disabled /></div>')
						$('#teacher_choose_'+i).val(data[i][0]);
						$('#timetable_choose_'+i).val(data[i][1]);
						$('#level_start_choose_'+i).val(data[i][2]);
						$('#level_choose_'+i).val(data[i][3]);
						// console.log(is);
					}
					if(data[i][4]== null){
						var is = [];
						$.ajax({
							type:'POST',
							async: false,
							url: './oldphpfiles/is_today_within_combination.php',
							dataType: 'json',
							data: {teacher:data[i][0],timetable:data[i][1],level_start:data[i][2]},
							success: function(data){
								is[i] = data;
							},
							error:  function(xhr, str){
								alert('Возникла ошибка: ' + xhr.responseCode);
							}
						});
						// console.log(i);
					}
					if(data[i][4]== 0 ){
						var teacher_remove = "'"+data[i][0]+"'";
						var timetable_remove = "'"+data[i][1]+"'";
						var level_start_remove = "'"+data[i][2]+"'";
						$('.peresent_combinations').append('<div class="brick"><div class="remove_combination remove_combination_'+i+'"><button class="btn_remove_combination" onclick="remove_combination('+teacher_remove+','+timetable_remove+','+level_start_remove+')" disabled>x</button></div><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_'+i+'" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_'+i+'" disabled /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_'+i+'" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_'+i+'" disabled /></div>')
						$('#teacher_choose_'+i).val(data[i][0]);
						$('#timetable_choose_'+i).val(data[i][1]);
						$('#level_start_choose_'+i).val(data[i][2]);
						$('#level_choose_'+i).val(data[i][3]);
					}
					// console.log(data[i][4]=-1));
					if(data[i][4]==-1 ){ 
						var teacher_remove = "'"+data[i][0]+"'";
						var timetable_remove = "'"+data[i][1]+"'";
						var level_start_remove = "'"+data[i][2]+"'";
						$('.past_combinations').append('<div class="brick"><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_'+i+'" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_'+i+'" disabled /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_'+i+'" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_'+i+'" disabled /></div>')
						$('#teacher_choose_'+i).val(data[i][0]);
						$('#timetable_choose_'+i).val(data[i][1]);
						$('#level_start_choose_'+i).val(data[i][2]);
						$('#level_choose_'+i).val(data[i][3]);
					}
					if(data[i][4]==1 ){ 
						var teacher_remove = "'"+data[i][0]+"'";
						var timetable_remove = "'"+data[i][1]+"'";
						var level_start_remove = "'"+data[i][2]+"'";
						$('.future_combinations').append('<div class="brick"><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_'+i+'" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_'+i+'" disabled /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_'+i+'" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_'+i+'" disabled /></div>')
						$('#teacher_choose_'+i).val(data[i][0]);
						$('#timetable_choose_'+i).val(data[i][1]);
						$('#level_start_choose_'+i).val(data[i][2]);
						$('#level_choose_'+i).val(data[i][3]);
					}
				}
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
		$('.combination_all .past_combinations .brick').click(function(){
			$('.brick').css('borderColor','#000');
			var teacher_choose = $(this).children("input[name='teacher_choose']").val();
			var timetable_choose = $(this).children("input[name='timetable_choose']").val();
			var level_start_choose = $(this).children("input[name='level_start_choose']").val();
			$('#lgtt_form').children("input[name='teacher_choose']").val(teacher_choose);
			$('#lgtt_form').children("input[name='timetable_choose']").val(timetable_choose);
			$('#lgtt_form').children("input[name='level_start_choose']").val(level_start_choose);
			lgtt_match_fn(teacher_choose,timetable_choose,level_start_choose);
			$(this).css('borderColor','blue');
			$('.change_start_date').prop('disabled',true);
			$('.btn_send_to_archive').prop('disabled',true);
		});
		$('.combination_all .peresent_combinations .brick').click(function(){
			$('.brick').css('borderColor','#000');
			var teacher_choose = $(this).children("input[name='teacher_choose']").val();
			var timetable_choose = $(this).children("input[name='timetable_choose']").val();
			var level_start_choose = $(this).children("input[name='level_start_choose']").val();
			$('#lgtt_form').children("input[name='teacher_choose']").val(teacher_choose);
			$('#lgtt_form').children("input[name='timetable_choose']").val(timetable_choose);
			$('#lgtt_form').children("input[name='level_start_choose']").val(level_start_choose);
			lgtt_match_fn(teacher_choose,timetable_choose,level_start_choose);
			$(this).css('borderColor','blue');
		});
		$('.combination_all .future_combinations .brick').click(function(){
			$('.brick').css('borderColor','#000');
			var teacher_choose = $(this).children("input[name='teacher_choose']").val();
			var timetable_choose = $(this).children("input[name='timetable_choose']").val();
			var level_start_choose = $(this).children("input[name='level_start_choose']").val();
			$('#lgtt_form').children("input[name='teacher_choose']").val(teacher_choose);
			$('#lgtt_form').children("input[name='timetable_choose']").val(timetable_choose);
			$('#lgtt_form').children("input[name='level_start_choose']").val(level_start_choose);
			lgtt_match_fn(teacher_choose,timetable_choose,level_start_choose);
			$(this).css('borderColor','blue');
			$('.btn_send_to_archive').prop('disabled',true);
		});
		$('.brick').each(function(){
			if($(this).children("input[name='teacher_choose']").val()==teacher_now && $(this).children("input[name='timetable_choose']").val()==timetable_now && $(this).children("input[name='level_start_choose']").val()==level_start_now){
				$(this).css('borderColor','rgb(0, 0, 255)');
			}
		});
}

function lgtt_match_fn(teacher,timetable,level_start){
		// построение блоков сочетаний
		var msg;
		if(teacher!=undefined && timetable!=undefined && level_start!=undefined){
			msg = {teacher:teacher,timetable:timetable,level_start:level_start};
		}else{
			msg = $('#lgtt_form').serialize();
		}
		var level_date = [] ;
		var person_date = [] ;
		var personus_fio = [] ;			
			
			//----- Построение шапки таблицы ;
		$('#attendance_table').empty();
		$('#attendance_table').html('<tbody><tr id="th_line"><th class="attendance_name_th" id="name_th"><div id="attendance_table_name">Имя</div></th></tr></tbody>');
		
			$.ajax({
				type: 'POST',
				async: false,
				url: './Attendance/CombinationDatesFittedToTimetable.php',
				dataType: 'json',
				data: msg,
				success: function(data) {
                    for(var g=21;g>0;g--){
                        $('#name_th').after("<th class='attendance_th'><div class='rotateText'>"+data['sd_'+g]+"</div></th>");
                        level_date[g-1]=data['sd_'+g];
                    }
				},
				error: function(xhr, str){
					alert('Возникла ошибка: ' + xhr.responseCode);
				}
			});
			
			//----- /Построение шапки таблицы

			//return;

			//----- Фамилии даты
			$.ajax({
				type: 'POST',
				async: false,
				url: './Attendance/StudentsInformation.php',
				dataType: 'json',
				data: msg,
				success: function(data) {
                    var payed_all=0;
                    console.log(data);
                    for (var i=(data['name'].length-1); i>=0; i--) {
                        $('#th_line').after("<tr id='tr"+i+"'></tr>");
                        if(data['numPayed'][i] == data['numReserved'][i]){
                            $('#tr'+i).html('<td id="td'+i+'_'+0+'"><div class="remove_pers_soch"><button  onclick="remove_pers_soch('+data['id'][i]+','+data['name'][i]+','+i+')">x</button></div><div class="pay_check pay_check_green">'+data['numPayed'][i]+'/'+data['numReserved'][i]+'</div><div class="fio_pers_soch">'+data['name'][i]+'</div></td>');
                            payed_all=1;
                        }else{
                            $('#tr'+i).html('<td id="td'+i+'_'+0+'"><div class="remove_pers_soch"><button  onclick="remove_pers_soch('+data['id'][i]+','+data['name'][i]+','+i+')">x</button></div><div class="pay_check">'+data['numPayed'][i]+'/'+data['numReserved'][i]+'</div><div class="fio_pers_soch">'+data['name'][i]+'</div></td>');
                        }
                        if(data['status'][i]==-1){$('.remove_pers_soch button').remove();}
                        for(var q =0;q<data['dates'].length;q++ ) {
                            //if(data == null){$('.brick').children("input[value='"+teacher+"']").siblings("input[value='"+timetable+"']").siblings("input[value='"+level_start+"']").parent('.brick').children('.remove_combination').children('.btn_remove_combination').prop('disabled',false);}
                            //return;
                            var color = 0;
                            var color_freeze = 0;
                            var start_date = 0;
                            var stop_date = 0;
                            var before_person_start = 1;
                            var after_person_stop = 0;
                                if (data['attenedDates'][i] != 0) {
                                    for (var c in data['attenedDates'][i]) {
                                        if (data['dates'][q] == data['attenedDates'][i][c]) {
                                            match = data['attenedDates'][i][c];
                                            color = 1;
                                        }
                                        else {
                                            match = data['dates'][q];
                                        }
                                    }
                                }else{
                                    match = data['dates'][q];
                                }
                                if (data['dates'][q] < data['personStart'][i]) {
                                    before_person_start = 1;
                                } else if (data['dates'][q] >= data['personStart'][i]) {
                                    before_person_start = 0;
                                }
                                if (data['dates'][q] > data['personStop'][i]) {
                                    after_person_stop = 1;
                                } else if (data['dates'][q] <= data['personStop'][i]) {
                                    after_person_stop = 0;
                                }
                                for(var g in data['frozenDates'][i]){
                                    if(data['dates'][q]  == data['frozenDates'][i][g]){
                                        color_freeze = 1;
                                    }
                                }
                                if(data['dates'][q]  == data['personStart'][i]){
                                    start_date = 1;
                                }
                                if(data['dates'][q] == data['personStop'][i]){
                                    stop_date = 1;
                                }
                                if(color == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){
                                    $('#td'+i+'_'+q).after("<td id=td"+i+"_"+(parseInt(q)+1)+" class='payment_mark attendence_mark before_person_start_mark color'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
                                    $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark color'>"+match+"</td>");
                                }else if(color == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
                                    $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark before_person_start_mark color'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
                                    $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark color'>"+match+"</td>");
                                }else if(color == 1 && start_date == 1){
                                    $('#td'+i+'_'+q).after("<td  bordercolor='#0000FF' id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark person_start_mark color'>"+match+"</td>");
                                }
                                // color_freeze
                                else if(color_freeze == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){
                                    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark before_person_start_mark color_freeze'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
                                    $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark color_freeze'>"+match+"</td>");
                                }else if(color_freeze == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
                                    $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark before_person_start_mark color_freeze'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
                                    $('#td'+i+'_'+q).after("<td  id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark color_freeze'>"+match+"</td>");
                                }else if(color_freeze == 1 && start_date == 1){
                                    $('#td'+i+'_'+q).after("<td  bordercolor='#0000FF' id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark person_start_mark color_freeze'>"+match+"</td>");
                                }
                                // /color_freeze

                                else if(color == 0 && start_date == 1){
                                    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark person_start_mark'>"+match+"</td>");
                                }else if(color == 1 && stop_date == 1){
                                    // console.log(color, stop_date);
                                    $('#td'+i+'_'+q).after("<td  bordercolor='#0000FF' id='td"+i+"_"+(q+1)+"' class='payment_mark attendence_mark person_stop_mark color'>"+match+"</td>");
                                }
                                // color_freeze
                                else if(color_freeze == 1 && stop_date == 1){
                                    // console.log(color, stop_date);
                                    $('#td'+i+'_'+q).after("<td  bordercolor='#0000FF' id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark person_stop_mark color_freeze'>"+match+"</td>");
                                }
                                // /color_freeze
                                else if(color == 0 && stop_date == 1){
                                    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark person_stop_mark'>"+match+"</td>");
                                }else if(color == 0 && before_person_start == 1){
                                    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark before_person_start_mark'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 0 && after_person_stop == 0 && before_person_start == 0){
                                    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark'>"+match+"</td>");
                                }else if(color == 0 && after_person_stop == 1){
                                    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark before_person_start_mark'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 0 && after_person_stop == 0){
                                    $('#td'+i+'_'+q).after("<td id='td"+i+"_"+(parseInt(q)+1)+"' class='payment_mark attendence_mark'>"+match+"</td>");
                                }
                            }
                    }

					//		Проверка оплачен ли урок (полностью или частично)
					//function pay(){
					//	var t = 0;
					//	for (var i in data) {
					//		var start_point = $('td[id^=td'+t+'_].person_start_mark').attr('id');
					//		// console.log("start_point 1 : "+start_point);
					//		start_point = parseInt(start_point.replace('td'+t+'_', ''));
					//		// console.log("start_point 2 : "+start_point);
					//		// alert(i);
					//		var flag = 0;
					//		for(var y = 0; y <= data[i]['lessons_num'][3]; y++){
					//			// alert(y);
					//				if(y === 0 && y === data[i]['lessons_num'][3] && data[i]['lessons_num'][4] > 0){
					//					console.log('недоплата : '+data[i]['lessons_num'][4])
					//					$('td[id=td'+t+'_'+start_point+']').addClass('partly_payed_lesson');
                    //
					//				}else if(y !== 0 && y === data[i]['lessons_num'][3] && data[i]['lessons_num'][4] > 0){
					//					// console.log('недоплата : '+data[i]['lessons_num'][4])
					//					start_point = start_point + 1;
					//					$('td[id=td'+t+'_'+start_point+']').addClass('partly_payed_lesson');
                    //
					//				}else if(data[i]['lessons_num'][4] === 0){}
					//				else{
					//				if(start_point !== undefined && flag !== 1){
					//					// console.log(data[i]['lessons_num'][3]);
					//					flag = 1;
					//					var num_num = data[i]['lessons_num'][3];
					//					// console.log(num_num);+
					//							// $('td[id=td'+y+'_'+start_point+'].person_start_mark').css('color','rgb(255, 20, 255)');
					//							$('td[id=td'+t+'_'+start_point+'].person_start_mark').addClass('payed_lesson');
					//				}else{
					//					start_point = start_point + 1;
					//				// console.log("start_point 3 : "+start_point);
					//					// $('td[id=td'+y+'_'+start_point+']').css('color','rgb(255, 20, 255)');
					//					// $('td[id=td0_6]').delay(8000).css('color','rgb(255, 20, 255)');
					//					$('td[id=td'+t+'_'+start_point+']').addClass('payed_lesson');
					//					// alert($('td[id=td0_6]'));
					//				}
					//			}
					//		}
					//		t++;
					//	}
					//}

					//---- действия при клике на ячейку даты, находиться здесь так как таблицеа формируется после формирования страницы
					//$('.attendence_mark').not('.before_person_start_mark').not('.color_freeze').click(function(){
					//	// console.log("lllll");
					//	if($(this).hasClass('color')){
					//		if(confirm('Вы действительно хотите удалить '+$(this).text()+' дату посещения')){
					//			var this_thing = $(this).attr('id');
					//			var fio_num1 = this_thing.split("_");
					//			var fio_num = fio_num1[0].replace("td", "");
					//			var date = $(this).text();
                    //
					//			// console.log(fio_num,arr_id);
					//			$.ajax({
					//				type:'POST',
					//				url: './oldphpfiles/date_of_visit_to_bd.php',
					//				data: {person_id:arr_id[fio_num],person_date:date,teacher:r,timetable:t,level_start:u},
					//				success: function(data){
					//					// console.log(data);
					//					// return false;
					//					// var r = $('[style="border-color: blue;"]').children('[name=teacher_choose]').val();
					//					// var t = $('[style="border-color: blue;"]').children('[name=timetable_choose]').val();
					//					// var u = $('[style="border-color: blue;"]').children('[name=level_start_choose]').val();
					//					lgtt_match_fn(r,t,u);
					//				},
					//				error:  function(xhr, str){
					//					alert('Возникла ошибка: ' + xhr.responseCode);
					//				}
					//			});
					//		}
					//	}else{
					//		var this_thing = $(this).attr('id');
					//		var fio_num1 = this_thing.split("_");
					//		var fio_num = fio_num1[0].replace("td", "");
					//		var date = $(this).text();
                    //
					//		// console.log(fio_num,arr_id);
					//		$.ajax({
					//			type:'POST',
					//			url: './oldphpfiles/date_of_visit_to_bd.php',
					//			data: {person_id:arr_id[fio_num],person_date:date,teacher:r,timetable:t,level_start:u},
					//			success: function(data){
					//				// console.log(data);
					//				// return false;
					//				// var r = $('[style="border-color: blue;"]').children('[name=teacher_choose]').val();
					//				// var t = $('[style="border-color: blue;"]').children('[name=timetable_choose]').val();
					//				// var u = $('[style="border-color: blue;"]').children('[name=level_start_choose]').val();
					//				lgtt_match_fn(r,t,u);
					//			},
					//			error:  function(xhr, str){
					//				alert('Возникла ошибка: ' + xhr.responseCode);
					//			}
					//		});
					//	}
					//});

					//---- /действия при клике на ячейку даты, находиться здесь так как таблицеа формируется после формирования страницы
					//	*/
					// var is_today_within_combination = 0;
					//var is = 0;
					//$.ajax({
					//	type:'POST',
					//	async: false,
					//	url: './oldphpfiles/is_today_within_combination.php',
					//	dataType: 'json',
					//	data: {teacher:r,timetable:t,level_start:u},
					//	success: function(data){
					//		is = data;
					//	},
					//	error:  function(xhr, str){
					//		alert('Возникла ошибка: ' + xhr.responseCode);
					//	}
					//});

					//var teacherQuoted = "'"+teacher+"'";
					//var timetableQuoted = "'"+timetable+"'";
					//var level_startQuoted = "'"+level_start+"'";
					//$('.btn_arrangment').remove();
					//$('.btn_send_to_archive').remove();

					//$('.att_table').before('<div class="btn_arrangment"><input type="text" name="new_level_start" id="new_level_start"/><button class="change_start_date" onclick="change_start_date('+teacherQuoted+','+timetableQuoted+','+level_startQuoted+')">change_start_date</button></div><div class="send_to_archive_div"><button class="btn_send_to_archive" onclick="send_to_archive('+teacherQuoted+','+timetableQuoted+','+level_startQuoted+')" disabled>Отправить в архив</button></div>');

					//if(location.pathname == "/bigstep/attendance_table_blocks.php"){
					//	// console.log($("#new_level_start" ));
					//	$("#new_level_start" ).datepicker({
					//		showOn: "button",
					//		buttonImage: "images/calendar.gif",
					//		buttonImageOnly: true,
					//		buttonText: "Select date",
					//		dateFormat: "yy-mm-dd",
					//		firstDay: 1,
					//	});
					//}

					//if($('*').hasClass('color')){$('.change_start_date').prop('disabled',true);}
					//if(payed_all==1 && $('*').hasClass('fio_pers_soch') && is ==-1){$('.btn_send_to_archive').prop('disabled',false);}
					// $('.past_combinations .btn_send_to_archive').prop('disabled',true);}

				},
				error:  function(xhr, str){
					alert('Возникла ошибка ajax: ' + xhr.responseCode);
				}

			});




			
			/*----- /Фамилии даты -------*/
			
	//	}
}

function level_culc_fn(){
	var msg = $('#level_culc').serialize();
	if($('#level_culc input#teacher_choose')[0]['value'] != "" && 
		$('#level_culc input#timetable_choose')[0]['value'] != "" && 
		$('#level_culc input#level_start_date')[0]['value'] != "" && 
		$('#level_culc input#level_choose')[0]['value'] != ""){
		var teacher = $('#level_culc input#teacher_choose')[0]['value'];
		var timetable = $('#level_culc input#level_start_date')[0]['value'];
		var level_start = $('#level_culc input#level_choose')[0]['value'];
		$.ajax({
			type:'POST',
			url: './oldphpfiles/calculate_level_dates.php',
			data: msg,
			success: function(data){
				console.log(data);
				if(data=="bad"){
					alert("Дата старта уровня не соответствует расписанию");
				}else{
					var path = "/bigstep/bad_days.php?teacher="+teacher+"&timetable="+timetable+"&level_start="+level_start;
					var direct = location.origin + path;
					location.href = direct;
				}
			},
			error:  function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}else{alert('Заполните все обязательные поля')}
}


function Num_of_students_fn(){
	var msg = $('#Num_of_students').serialize();
	$.ajax({
		type:'POST',
		url: './oldphpfiles/Number_of_students_calculation.php',
		dataType: 'json',
		data: msg,
		success: function(data){
			//	console.log(data);
				for(i in data)
				{
						w = parseInt(i) + 1;
						$('.inscription_'+w).html(data[i][0]+' <br /> '+data[i][1]);
						$('.week_'+w).html(data[i][2]);
				}
			//	$('.inscription_1')
		},
		error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});
}
/*
function calendar(){
	$.ajax({
		type:
	});
}
*/
function datepicker_fn(){
	var msg = $('#widgetField span').get(0).innerHTML;
	var new_msg = msg.split(" ÷ ");
	var teacher = $('#teacher').val();
	var all_teachers= [];
	var color_arr = [];

	$.ajax({
		type:'POST',
		url: './oldphpfiles/get_all_teachers.php',
		dataType: 'json',
		success: function(data){
			// console.log(data);
			all_teachers = data;
			for(var i in data){
				color_arr[i]="#" + Math.random().toString(16).slice(2, 8)
				// (Math.random().toString(16) + '000000').slice(2, 8) // This only uses random() once, requires no difficult maths, converts directly into hex, and accounts for the issue of lost trailing zeros.
				// The issues with Paul's method are:
				// - it does not account for leading zeros (yielding invalid results)
				// - the Math.floor() and the multiplication add unnecessary complexity

			}
		},
		error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});
	
	$.ajax({
		type:'POST',
		url: './oldphpfiles/datepicker.php',
		dataType: 'json',
		data: {from:new_msg[0],to:new_msg[1],teacher:teacher},
		success: function(data){
			// console.log(data);
			diag(data,all_teachers,color_arr);
		},
		error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});
}
//---   DIAG
var f = true;
function diag(e,all_teachers,color_arr){
	arrayOfDataMulti = e;
	
	var first = f; // whether it first time legend_all clicked
	
	build(false,first,all_teachers,color_arr); // first time, we do need inscriptions and legend
	build2(all_teachers,color_arr);
	init(); // initialization .clickable and legend_all
	
	function init(){	
		$('.clickable').click(function(){
			var r = $(this).attr('id'); 
			var t;
			t = $(this).attr('id').substr(-1);
			
			var legend_arr  = new Array();
			
			for(i in arrayOfDataMulti){
				legend_arr[i]  = new Array();
				legend_arr[i][0]  = new Array();
				legend_arr[i][0][0]  = new Array();
				
				legend_arr[i][0][0] = arrayOfDataMulti[i][0][t];
				legend_arr[i][1] = arrayOfDataMulti[i][1];
			} 
			$("#stackedGraph_multi").empty();
			$("#stackedGraph_multi").attr('style','');
			$("#stackedGraph_multi").prev('h3').remove();
			
			build_single(t,legend_arr,all_teachers,color_arr);
			init();
		});
	
		$('#legend_all').click(function(){
			$("#graphHolder").remove();
			$('<div id="stackedGraph_multi"></div>').prependTo($("#stackedGraph_wrapper"));
			
			build(true,first,all_teachers,color_arr); // build(all,times)	
			first = false;
			init();	
			
		});
	}
	f = false;
}		
function build(e,p,all_teachers,color_arr){
	$("#graphHolder").remove();
	$("#stackedGraph_multi").remove();
	$('<div id="stackedGraph_multi"></div>').prependTo($("#stackedGraph_wrapper"));
	var t = e;
	var y = p;
	$("#stackedGraph_multi").jqBarGraph({
		all: true,
		first: false,
		single: false,
		data: arrayOfDataMulti,
		colors: color_arr,
		legends: all_teachers,
		legend: true,
		width: 1500,
		type: 'multi',
		animate: true,
	//	postfix: ' учеников',
		title: '<h3>Количество учеников  <br /><small>по неделям у каждого преподавателя</small></h3>'
	});
}
function build_single(e,r,all_teachers,color_arr){
	var m = e;
	var legend_arr = r;
	//	console.log(m);
	$("#stackedGraph_multi").jqBarGraph({
		all: false,
		single: true,
		single_data: [color_arr[m]],
		data: legend_arr,
		colors: color_arr,
		legends: all_teachers,
		legend: true,
		width: 1500,
		type: 'multi',
		animate: true,
	//	postfix: ' учеников',
		title: '<h3>Количество учеников  <br /><small>по неделям у каждого преподавателя</small></h3>'
	});
} 
function build2(all_teachers,color_arr){
	$("#graphHolder_sum").remove();
	$("#stackedGraph").remove();
	$('<div id="stackedGraph"></div>').appendTo($("#stackedGraph_wrapper"));
	$("#stackedGraph").jqBarGraph({
		sum: true,
		data: arrayOfDataMulti,
		colors: color_arr,
		legends: all_teachers,
		legend: true,
		width: 1500,
		animate: true,
	//	type: 'multi',
	//	postfix: ' учеников',
		title: '<h3><small>Сумма по неделям</small></h3>'
	});
}
function teacher_calculate(){
	var t = $('#teacher_select option:selected').val();
	$.ajax({
		type:'POST',
		async: false,
		url:'./oldphpfiles/attendance_filter.php',
		dataType:'json',
		data: {teacher:t},
		success: function(data){
			// console.log(data);
			if($('#timetable_select')){$('#timetable_select').remove();}
			$('#teacher_select').after("<select id='timetable_select' onchange='timetable_calculate()' name='timetable_choose'>");
			for(i in data){
				$('#timetable_select').append("<option value='"+data[i]+"'>"+data[i]+"</option>");
			}
		},
		error: function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}   
	});
}

// function timetable_calculate(){
 // 	var t = $('#teacher_select option:selected').val();
	// var y = $('#timetable_select option:selected').val();
	// $.ajax({
	// 	type:'POST',
	// 	async: false,
	// 	url:'attendance_filter2.php',
	// 	dataType:'json',
	// 	data: {teacher:t,timetable:y},
	// 	success: function(data){
	// 		// console.log(data);
	// 		if($('#start_select')){$('#start_select').remove();}
	// 		$('#timetable_select').after("<select id='start_select' onchange='timetable_calculate()' name='level_start_choose'>");
	// 		for(i in data){
	// 			$('#start_select').append("<option value='"+data[i]+"'>"+data[i]+"</option>");
	// 		}
	// 	},
	// 	error: function(xhr, str){
	// 		alert('Возникла ошибка: ' + xhr.responseCode);
	// 	}
	// });
 // }
 // BLOCKS
// function teacher_calculate_blocks(){
// 	var t = $('#teacher_select option:selected').val();
// 	$.ajax({
// 		type:'POST',
// 		async: false,
// 		url:'attendance_filter.php',
// 		dataType:'json',
// 		data: {teacher:t},
// 		success: function(data){
// 			console.log(data);
// 			if($('#timetable_select')){$('#timetable_select').remove();}
// 			$('#teacher_select').after("<select id='timetable_select' onchange='timetable_calculate()' name='timetable_choose'>");
// 			for(i in data){
// 				$('#timetable_select').append("<option value='"+data[i]+"'>"+data[i]+"</option>");
// 			}
// 		},
// 		error: function(xhr, str){
// 			alert('Возникла ошибка: ' + xhr.responseCode);
// 		}   
// 	});
// }

// function timetable_calculate_blocks(){
//  	var t = $('#teacher_select option:selected').val();
// 	var y = $('#timetable_select option:selected').val();
// 	$.ajax({
// 		type:'POST',
// 		async: false,
// 		url:'attendance_filter2.php',
// 		dataType:'json',
// 		data: {teacher:t,timetable:y},
// 		success: function(data){
// 			console.log(data);
// 			if($('#start_select')){$('#start_select').remove();}
// 			$('#timetable_select').after("<select id='start_select' onchange='timetable_calculate()' name='level_start_choose'>");
// 			for(i in data){
// 				$('#start_select').append("<option value='"+data[i]+"'>"+data[i]+"</option>");
// 			}
// 		},
// 		error: function(xhr, str){
// 			alert('Возникла ошибка: ' + xhr.responseCode);
// 		}
// 	});
//  }

function amount_of_money_fn(){
	var msg = $('#widgetField span').get(0).innerHTML;
	var new_msg = msg.split(" ÷ ");

	$.ajax({
		type:'POST',
		url: './oldphpfiles/amount_of_money_server.php',
		dataType: 'json',
		data: {from:new_msg[0],to:new_msg[1]},
		success: function(data){
			// console.log(data);
			// diag_money(data);	
			$("#graphHolder").remove();
			$("#Graph_money").remove();
			$('<div id="Graph_money"></div>').prependTo($("#stackedGraph_wrapper"));
			$("#Graph_money").jqBarGraph({data: data,width: 1500,animate: false});
		},
		error:  function(xhr, str){
			alert('Возникла ошибка !!!: ' + xhr.responseCode);
		}
	});
}

function add_discount(r,t,u,i,p){
		var r = r;
		var t = t;
		var u = u;
		var i = i;
		var person = p;
		var discount_value = $("#discount_add_"+i).val();
		// alert("r= "+r+" t= "+t+" u= "+u+" i= "+i+" discount_value= "+discount_value);
		// return false;
		$.ajax({
			type: 'POST',
			async: false,
			url: './oldphpfiles/add_discount.php',
			dataType: 'json',
			data: {teacher:r,timetable:t,level_start:u,i:i,discount_value:discount_value,person:p},
			success: function(data){
				// console.log(data);
				get_person_discount(p,r,t,u,i);
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});  
	}

function get_person_discount(id,teacher,timetable,level_start,i){
		var id = id;
		var teacher = "'"+teacher+"'";
		var timetable = "'"+timetable+"'";
		var level_start = "'"+level_start+"'";
		var i = i;
		$.ajax({
			type:'POST',
			async:false,
			url: "./Person/GetPersonDiscountReason",
			dataType:'json',
			data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start},
			success: function(data) {
				//console.log(data);
                //$('#attendance_table').html(data);
				if('discount' in data){$('#discount_set_'+i).val(data['discount']);}else if(data['discount'] == null){$('#discount_set_'+i).val('Нет скидки');}
                //if ("home" in assoc_pagine)
				if('reason' in data){$('#reason_set_'+i).val(data['reason']);}else if(data['reason'] == null){$('#reason_set_'+i).val('Нет причины');}
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}

function add_person_reason(r,t,u,i,p){
		var person = "'"+p+"'";
		var reason = "'"+reason+"'";
		var r = "'"+r+"'";
		var t = "'"+t+"'";
		var u = "'"+u+"'";
		var i = i;
		var reason = $("#reason_add_"+i).val();
		$.ajax({
			type:'POST',
			async:false,
			url: "./oldphpfiles/add_person_reason.php",
			dataType:'json',
			data: {person:person,teacher:r,timetable:t,level_start:u,i:i,reason:reason},
			success: function(data) {
				console.log(data);
				if(data){$('#reason_set_'+i).val(data);}else if(data == false){$('#reason_set_'+i).val('Нет причины');}
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}

function person_match(id){
    var pay_flag = 0;
	var fio;
	$.ajax({
		type: 'POST',
		async: false,
		url: './Person/NameSumCombinationsFrozenDatesBalance',
		dataType: 'json',
		data: {id:id},
		success: function(data) {
            $('#attendance_table').html(data);
			name = data['name'];
			$('#attendance_table').html("<div class='table_title'><h3>"+name+"</h3></div>");
            $('#attendance_table').append("<p>Всего внес: "+data['sum']+"</p>");
            if(data['balance']){$('#attendance_table').append("<p>Баланс: "+data['balance']+"</p>");}else{
                $('#attendance_table').append("<p>Баланс: 0</p>");
            }
            if(data['allCombinationsOfThisPerson']){
                var freeze_dates_arr = Array();
                for(var i = 0;i<data['allCombinationsOfThisPerson'].length;i++) {
                    freeze_dates_arr[i] = data['frozenDatesOfStudent'][i];
                    var teacher = data['allCombinationsOfThisPerson'][i]['teacher'];
                    var timetable = data['allCombinationsOfThisPerson'][i]['timetable'];
                    var level_start = data['allCombinationsOfThisPerson'][i]['level_start'];
                    var level = data['allCombinationsOfThisPerson'][i]['level'];
                    var level_date = [];
                    var person_start;
                    var person_stop;
                    var numberOfStartLesson;
                    $('#attendance_table').append("<div class='xxx'><p id='stack_" + i + "'>" + teacher + "/" + timetable + "/" + level_start + "/" + level + "</p></div>");
                    $('#attendance_table').append("<div class='main_form  main_form_" + i + "'><table width='50%'' class='attendance_table default_table' id='attendance_table_" + i + "'></table></div>")
                    //----- Построение шапки таблицы ;
                    $("#attendance_table_" + i).empty();

                    $("#attendance_table_" + i).html("<tbody><tr id='th_line_" + i + "'><th class='attendance_name_th' id='name_th_" + i + "'><div id='attendance_table_name_" + i + "'>Имя</div></th></tr></tbody>");
                    $("#attendance_table_" + i).append("<div><lebel for='discount_set_" + i + "'>текущая скидка на сочетание</lebel><input type='text' name='discount_set_" + i + "' id='discount_set_" + i + "' class='reason_set' style='border:0px' readonly/><br /><lebel for='discount_add_" + i + "'>изменить текущую скидку на</lebel><input type='text' name='discount_add_" + i + "' id='discount_add_" + i + "'/><button onclick=add_discount('" + teacher + "','" + timetable + "','" + level_start + "','" + i + "','" + id + "')>изменить</button></div>");
                    $("#attendance_table_" + i).append("<div><lebel for='reason_set_" + i + "'>текущая причина скидки</lebel><input type='text' name='reason_set_" + i + "' id='reason_set_" + i + "' class='reason_set' style='border:0px' readonly/><br /><lebel for='reason_add_" + i + "'>изменить текущую причину на</lebel><input type='text' name='reason_add_" + i + "' id='reason_add_" + i + "'/><button onclick=add_person_reason('" + teacher + "','" + timetable + "','" + level_start + "','" + i + "','" + id + "')>изменить</button></div>");
                    get_person_discount(id, teacher, timetable, level_start, i);

					$.ajax({
						type: 'POST',
						async: false,
                        url: './Person/CombinationDatesFittedToTimetable',
						dataType: 'json',
						data: {teacher:teacher,timetable:timetable,level_start:level_start},
						success: function(data) {
							for(var g=21;g>0;g--){
								$('#name_th_'+i).after("<th class='attendance_th'><div class='rotateText'>"+data['sd_'+g]+"</div></th>");
								level_date[g-1]=data['sd_'+g];
							}
						},
						error: function(xhr, str){
							alert('Возникла ошибка: ' + xhr.responseCode);
						}
					});
					//----- /Построение шапки таблицы

					//----- Фамилии даты

					$.ajax({
						type: 'POST',
						async: false,
						url: './Person/StudentNameAndDates.php',
						dataType: 'json',
						data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start},
						success: function(data){
                            person_start = data['person_start'];
                            person_stop = data['person_stop'];
                            numberOfStartLesson = data['numberOfStartLesson'];
							var y =0;
							var w =0;
							//var p =0;
							//var e =0;
							//var arr_id = [];
							var color = 0;
							var match = 0;
							$('#th_line_'+i).after("<tr id='tr"+y+"'></tr>");
							var flag = 0;
                            $('#attendance_table_'+i+' #tr'+y).after("<tr id='tr"+(y+1)+"'></tr>");
                            $('#attendance_table_'+i+' #tr'+y).html("<td id='td"+y+"_"+w+"'>"+data['name']+"</td>");
                            for(var q=0;q<21;q++){
                                color = 0;
                                color_freeze = 0;
                                start_date = 0;
                                stop_date = 0;
                                before_person_start = 1;
                                after_person_stop = 0;
                                for(var c in data['datesOfVisit']){
                                    if(level_date[q]  == data['datesOfVisit'][c]){
                                        match = data['datesOfVisit'][c];
                                        color = 1;
                                    }else{
                                        match = level_date[q];
                                    }	// ДАТЫ ПОСЕЩЕНИЙ (совпадение с датами сочетания)
                                    if(level_date[q] < data['person_start']){
                                            before_person_start = 1;
                                    }else if(level_date[q] >= data['person_start']){
                                            before_person_start = 0;
                                    }	// ОПРЕДИЛЕНИЕ ДО/ПОСЛЕ ДАТЫ СТАРТА ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
                                    if(level_date[q] > data['person_stop']){
                                        after_person_stop = 1;
                                    }else if(level_date[q] <= data['person_stop']){
                                        after_person_stop = 0;
                                    }	// ОПРЕДИЛЕНИЕ ДО/ПОСЛЕ ДАТЫ СТОП ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
                                }
                                for(var g in freeze_dates_arr[i]){
                                    if(level_date[q]  == freeze_dates_arr[i][g]){
                                        color_freeze = 1;
                                    }
                                }

                                if(level_date[q]  == data['person_start']){
                                    start_date = 1;
                                }
                                if(level_date[q] == data['person_stop']){
                                    stop_date = 1;
                                }	// ОПРЕДИЛЕНИЕ ДАТЫ СТАРТ/СТОП ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
                                if(color == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark before_person_start_mark color'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark color'>"+match+"</td>");
                                }else if(color == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark before_person_start_mark color'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark color'>"+match+"</td>");
                                }else if(color == 1 && start_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark person_start_mark color'>"+match+"</td>");
                                }
                                // color_freeze
                                else if(color_freeze == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark before_person_start_mark color_freeze'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark color_freeze'>"+match+"</td>");
                                }else if(color_freeze == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark before_person_start_mark color_freeze'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark color_freeze'>"+match+"</td>");
                                }else if(color_freeze == 1 && start_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark person_start_mark color_freeze'>"+match+"</td>");
                                }
                                // /color_freeze

                                else if(color == 0 && start_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark person_start_mark'>"+match+"</td>");
                                }else if(color == 1 && stop_date == 1){
                                    // console.log(color, stop_date);
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark person_stop_mark color'>"+match+"</td>");
                                }
                                // color_freeze
                                else if(color_freeze == 1 && stop_date == 1){
                                    // console.log(color, stop_date);
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark person_stop_mark color_freeze'>"+match+"</td>");
                                }
                                // /color_freeze
                                else if(color == 0 && stop_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark person_stop_mark'>"+match+"</td>");
                                }else if(color == 0 && before_person_start == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark before_person_start_mark'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 0 && after_person_stop == 0 && before_person_start == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark'>"+match+"</td>");
                                }else if(color == 0 && after_person_stop == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark before_person_start_mark'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 0 && after_person_stop == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='payment_mark attendence_mark'>"+match+"</td>");
                                }
                                w++;
                            }
                            y++;
						},
						error: function(xhr, str){
							alert('Возникла ошибка: ' + xhr.responseCode);
						}
					});
                    markAllPayedDates();
				}
				function markAllPayedDates(){
					var person_start_arr =[];
                    var nameQuoted= "'"+name+"'";
                    var idQuoted= "'"+id+"'";
                    var teacherQuoted = "'"+teacher+"'";
                    var timetableQuoted = "'"+timetable+"'";
                    var level_startQuoted = "'"+level_start+"'";
                    var levelQuoted = "'"+level+"'";
                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: './Person/NumPayedNumReservedCostOfOneLessonWithDiscount.php',
                        dataType: 'json',
                        data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start},
                        success: function(data) {
                            var cell_now = numberOfStartLesson;
                            $('#stack_'+i).after("<p>Оплачено "+data['num_payed']+" ("+(data['num_payed']*data['CostOfOneLessonWithDiscount']).toFixed(2)+") из "+data['num_reserved']+" ("+(data['num_reserved']*data['CostOfOneLessonWithDiscount']).toFixed(2)+")</p><p>Осталось оплатить: "+((data['num_reserved']-data['num_payed'])*data['CostOfOneLessonWithDiscount']).toFixed(2)+"</p>");
                            $('.main_form_'+i).prepend('<div class="removePersonFromCombo"><button onClick="removePersonFromCombo('+nameQuoted+','+idQuoted+','+teacherQuoted+','+timetableQuoted+','+level_startQuoted+','+levelQuoted+')">Удалить студента с данного сочентания X </button></div>');
                            for(var b=0; b<data['num_payed'];b++){
                                check();
                                function check(){
                                    if($('#attendance_table_'+i+' #td0_'+cell_now).hasClass('color_freeze')){
                                        cell_now++;
                                        check();
                                    }
                                }
                                $('#attendance_table_'+i+' #td0_'+cell_now).addClass('payed_lesson');
                                cell_now++;
                            }
                        },
                        error: function(xhr, str){
                            alert('Возникла ошибка: ' + xhr.responseCode);
                        }
                    });
				}

				function pay3(){
					var person_start_arr =[];
					for(var i =0;i<data[2].length;i++){
						person_start_arr[i] = parseInt($('#attendance_table_'+i+' .person_start_mark').attr('id').replace('td0_', ''));
						// console.log(person_start_arr);
						$.ajax({
							type: 'POST',
							async: false,
							url: './oldphpfiles/payed_lessons.php',
							dataType: 'json',
							data: {id_person:person,teacher:data[2][i][0],timetable:data[2][i][1],level_start:data[2][i][2]},
							success: function(data) {
								var cell_now2 = person_start_arr[i];
								for(var w=0; w<data[0][1];w++){
									$('#attendance_table_'+i+' #td0_'+cell_now2).removeClass('payed_lesson');
									cell_now2++;
								}
							},
							error: function(xhr, str){
								alert('Возникла ошибка: ' + xhr.responseCode);
							}
						});
					}
				}

				function unmarkPayedFromAllReservedDates(){
						$.ajax({
							type: 'POST',
							async: false,
                            url: './Person/NumPayedNumReservedCostOfOneLessonWithDiscount.php',
							dataType: 'json',
							data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start},
							success: function(data) {
                                var cell_now = numberOfStartLesson;
								for(var w=0; w<data['num_reserved'];w++){
									$('#attendance_table_'+i+' #td0_'+cell_now).removeClass('payed_lesson');
                                    cell_now++;
								}
							},
							error: function(xhr, str){
								alert('Возникла ошибка: ' + xhr.responseCode);
							}
						});

				}

				//pay3();
				//pay2();
			}else{$('#attendance_table').append("<p> Студент не учиться ни на одном сочетании</p>");}
		},
		error: function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});

	if(location.pathname == "/bs_mvc/person"){
		$('.payment_mark').not('.before_person_start_mark').not('.color_freeze').click(function(){
			function getParameterByName(name) {
				name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
				var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
				results = regex.exec(location.search);
				return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
			}
			var id = getParameterByName('id');
			var pay_flag = 0;
			var IfThisDatePayed = $(event.target).attr('class');
            IfThisDatePayedArray = IfThisDatePayed.split(" ");
            for(var i in IfThisDatePayedArray){
                if(IfThisDatePayedArray[i] == "payed_lesson"){pay_flag=1;}
            }
            var whatCombo = $(event.target).parents('.attendance_table').attr('id');
            //console.log(whatCombo);return;
            var whatComboByNumber = whatCombo.split("_");
            var ComboNumber = whatComboByNumber[2];
            var Combo = $('#stack_'+ComboNumber).text().split('/');
            // console.log(stack);
			// return false;
			var teacher = Combo[0];
			var timetable = Combo[1];
			var level_start = Combo[2];
			if(pay_flag == 0){
				var addOrRemove = 1; // добавляем занятие, отнимаем из баланса
				$.ajax({
					type:'POST',
					url: './Person/AddOrRemovePayedDate',
					data: {id:id,addOrRemove:addOrRemove,teacher:teacher,timetable:timetable,level_start:level_start},
					success: function(data){
						person_match(id);
					},
					error:  function(xhr, str){
						alert('Возникла ошибка: ' + xhr.responseCode);
					}
				});
			}else if(pay_flag == 1){
				var addOrRemove = -1; // минус один значит отнимаем проплату урока и добавляем его стоимость в баланс
				$.ajax({
					type:'POST',
                    url: './Person/AddOrRemovePayedDate',
					data: {id:id,addOrRemove:addOrRemove,teacher:teacher,timetable:timetable,level_start:level_start},
					success: function(data){
						person_match(id);
					},
					error:  function(xhr, str){
						alert('Возникла ошибка: ' + xhr.responseCode);
					}
				});
			}
		});
	}
}

function freeze_match(id){
	$.ajax({
		type: 'POST',
		async: false,
		url: './Freeze/BuildFreezeTable',
		dataType: 'json',
		data: {id:id},
		success: function(data) {
            //console.log(data);
            //return;
            var freeze_dates_arr = data['frozenDatesOfStudentOnEachCombination'] ;
			$('#attendance_table').html("<div class='table_title'><h3>"+data['name']+"</h3></div>");
            // $('#attendance_table').append("<p>Всего внес: "+data[1]+"</p>");
            if(data['numOfLessonsOnEachCombination']){
                //var freeze_dates_arr = Array();
                for(var i =0;i<data['numOfLessonsOnEachCombination'].length;i++){
                    //freeze_dates_arr[i] = data[5][i];
					//var tyt = data[2][i][2];
                    $('#attendance_table').append("<p id='stack_"+i+"'>"+data['combinations'][i]['teacher']+"/"+data['combinations'][i]['timetable']+"/"+data['combinations'][i]['level_start']+"/"+data['combinations'][i]['level']+"</p>");
                    $('#attendance_table').append("<div class='main_form'><table width='50%'' class='attendance_table default_table' id='attendance_table_"+i+"'></table></div>")
					var level_date = [] ;
					var teacher = data['combinations'][i]['teacher'];
                    var timetable = data['combinations'][i]['timetable'];
                    var level = data['combinations'][i]['level'];
                    var level_start = data['combinations'][i]['level_start'];

					//----- Построение шапки таблицы ;
					$("#attendance_table_"+i).empty();
                    $("#attendance_table_"+i).html("<tbody><tr id='th_line_"+i+"'><th class='attendance_name_th' id='name_th_"+i+"'><div id='attendance_table_name_"+i+"'>Имя</div></th></tr></tbody>");
                    //return;

                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: './Freeze/CombinationDatesFittedToTimetable',
                        dataType: 'json',
                        data: {teacher:teacher,timetable:timetable,level_start:level_start},
                        success: function(data) {
                            //console.log(data);
                            //return;
                            for(var g=21;g>0;g--){
                                $('#name_th_'+i).after("<th class='attendance_th'><div class='rotateText'>"+data['sd_'+g]+"</div></th>");
                                level_date[g-1]=data['sd_'+g];
                            }
                        },
                        error: function(xhr, str){
                            alert('Возникла ошибка: ' + xhr.responseCode);
                        }
                    });
					//----- /Построение шапки таблицы



					//----- Фамилии даты 


					//$.ajax({
					//	type: 'POST',
					//	async: false,
					//	url: './oldphpfiles/lgtt_match.php',
					//	dataType: 'json',
					//	// data: msg,
					//	data: {teacher_choose:data[2][i][0],timetable_choose:data[2][i][1],level_start_choose:data[2][i][2]},
					//	success: function(data) {
					//		var y =0;
					//		var w =0;
					//		var p =0;
					//		var e =0;
					//		var arr_id = [];
					//		var color = 0;
					//		var match = 0;
					//		var arr_id = [];
					//		$('#th_line_'+i).after("<tr id='tr"+y+"'></tr>");
					//		var flag = 0;
					//		for (var v in data) {
					//			var split_id = v.split('|');
					//			if(person == split_id[1]){
					//			// console.log(split_id);
					//				$('#attendance_table_'+i+' #tr'+y).after("<tr id='tr"+(y+1)+"'></tr>");
					//				$('#attendance_table_'+i+' #tr'+y).html("<td id='td"+y+"_"+w+"'>"+split_id[0]+"</td>");
					//				// console.log(y);
					//				arr_id[y] = split_id[1];
					//				for(var q=0;q<21;q++){
					//					color = 0;
					//					color_freeze = 0;
					//					start_date = 0;
					//					stop_date = 0;
					//					before_person_start = 1;
					//					after_person_stop = 0;
					//					// w++;
					//					for(var c in data[v]['dates']){
					//						if(level_date[q]  == data[v]['dates'][c]){
					//							color = 1;
					//						}
					//						match = level_date[q];
					//						// ДАТЫ ПОСЕЩЕНИЙ (совпадение с датами сочетания)
					//						if(level_date[q] < split_id[2]){
					//								before_person_start = 1;
					//						}else if(level_date[q] >= split_id[2]){
					//								before_person_start = 0;
					//						}	// ОПРЕДИЛЕНИЕ ДО/ПОСЛЕ ДАТЫ СТАРТА ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
					//						if(level_date[q] > split_id[3]){
					//							after_person_stop = 1;
					//						}else if(level_date[q] <= split_id[3]){
					//							after_person_stop = 0;
					//						}	// ОПРЕДИЛЕНИЕ ДО/ПОСЛЕ ДАТЫ СТОП ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
					//					}
					//					for(var g in freeze_dates_arr[i]){
					//						if(level_date[q]  == freeze_dates_arr[i][g]){
					//							color_freeze = 1;console.log('fuck!!!');
					//						}
					//					}
                    //
					//					if(level_date[q]  == split_id[2]){
					//						start_date = 1;
					//					}
					//					if(level_date[q] === split_id[3]){
					//						stop_date = 1;
					//					}	// ОПРЕДИЛЕНИЕ ДАТЫ СТАРТ/СТОП ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
					//					var trata = color+" | "+start_date+" | "+stop_date+" | "+before_person_start+" | "+after_person_stop;
					//					// console.log(match);
					//					if(color == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark color'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark color'>"+match+"</td>");
					//					}else if(color == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark color'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark color'>"+match+"</td>");
					//					}else if(color == 1 && start_date == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td bgcolor='#0033FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_start_mark color'>"+match+"</td>");
					//					}
                    //
					//					else if(color_freeze == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){ // color_freeze
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark color_freeze'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark color_freeze'>"+match+"</td>");
					//					}else if(color_freeze == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark color_freeze'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td bgcolor='#0033FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark color_freeze'>"+match+"</td>");
					//					}else if(color_freeze == 1 && start_date == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_start_mark color_freeze'>"+match+"</td>");
					//					} // /color_freeze
                    //
					//					else if(color == 0 && start_date == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_start_mark'>"+match+"</td>");
					//					}else if(color == 1 && stop_date == 1){
					//						// console.log(color, stop_date);
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td bgcolor='#0033FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_stop_mark color'>"+match+"</td>");
					//					}
					//					else if(color_freeze == 1 && stop_date == 1){  // color_freeze
					//						// console.log(color, stop_date);
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td bgcolor='#0033FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_stop_mark color_freeze'>"+match+"</td>");
					//					} // /color_freeze
                    //
					//					else if(color == 0 && stop_date == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_stop_mark'>"+match+"</td>");
					//					}else if(color == 0 && before_person_start == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color == 0 && after_person_stop == 0 && before_person_start == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark'>"+match+"</td>");
					//					}else if(color == 0 && after_person_stop == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color == 0 && after_person_stop == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark'>"+match+"</td>");
					//					}
					//					w++;
					//				}
					//				y++;
					//			}
                    //
					//		}
                    //
					//	},
					//	error: function(xhr, str){
					//		alert('Возникла ошибка: ' + xhr.responseCode);
					//	}
					//});

                    $.ajax({
                        type: 'POST',
                        async: false,
                        //url: './Person/StudentNameAndDates.php',
                        url: './Freeze/StudentNameAndDates.php',
                        dataType: 'json',
                        data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start},
                        success: function(data){
                            //console.log(data);
                            //return;
                            person_start = data['person_start'];
                            person_stop = data['person_stop'];
                            numberOfStartLesson = data['numberOfStartLesson'];
                            var y =0;
                            var w =0;
                            //var p =0;
                            //var e =0;
                            //var arr_id = [];
                            var color = 0;
                            var match = 0;
                            $('#th_line_'+i).after("<tr id='tr"+y+"'></tr>");
                            var flag = 0;
                            $('#attendance_table_'+i+' #tr'+y).after("<tr id='tr"+(y+1)+"'></tr>");
                            $('#attendance_table_'+i+' #tr'+y).html("<td id='td"+y+"_"+w+"'>"+data['name']+"</td>");
                            for(var q=0;q<21;q++){
                                color = 0;
                                color_freeze = 0;
                                start_date = 0;
                                stop_date = 0;
                                before_person_start = 1;
                                after_person_stop = 0;
                                for(var c in data['datesOfVisit']){
                                    if(level_date[q]  == data['datesOfVisit'][c]){
                                        match = data['datesOfVisit'][c];
                                        color = 1;
                                    }else{
                                        match = level_date[q];
                                    }	// ДАТЫ ПОСЕЩЕНИЙ (совпадение с датами сочетания)
                                    if(level_date[q] < data['person_start']){
                                        before_person_start = 1;
                                    }else if(level_date[q] >= data['person_start']){
                                        before_person_start = 0;
                                    }	// ОПРЕДИЛЕНИЕ ДО/ПОСЛЕ ДАТЫ СТАРТА ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
                                    if(level_date[q] > data['person_stop']){
                                        after_person_stop = 1;
                                    }else if(level_date[q] <= data['person_stop']){
                                        after_person_stop = 0;
                                    }	// ОПРЕДИЛЕНИЕ ДО/ПОСЛЕ ДАТЫ СТОП ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
                                }
                                for(var g in freeze_dates_arr[i]){
                                    if(level_date[q]  == freeze_dates_arr[i][g]){
                                        color_freeze = 1;
                                    }
                                }
                                if(level_date[q]  == data['person_start']){
                                    start_date = 1;
                                }
                                if(level_date[q] == data['person_stop']){
                                    stop_date = 1;
                                }	// ОПРЕДИЛЕНИЕ ДАТЫ СТАРТ/СТОП ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
                                if(color == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark color'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark color'>"+match+"</td>");
                                }else if(color == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark color'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark color'>"+match+"</td>");
                                }else if(color == 1 && start_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_start_mark color'>"+match+"</td>");
                                }
                                // color_freeze
                                else if(color_freeze == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark color_freeze'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark color_freeze'>"+match+"</td>");
                                }else if(color_freeze == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark color_freeze'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark color_freeze'>"+match+"</td>");
                                }else if(color_freeze == 1 && start_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_start_mark color_freeze'>"+match+"</td>");
                                }
                                // /color_freeze

                                else if(color == 0 && start_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_start_mark'>"+match+"</td>");
                                }else if(color == 1 && stop_date == 1){
                                    // console.log(color, stop_date);
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_stop_mark color'>"+match+"</td>");
                                }
                                // color_freeze
                                else if(color_freeze == 1 && stop_date == 1){
                                    // console.log(color, stop_date);
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_stop_mark color_freeze'>"+match+"</td>");
                                }
                                // /color_freeze
                                else if(color == 0 && stop_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark person_stop_mark'>"+match+"</td>");
                                }else if(color == 0 && before_person_start == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 0 && after_person_stop == 0 && before_person_start == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark'>"+match+"</td>");
                                }else if(color == 0 && after_person_stop == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark before_person_start_mark'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 0 && after_person_stop == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark attendence_mark'>"+match+"</td>");
                                }
                                w++;
                            }
                            y++;
                        },
                        error: function(xhr, str){
                            alert('Возникла ошибка: ' + xhr.responseCode);
                        }
                    });
                    markAllPayedDates();

				}
                function markAllPayedDates(){
                    var person_start_arr =[];
                    var nameQuoted= "'"+name+"'";
                    var idQuoted= "'"+id+"'";
                    var teacherQuoted = "'"+teacher+"'";
                    var timetableQuoted = "'"+timetable+"'";
                    var level_startQuoted = "'"+level_start+"'";
                    var levelQuoted = "'"+level+"'";
                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: './Person/NumPayedNumReservedCostOfOneLessonWithDiscount.php',
                        dataType: 'json',
                        data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start},
                        success: function(data) {
                            var cell_now = numberOfStartLesson;
                            $('#stack_'+i).after("<p>Оплачено "+data['num_payed']+" ("+(data['num_payed']*data['CostOfOneLessonWithDiscount']).toFixed(2)+") из "+data['num_reserved']+" ("+(data['num_reserved']*data['CostOfOneLessonWithDiscount']).toFixed(2)+")</p><p>Осталось оплатить: "+((data['num_reserved']-data['num_payed'])*data['CostOfOneLessonWithDiscount']).toFixed(2)+"</p>");
                            $('#stack_'+i).after("<input class='one_lesson_"+i+"' type='hidden' value='"+data['CostOfOneLessonWithDiscount']+"' />");
                            $('.main_form_'+i).prepend('<div class="removePersonFromCombo"><button onClick="removePersonFromCombo('+nameQuoted+','+idQuoted+','+teacherQuoted+','+timetableQuoted+','+level_startQuoted+','+levelQuoted+')">Удалить студента с данного сочентания X </button></div>');
                            for(var b=0; b<data['num_payed'];b++){
                                check();
                                function check(){
                                    if($('#attendance_table_'+i+' #td0_'+cell_now).hasClass('color_freeze')){
                                        cell_now++;
                                        check();
                                    }
                                }
                                $('#attendance_table_'+i+' #td0_'+cell_now).addClass('payed_lesson');
                                cell_now++;
                            }
                        },
                        error: function(xhr, str){
                            alert('Возникла ошибка: ' + xhr.responseCode);
                        }
                    });
                }
				// console.log($freeze_dates_arr);
			}

		},
		error: function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});

	if(location.pathname == "/bs_mvc/Freeze"){
		$('.freeze_mark').unbind('click');
		$('.freeze_mark').not('.before_person_start_mark').not('.color').click(function(){
			var id = getParameterByName('id');

			var IfThisDateFrozenOrPayed = $(event.target).attr('class');
            var IfThisDateFrozenOrPayedArray = IfThisDateFrozenOrPayed.split(" ");

			var numOfCombinationArray = $(event.target).parents('.attendance_table').attr('id');
            var numOfCombination = numOfCombinationArray.split("_");
            numOfCombination = numOfCombination[2];
            var combination = $('#stack_'+numOfCombination).text().split('/');
            var teacher = combination[0];
            var timetable = combination[1];
            var level_start = combination[2];

            var costOfOneLessonWithDiscount = $('.one_lesson_'+numOfCombination).val();

            var date = $(this).text();

            var isFrozen=false;
            for(var y in IfThisDateFrozenOrPayedArray){
				if(IfThisDateFrozenOrPayedArray[y]=="color_freeze"){isFrozen=true;}
			}

            var isPayed=false;
            for(var y in IfThisDateFrozenOrPayedArray){
                if(IfThisDateFrozenOrPayedArray[y]=="payed_lesson"){isPayed=true;}
            }
            $.ajax({
                type:'POST',
                url: './Freeze/ChangeFrozenDate',
                data: {isPayed:isPayed,isFrozen:isFrozen,id:id,date:date,teacher:teacher,timetable:timetable,level_start:level_start,costOfOneLessonWithDiscount:costOfOneLessonWithDiscount},
                success: function(data){
                    freeze_match(id);
                },
                error:  function(xhr, str){
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
		});
	}
}

function get_timetable(teacher){
	if($('.timetable_soch')){$('.timetable_soch').remove();}
	if($('.level_start_soch')){$('.level_start_soch').remove();}
	if($('.level_soch')){$('.level_soch').remove();}
	if($('.person_start_soch')){$('.person_start_soch').remove();}
	if($('.person_stop_soch')){$('.person_stop_soch').remove();}
	if(teacher == "choose_teacher"){
		if($('.timetable_soch')){$('.timetable_soch').remove();}
	}else{
		var teacherQuoted= "'"+teacher+"'";
		$.ajax({
			type: 'POST',
			url: './Main/timeTables.php',
			dataType: 'json',
			data: {teacher:teacher},
			success: function(data) {
                $('.timetable_soch').remove();
				$('.teacher_soch').after('<div class="item timetable_soch"><label for="timetable_sel">Расписание:</label><select name="timetable_sel" id="timetable_sel" class="add_form_select" onchange="get_level_start('+teacherQuoted+',this.value)"></select></div>');
			$('#timetable_sel').append('<option value="choose_timetable">Выберите расисание</option>');
				for(var i in data){
					$('#timetable_sel').append('<option value="'+data[i]+'">'+data[i]+'</option>');
				}
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}
}

function get_level_start(teacher,timetable){
	if($('.level_start_soch')){$('.level_start_soch').remove();}
	if($('.level_soch')){$('.level_soch').remove();}
	if($('.person_start_soch')){$('.person_start_soch').remove();}
	if($('.person_stop_soch')){$('.person_stop_soch').remove();}
	if(timetable == "choose_timetable"){
		if($('.level_start_soch')){$('.level_start_soch').remove();}
	}else{
		var teacherQuoted= "'"+teacher+"'";
		var timetableQuoted = "'"+timetable+"'";
		var id_person = "'"+$('input#id_person').val()+"'";
		$.ajax({
			type: 'POST',
			url: './Main/LevelStart.php',
			dataType: 'json',
			data: {teacher:teacher,timetable:timetable},
			success: function(data) {
				$('.level_start_soch').remove();
				$('.timetable_soch').after('<div class="item level_start_soch"><label for="level_start_sel">Дата старта уровня:</label><select name="level_start_sel" id="level_start_sel" class="add_form_select" onchange="get_level('+teacherQuoted+','+timetableQuoted+',this.value,'+id_person+')"></select></div>');
				$('#level_start_sel').append('<option value="choose_level_start">Выберите дату старта уровня</option>');
				for(var i in data){
					$('#level_start_sel').append('<option value="'+data[i]+'">'+data[i]+'</option>');
				}
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}
}
function get_level(teacher,timetable,level_start,id){
	if($('.level_soch')){$('.level_soch').remove();}
	if($('.person_start_soch')){$('.person_start_soch').remove();}
	if($('.person_stop_soch')){$('.person_stop_soch').remove();}
	if(level_start == "choose_level_start"){
		if($('.level_soch')){$('.level_soch').remove();}
	}else{
		$.ajax({
			type: 'POST',
			url: './Main/AreAnyPayedOrAttenedOrFrozenLessonsExist',
			dataType: 'json',
			data: {teacher:teacher,timetable:timetable,level_start:level_start,id:id},
			success: function(data) {
				//console.log(data);
                var AreAnyPayedOrAttenedOrFrozenLessonsExist = data;
                $.ajax({
                    type: 'POST',
                    url: './Main/LevelCombinationDates',
                    dataType: 'json',
                    data: {teacher:teacher,timetable:timetable,level_start:level_start},
                    success: function(data){
                        //console.log(data);
                        var CombinationLevel = data['combinationLevel'][0][0];
                        var CombinationDates = data['combinationDates'][0];
                        if(AreAnyPayedOrAttenedOrFrozenLessonsExist){
                            $('.level_soch').remove();
                            $('.level_start_soch').after('<div class="item level_soch"><label for="level_soch">Уровень:</label> <input class="add_form_select" type="text" id="level_soch" name="level_soch" value='+CombinationLevel+' style="border:none;" readonly></div>');

                            $('.person_start_soch').remove();
                            $('.level_soch').after('<div class="item person_start_soch"><label for="person_start_soch">Дата старта уровня:</label><select name="person_start_sel" id="person_start_sel" class="add_form_select" onchange="fix_person_stop(this.value)"></select></div>');
                            for(var i in CombinationDates){
                                if( i == 0){$('#person_start_sel').append('<option value="'+CombinationDates[i]+'" selected>'+CombinationDates[i]+'</option>');}else{$('#person_start_sel').append('<option value="'+CombinationDates[i]+'">'+CombinationDates[i]+'</option>');}
                            }
                            $('.person_start_soch').hide();

                            $('.person_stop_soch').remove();
                            $('.person_start_soch').after('<div class="item person_stop_soch"><label for="person_stop_soch">Дата финиша уровня:</label><select name="person_stop_sel" id="person_stop_sel" class="add_form_select" onchange="fix_person_start(this.value)"></select></div>');
                            for(var i in CombinationDates){
                                if( i == CombinationDates.length-1){$('#person_stop_sel').append('<option value="'+CombinationDates[i]+'" selected>'+CombinationDates[i]+'</option>');}else{$('#person_stop_sel').append('<option value="'+CombinationDates[i]+'">'+CombinationDates[i]+'</option>');}
                            }
                            $('.person_stop_soch').hide();

                            $('.warning').show();
                        }else{
                            $('.warning').hide();

                            $('.level_soch').remove();
                            $('.level_start_soch').after('<div class="item level_soch"><label for="level_soch">Уровень:</label> <input class="add_form_select" type="text" id="level_soch" name="level_soch" value='+CombinationLevel+' style="border:none;" readonly></div>');

                            $('.person_start_soch').remove();
                            $('.level_soch').after('<div class="item person_start_soch"><label for="person_start_soch">Дата старта уровня:</label><select name="person_start_sel" id="person_start_sel" class="add_form_select" onchange="fix_person_stop(this.value)"></select></div>');
                            for(var i in CombinationDates){
                                if( i == 0){$('#person_start_sel').append('<option value="'+CombinationDates[i]+'" selected>'+CombinationDates[i]+'</option>');}else{$('#person_start_sel').append('<option value="'+CombinationDates[i]+'">'+CombinationDates[i]+'</option>');}
                            }

                            $('.person_stop_soch').remove();
                            $('.person_start_soch').after('<div class="item person_stop_soch"><label for="person_stop_soch">Дата финиша уровня:</label><select name="person_stop_sel" id="person_stop_sel" class="add_form_select" onchange="fix_person_start(this.value)"></select></div>');
                            for(var i in CombinationDates){
                                if( i == CombinationDates.length-1){$('#person_stop_sel').append('<option value="'+CombinationDates[i]+'" selected>'+CombinationDates[i]+'</option>');}else{$('#person_stop_sel').append('<option value="'+CombinationDates[i]+'">'+CombinationDates[i]+'</option>');}
                            }
                        }

                    },
                    error: function(xhr, str){
                        alert('Возникла ошибка: ' + xhr.responseCode);
                    }
                });
            },
            error: function(xhr, str){
                alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });
	}
}

function fix_person_stop(date){
	var flag=0;
    $( "#person_stop_sel option" ).each(function(  ) {
        $( this ).prop('disabled', false);
    });
	$( "#person_stop_sel option" ).each(function(  ) {
		if(date == $( this ).val()){flag=1;}
		if(flag == 0){$( this ).prop('disabled', true);}
	});
}

function fix_person_start(date){
	var flag=0;
    $( "#person_start_sel option" ).each(function(  ) {
        $( this ).prop('disabled', false);
    });
	$( "#person_start_sel option" ).each(function(  ) {
        if(date == $( this ).val()){flag=1;}
        if(flag==1){$( this ).prop('disabled', true);}
	});
}

function change_start_date(r,t,u){
	var new_level_start = $('#new_level_start').val();
	if(new_level_start==""){alert('Введите новую дату старта');}else{
		$.ajax({
			type: 'POST',
			url: './oldphpfiles/change_start_date.php',
			// dataType: 'json',
			data: {teacher:r,timetable:t,level_start:u,new_level_start:new_level_start},
			success: function(data) {
				if(data=="bad"){alert('Данная дата не совпадает с расписанием(не тот день недели');}else{
					// console.log(r,t,new_level_start);
					lgtt_match_fn(r,t,new_level_start); 
					$('.brick').remove();
					building_blocks(r,t,new_level_start);
				} 
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}
}

function remove_combination(r,t,u){
	if(confirm("Вы действительно хотите удалить сочетание: "+r+"/"+t+"/"+u+" ?")){
		$.ajax({
			type: 'POST',
			url: './oldphpfiles/remove_combination.php',
			dataType: 'json',
			data: {teacher:r,timetable:t,level_start:u},
			success: function(data) {
				// console.log(r,t,new_level_start);
				$(".brick[style*='blue']").remove();
				var r = $('.brick:first').children('[name=teacher_choose]').val();
				var t = $('.brick:first').children('[name=timetable_choose]').val();
				var u = $('.brick:first').children('[name=level_start_choose]').val();
				$('.brick:first').css('borderColor','blue');
				lgtt_match_fn(r,t,u); 
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}
}

function send_to_archive(r,t,u){
	if(confirm("Вы действительно хотите отправить в архив сочетание: "+r+"/"+t+"/"+u+" ?")){
		var teacher_quoted = "'"+r+"'";
		var timetable_quoted = "'"+t+"'";
		var level_start_quoted = "'"+u+"'";

		var status = -1;

		$.ajax({
			type: 'POST',
			url: './oldphpfiles/status_update.php',
			dataType: 'json',
			data: {teacher:teacher_quoted,timetable:timetable_quoted,level_start:level_start_quoted,status:status},
			success: function(data) {
				console.log();
				$('.past_combinations').append('<div class="brick"><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_archive" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_archive" disabled /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_archive" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_archive" disabled /></div>')
				$('#teacher_choose_archive').val(r);
				$('#timetable_choose_archive').val(t);
				$('#level_start_choose_archive').val(u);

				$.ajax({
					type: 'POST',
					url: './oldphpfiles/get_level.php',
					dataType: 'json',
					data: {teacher:teacher_quoted,timetable:timetable_quoted,level_start:level_start_quoted},
					success: function(data) {
						console.log();
						$('#level_choose_archive').val(data[0]);
						$(".brick[style*='blue']").remove();
						$('.brick:first').css('borderColor','blue');
						// lgtt_match_fn(r,t,u); 
					},
					error: function(xhr, str){
						alert('Возникла ошибка: ' + xhr.responseCode);
					}
				});
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});

	}
}

function removePersonFromCombo(name,id,teacher,timetable,level_start,level){
    if(confirm("Вы действительно хотите далить студента: "+name+" с сочетания : "+teacher+"/"+timetable+"/"+level_start+" ?")){
		var notExistFlag = 0;
        var t = false;
        //return t;
		$.ajax({
			type: 'POST',
			async: false,
			url: './Person/AreAnyPayedOrAttenedOrFrozenLessonsExist.php',
			dataType: 'json',
			data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start},
			success: function(data) {
                if(data){notExistFlag = 1;}
                if(!data){alert('Для удаления студента с сочетания, удалите все проплаты, посещения либо заморозки студента на данном сочетании.');}
            },
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});

		if(notExistFlag==1){
			$.ajax({
				type: 'POST',
				async: false,
				url: './Person/RemovePersonComboPayedLessonsFrozenLessons.php',
				dataType: 'json',
				data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start},
				success: function(data) {
					$('p').each(function(){
						if($(this).text()==teacher+"/"+timetable+"/"+level_start+"/"+level){
							// console.log($(this).parent().next());
							$(this).parent().next().empty();
							$(this).parent().empty();
						}
					});
                    person_match(id)
				},
				error: function(xhr, str){
					alert('Возникла ошибка: ' + xhr.responseCode);
				}
			});
		}
	}
}

function createCombinationOrUpdateStartStopDates(){
    var msg   = $('#level_person_form').serialize();
    $.ajax({
        type: 'POST',
        url: './Main/SaveUpdateStudentCombination.php',
        dataType: 'json',
        data: msg,
        success: function(data){
            $('.level_person_form').hide();
            $("#level_person_form")[0].reset();
            if(data['state'] == 'insert'){alert( "Для "+data['fio_person']+" создан уровень: "+data['level']);}
            $('.back_gray').hide();
        },
        error:  function(xhr, str){
            alert('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}
