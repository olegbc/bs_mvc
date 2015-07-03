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

	$('#intensive_choose').click(function(){
        if($(this).attr('checked') == 'checked'){
            $('#timetable_choose').attr('hidden',true);
            $('#level_choose').attr('hidden',true);
            $("label[for='level_choose']").hide();
            $("label[for='timetable_choose']").hide();
        }
        if($(this).attr('checked') == undefined){
            $('#timetable_choose').val('').attr('hidden',false);
            $('#level_choose').attr('hidden',false);
            $("label[for='level_choose']").show();
            $("label[for='timetable_choose']").show();
        }
    });

	$('#IntensiveCheckIn').click(function(){
        if($(this).attr('checked') == 'checked'){
            if($('.item.timetable_soch'))$('.item.timetable_soch').remove();
            if($('.item.level_start_soch'))$('.item.level_start_soch').remove();
            if($('.item.level_soch'))$('.item.level_soch').remove();
            if($('.item.person_start_soch'))$('.item.person_start_soch').remove();
            if($('.item.person_stop_soch'))$('.item.person_stop_soch').remove();
            $('option[value="choose_teacher"]').prop('selected','selected');
        }
        if($(this).attr('checked') == undefined){
            if($('.item.timetable_soch'))$('.item.timetable_soch').remove();
            if($('.item.level_start_soch'))$('.item.level_start_soch').remove();
            if($('.item.level_soch'))$('.item.level_soch').remove();
            if($('.item.person_start_soch'))$('.item.person_start_soch').remove();
            if($('.item.person_stop_soch'))$('.item.person_stop_soch').remove();
            $('option[value="choose_teacher"]').prop('selected','selected');
        }
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

    if(location.pathname == "/bs_mvc/LevelCalculation"){
        $("#level_start_date" ).datepicker({
            showOn: "button",
            buttonImage: "images/calendar.gif",
            buttonImageOnly: true,
            buttonText: "Select date",
            dateFormat: "yy-mm-dd",
            firstDay: 1
        });
    }

    $('.btn_level_culculation').click(function(){
        var origin = window.location.origin;
        var pathname = "/bs_mvc/LevelCalculation";
        window.location.href = origin + pathname;
	});
	$('.btn_number_of_students').click(function(){
        var origin = window.location.origin;
        var pathname = "/bs_mvc/NumberOfStudents";
        window.location.href = origin + pathname;
	});
	$('.btn_amount_of_money').click(function(){
        var origin = window.location.origin;
        var pathname = "/bs_mvc/AmountOfMoney";
        window.location.href = origin + pathname;
	});
	$('.btn_edit_levels').click(function(){
        var origin = window.location.origin;
        var pathname = "/bs_mvc/LevelCalculation";
        window.location.href = origin + pathname;
	});
	$('.btn_bad_days').click(function(){
        var origin = window.location.origin;
        var pathname = "/bs_mvc/BadDays";
        window.location.href = origin + pathname;
	});
	$('.btn_freeze_table').click(function(){
		var id = getParameterByName('id');
		var path = "/bs_mvc/Freeze?id="+id;
		var direct = location.origin + path;
		location.href = direct;

	});
	$('.btn_spu').click(function(){
		var path = "/bs_mvc/spu";
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

	$('.btn_add').click(function(){
        $('.add_form').show();
        $('.back_gray').show();
        $("#add_form")[0].reset();
	});

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
			if(data['studentExisted']){
				alert("Студент с таким именем и фамилией уже зарегестрирован");
			}else{
				$('.add_form').hide();
                var id = data['id'];
                var name = data['fio'];
                var nameQuoted = '"'+name+'"';
                var dog_num = data['dog_num'];
				$(".main_table").children('tbody').children('tr:last-child ').after("<tr class='tr_"+id+"'><td><input type='text' name='id' value='"+id+"' /></td><td><input type='text' name='fio' onchange='call2(this.value,"+id+",'fio')'><a href='"+window.location.origin+"bs_mvc/person?id="+id+"' target='_self' class='fio_links'>"+name+"</a></td><td><input type='text' name='dog_num' size='5'  onchange='call2(this.value,"+id+",'dog_num')'  value= "+dog_num+"></td><td><p class='lgtt' onclick='fillInNameAndIdInForm("+id+");showDivWrapperOfFormShowGrayBackgroundResetForm();'>Создать уровень</p></td><td><p class='take' onclick='ShowTakeForm("+id+","+nameQuoted+");'>Принять проплату</p></td><td><p class='del' onclick='deleteStudent("+id+","+nameQuoted+")'>Удалить</p></td></tr>");
				alert("Студент зарегестрирован");
				$('.back_gray').hide();
                //var origin = window.location.origin;
                //var pathname = "/bs_mvc/Attendance";
                //window.location.href = origin + pathname;
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

function remove_pers_sochOLD(id,fio,tr){
	var teacher = $(".brick[style*='rgb(0, 0, 255)']").children('[name="teacher_choose"]').val();
    var timetable = $(".brick[style*='rgb(0, 0, 255)']").children('[name="timetable_choose"]').val();
    var level_start = $(".brick[style*='rgb(0, 0, 255)']").children('[name="level_start_choose"]').val();
    if(confirm('Вы действительно хотите удалить '+fio+' из данного сочетания?')){
        if(confirm('Удаление приведет к удалению дат заморозки студента и проплат(вернет деньги на баланс),все равно продолжить?')) {
            var id = id;
            $.ajax({
                type: 'POST',
                url: './Attendance/RemovePersonCombination.php',
                dataType: 'json',
                data: {id: id, teacher: teacher, timetable: timetable, level_start: level_start},
                success: function (data) {
                    $("#tr" + tr).remove();
                },
                error: function (xhr, str) {
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
        }
	}
}

function remove_pers_soch(id,name,tr){
	var teacher = $(".brick[style*='rgb(0, 0, 255)']").children('[name="teacher_choose"]').val();
    var intensive = $(".brick[style*='rgb(0, 0, 255)']").children('[name="intensive"]').val();
    var timetableOrIntensive = intensive;
    if(!intensive){var timetable = $(".brick[style*='rgb(0, 0, 255)']").children('[name="timetable_choose"]').val();timetableOrIntensive = timetable;}
    var level_start = $(".brick[style*='rgb(0, 0, 255)']").children('[name="level_start_choose"]').val();
    var level = $(".brick[style*='rgb(0, 0, 255)']").children('[name="level_choose"]').val();
    if(confirm("Вы действительно хотите удалить студента: "+name+" с сочетания : "+teacher+"/"+timetableOrIntensive+"/"+level_start+" ?")){
        $.ajax({
            type: 'POST',
            async: false,
            url: './Person/AreAnyPayedOrAttenedOrFrozenLessonsExist',
            dataType: 'json',
            data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
            success: function(data) {
                if(data){
                    alert('Для удаления студента с сочетания, удалите все проплаты, посещения либо заморозки студента на данном сочетании.');
                }else{
                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: './Person/RemovePersonOnThisCombinationFromLevelsPersonAndPayedLessons',
                        dataType: 'json',
                        data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
                        success: function(data) {
                            $("#tr" + tr).remove();
                        },
                        error: function(xhr, str){
                            alert('Возникла ошибка: ' + xhr.responseCode);
                        }
                    });
                }
            },
            error: function(xhr, str){
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
            dataType: 'json',
			data: {amount:amount,name:name,id:id},
			success: function(data) {
                if(data){
                    $('.take_form').hide();
                    $("#take_to_bd_form")[0].reset();
                    alert("Принято:" + amount + " грн \nот: " + name + " \n");
                    $('.back_gray').hide();
                }else{
                    alert("Если вносятся копейки - формат ввода 555.55(точка, а не запятая)");
                }
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
function building_blocks(teacher_now,timetable_now,level_start_now, intensive_now){
    var badDayPage = 0;
    if(getParameterByName('badDayPage') == 1){badDayPage = 1;}
    if(location.pathname == "/bs_mvc/BadDays"){
        badDayPage = 1;
    }
		$.ajax({
			type: 'POST',
			async: false,
			url: './Attendance/BuildingBlocks.php',
			dataType: 'json',
			success: function(data){
                //console.log(data);
                var dayNow = new Date();
				for(var i in data) {
                    var teacher = data[i]['teacher'];
                    var timetable = data[i]['timetable'];
                    var level_start = data[i]['sd_1'];
                    var level = data[i]['level'];
                    var teacherQuoted = "'" + teacher + "'";
                    var timetableQuoted = "'" + timetable + "'";
                    var levelStartQuoted = "'" + level_start + "'";
                    var intensive = parseInt(data[i]['intensive']);
                    var archive = parseInt(data[i]['archive']);
                    var paymentExists = data[i]['paymentExists'];
                    var attendanceExists = data[i]['attendanceExists'];

                    if(archive){
                        if(intensive && badDayPage == 0){
                            $('.past_combinations').append('<div class="brickWrapper"><div class="brick"><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_' + i + '" value="' + teacher + '" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_' + i + '" value="false" disabled hidden /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_' + i + '" value="' + level_start + '" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_' + i + '"  value="false" disabled hidden/><input class="brick_input" type="text" name="intensive" id="intensive_choose intensive_choose_' + i + '"  value="intensive" disabled /><input class="brick_input" type="text" name="choose" id="choose" value="" disabled /></div><div class="remove_combination remove_combination_' + i + '"><button class="btn_remove_combination" onclick="remove_combination(' + teacherQuoted + ',false,' + levelStartQuoted + ',true)" disabled hidden>x</button></div></div>');
                        }else{
                            $('.past_combinations').append('<div class="brickWrapper"><div class="brick"><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_' + i + '" value="' + teacher + '" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_' + i + '" value="' + timetable + '" disabled /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_' + i + '" value="' + level_start + '" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_' + i + '"  value="' + level + '" disabled /></div><div class="remove_combination remove_combination_' + i + '"><button class="btn_remove_combination" onclick="remove_combination(' + teacherQuoted + ',' + timetableQuoted + ',' + levelStartQuoted + ')" disabled hidden>x</button></div></div>');
                        }
                    }
                    else{
                        if(dayNow.getTime() < new Date(level_start.replace(/-/g, ",")).getTime()){
                            if(intensive && badDayPage == 0) {
                                $('.future_combinations').append('<div class="brickWrapper"><div class="brick"><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_' + i + '" value="' + teacher + '" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_' + i + '" value="false" disabled hidden /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_' + i + '" value="' + level_start + '" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_' + i + '"  value="false" disabled hidden/><input class="brick_input" type="text" name="intensive" id="intensive_choose intensive_choose_' + i + '"  value="intensive" disabled /><input class="brick_input" type="text" name="choose" id="choose" value="" disabled /></div><div class="remove_combination remove_combination_' + i + '"><button class="btn_remove_combination" onclick="remove_combination(' + teacherQuoted + ',false,' + levelStartQuoted + ',true)" disabled hidden>x</button></div></div>');
                            }else{
                                $('.future_combinations').append('<div class="brickWrapper"><div class="brick"><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_' + i + '" value="' + teacher + '" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_' + i + '" value="' + timetable + '" disabled /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_' + i + '" value="' + level_start + '" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_' + i + '"  value="' + level + '" disabled /></div><div class="remove_combination remove_combination_' + i + '"><button class="btn_remove_combination" onclick="remove_combination(' + teacherQuoted + ',' + timetableQuoted + ',' + levelStartQuoted + ')" disabled hidden >x</button></div></div>');
                            }
                        }else{
                            if(intensive && badDayPage == 0){
                                $('.peresent_combinations').append('<div class="brickWrapper"><div class="brick"><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_' + i + '" value="' + teacher + '" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_' + i + '" value="false" disabled hidden /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_' + i + '" value="' + level_start + '" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_' + i + '"  value="' + level + '" disabled hidden /><input class="brick_input" type="text" name="intensive" id="intensive_choose intensive_choose_' + i + '"  value="intensive" disabled /><input class="brick_input" type="text" name="choose" id="choose" value="" disabled /></div><div class="remove_combination remove_combination_' + i + '"><button class="btn_remove_combination" onclick="remove_combination(' + teacherQuoted + ',false,' + levelStartQuoted + ',true)" disabled hidden>x</button></div></div>');
                            }else{
                                $('.peresent_combinations').append('<div class="brickWrapper"><div class="brick"><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_' + i + '" value="' + teacher + '" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_' + i + '" value="' + timetable + '" disabled /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_' + i + '" value="' + level_start + '" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_' + i + '"  value="' + level + '" disabled /></div><div class="remove_combination remove_combination_' + i + '"><button class="btn_remove_combination" onclick="remove_combination(' + teacherQuoted + ',' + timetableQuoted + ',' + levelStartQuoted + ')" disabled hidden >x</button></div></div>');
                            }
                        }
                    }
                    if(!paymentExists && !attendanceExists && !archive) {
                        if(intensive){
                            $("input[value*='" + teacher + "']").siblings("input[value*='" + level_start + "']").siblings("input[value*='intensive']").parents('.brickWrapper').children('.remove_combination').children('.btn_remove_combination').prop({'disabled':false,'hidden':false});
                        }else{
                            $("input[value*='" + teacher + "']").siblings("input[value*='" + timetable + "']").siblings("input[value*='" + level_start + "']").parents('.brickWrapper').children('.remove_combination').children('.btn_remove_combination').prop({'disabled':false,'hidden':false});
                        }
                    }
                }
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});

		//$('.combination_all .past_combinations .brick').click(function(){
		//	$('.brick').css('borderColor','#000');
		//	var teacher_choose = $(this).children("input[name='teacher_choose']").val();
		//	var timetable_choose = $(this).children("input[name='timetable_choose']").val();
		//	var level_start_choose = $(this).children("input[name='level_start_choose']").val();
		//	$('#lgtt_form').children("input[name='teacher_choose']").val(teacher_choose);
		//	$('#lgtt_form').children("input[name='timetable_choose']").val(timetable_choose);
		//	$('#lgtt_form').children("input[name='level_start_choose']").val(level_start_choose);
		//	lgtt_match_fn(teacher_choose,timetable_choose,level_start_choose);
		//	$(this).css('borderColor','rgb(0, 0, 255)');
		//	$('.change_start_date').prop('disabled',true);
		//});
		//$('.combination_all .peresent_combinations .brick').click(function(){
		$('.combination_all .brick').not('.combination_all .past_combinations .brick').not('.remove_combination .btn_remove_combination').click(function(){
            $('.brick').css('borderColor', '#000');
            var teacher_choose = $(this).children("input[name='teacher_choose']").val();
            var level_start_choose = $(this).children("input[name='level_start_choose']").val();
            var timetable_choose = false;
            var intensive = false;
            $('#lgtt_form').children("input[name='teacher_choose']").val(teacher_choose);
            $('#lgtt_form').children("input[name='level_start_choose']").val(level_start_choose);
            if($(this).children("input[name='intensive']").val() == 'intensive'){
                var intensive = true;
                $('#lgtt_form').children("input[name='intensive']").val(intensive);
            }else{
                var timetable_choose = $(this).children("input[name='timetable_choose']").val();
                $('#lgtt_form').children("input[name='timetable_choose']").val(timetable_choose);
            }
            //console.log(teacher_choose, timetable_choose, level_start_choose,intensive);
            lgtt_match_fn(teacher_choose, timetable_choose, level_start_choose,intensive);
            $(this).css('borderColor', 'rgb(0, 0, 255)');
		});
		$('.combination_all .past_combinations .brick').click(function(){
            $('.brick').css('borderColor', '#000');
            var teacher_choose = $(this).children("input[name='teacher_choose']").val();
            var level_start_choose = $(this).children("input[name='level_start_choose']").val();
            var timetable_choose = false;
            var intensive = false;
            $('#lgtt_form').children("input[name='teacher_choose']").val(teacher_choose);
            $('#lgtt_form').children("input[name='level_start_choose']").val(level_start_choose);
            if($(this).children("input[name='intensive']").val() == 'intensive'){
                var intensive = true;
                $('#lgtt_form').children("input[name='intensive']").val(intensive);
            }else{
                var timetable_choose = $(this).children("input[name='timetable_choose']").val();
                $('#lgtt_form').children("input[name='timetable_choose']").val(timetable_choose);
            }
            //console.log(teacher_choose, timetable_choose, level_start_choose,intensive);
            lgtt_match_fn(teacher_choose, timetable_choose, level_start_choose,intensive);
            $('.change_start_date').prop('disabled', true);
            $('.btn_send_to_archive').prop('disabled', true);
            $(this).css('borderColor', 'rgb(0, 0, 255)');
		});
		//$('.combination_all .future_combinations .brick').click(function(){
		//	$('.brick').css('borderColor','#000');
		//	var teacher_choose = $(this).children("input[name='teacher_choose']").val();
		//	var timetable_choose = $(this).children("input[name='timetable_choose']").val();
		//	var level_start_choose = $(this).children("input[name='level_start_choose']").val();
		//	$('#lgtt_form').children("input[name='teacher_choose']").val(teacher_choose);
		//	$('#lgtt_form').children("input[name='timetable_choose']").val(timetable_choose);
		//	$('#lgtt_form').children("input[name='level_start_choose']").val(level_start_choose);
		//	lgtt_match_fn(teacher_choose,timetable_choose,level_start_choose);
		//	$(this).css('borderColor','rgb(0, 0, 255)');
		//	//$('.btn_send_to_archive').prop('disabled',true);
		//});

		$('.brick').each(function(){
            if(intensive_now){
                if($(this).children("input[name='teacher_choose']").val()==teacher_now && $(this).children("input[name='intensive']").val()=="intensive" && $(this).children("input[name='level_start_choose']").val()==level_start_now){
                    $(this).css('borderColor','rgb(0, 0, 255)');
                }
            }else {
                if ($(this).children("input[name='teacher_choose']").val() == teacher_now && $(this).children("input[name='timetable_choose']").val() == timetable_now && $(this).children("input[name='level_start_choose']").val() == level_start_now) {
                    $(this).css('borderColor', 'rgb(0, 0, 255)');
                }
            }
		});
}

function lgtt_match_fn(teacher,timetable,level_start,intensive){
    //console.log(teacher,timetable,level_start,intensive);
    //return;
    if(intensive == 'false'){
        intensive = false;
    }
    if(intensive == 'true'){
        intensive = true;
    }
    if(intensive == '1'){
        intensive = true;
    }
    if(intensive == '0'){
        intensive = false;
    }
    if(intensive == 'undefined'){
        intensive = false;
    }

    //console.log(teacher, timetable, level_start, intensive);

    var msg;
    if(!intensive && teacher!=undefined && timetable!=undefined && level_start!=undefined){
        msg = {teacher:teacher,timetable:timetable,level_start:level_start};
    }else if(intensive && teacher!=undefined && level_start!=undefined){
        msg = {teacher:teacher,level_start:level_start,intensive:intensive};
    }else{
        msg = $('#lgtt_form').serialize();
    }

    //console.log(msg);

    var level_date = [] ;
    var levelStop = '';

        //----- Построение шапки таблицы ;
	$('#attendance_table').empty();
	$('#attendance_table').html('<tbody><tr id="th_line"><th class="attendance_name_th" id="name_th"><div id="attendance_table_name" >Имя</div></th></tr></tbody>');
	//console.log(msg);
    $.ajax({
        type: 'POST',
        async: false,
        url: './Attendance/CombinationDatesFittedToTimetable.php',
        dataType: 'json',
        data: msg,
        success: function(data) {
            //console.log(data);
            if(intensive){
                levelStop = data['sd_10'];
                for (var g = 10; g > 0; g--) {
                    $('#name_th').after("<th class='attendance_th'><div class='rotateText'>" + data['sd_' + g] + "</div></th>");
                    level_date[g - 1] = data['sd_' + g];
                }
            }else {
                levelStop = data['sd_21'];
                for (var g = 21; g > 0; g--) {
                    $('#name_th').after("<th class='attendance_th'><div class='rotateText'>" + data['sd_' + g] + "</div></th>");
                    level_date[g - 1] = data['sd_' + g];
                }
            }
        },
        error: function(xhr, str){
            alert('Возникла ошибка: ' + xhr.responseCode);
        }
    });
    //return;
			//----- /Построение шапки таблицы

    //return;

			//----- Фамилии даты
			$.ajax({
				type: 'POST',
				async: false,
				url: './Attendance/StudentsInformation',
				dataType: 'json',
				data: msg,
				success: function(data) {
                    //console.log(data);
                    //return;

                    var teacherQuoted = "'" + teacher + "'";
                    var timetableQuoted = "'" + timetable + "'";
                    var level_startQuoted = "'" + level_start + "'";
                    var intensiveQuoted = "'" + intensive + "'";

                    $('.btn_arrangment').remove();
                    $('.btn_send_to_archive').remove();

                        $('.att_table').before('<div class="btn_arrangment"><input type="text" name="new_level_start" id="new_level_start"/><button class="change_start_date" onclick="change_start_date(' + teacherQuoted + ',' + timetableQuoted + ',' + level_startQuoted + ',' + intensiveQuoted+ ')">change_start_date</button><cite>Изменить дату старта возможно, если нет посещений или заморозок</cite></div><div class="send_to_archive_div"><button class="btn_send_to_archive" onclick="send_to_archive(' + teacherQuoted + ',' + timetableQuoted + ',' + level_startQuoted + ',' + intensiveQuoted+ ')" disabled>Отправить в архив</button></div>');


                    if(data){
                    var archive = data['archive'][0];
                    //console.log(data['status']);
                    //return;
                    var payed_all = 1;
                        var idArr = data['id'];
                        for (var i = (data['name'].length - 1); i >= 0; i--) {
                            $('#th_line').after("<tr id='tr" + i + "'></tr>");
                            idQuoted = "'" + data['id'][i] + "'";
                            nameQuoted = "'" + data['name'][i] + "'";
                            iQuoted = "'" + i + "'";
                            if (data['numPayed'][i] != 0 && data['numReserved'][i] !=0 && data['numPayed'][i] == data['numReserved'][i]) {
                                $('#tr' + i).html('<td  class="studentTitle" id="td' + i + '_' + 0 + '"><div class="remove_pers_soch"><button  onclick="remove_pers_soch(' + idQuoted + ',' + nameQuoted + ',' + iQuoted + ',' + intensiveQuoted +')">x</button></div><div class="pay_check pay_check_green">' + data['numPayed'][i] + '/' + data['numReserved'][i] + '</div><div class="fio_pers_soch"><a href='+window.location.origin+'/bs_mvc/person?id='+data['id'][i]+' target="_self" class="fio_links">' + data['name'][i] + '</a></div></td>');
                                //payed_all = 1;
                            } else {
                                $('#tr' + i).html('<td  class="studentTitle"  id="td' + i + '_' + 0 + '"><div class="remove_pers_soch"><button  onclick="remove_pers_soch(' + idQuoted + ',' + nameQuoted + ',' + iQuoted + ',' + intensiveQuoted +')">x</button></div><div class="pay_check">' + data['numPayed'][i] + '/' + data['numReserved'][i] + '</div><div class="fio_pers_soch"><a href='+window.location.origin+'/bs_mvc/person?id='+data['id'][i]+' target="_self" class="fio_links">' + data['name'][i] + '</a></div></td>');

                                payed_all = 0;
                            }
                            if (status == -1) {
                                $('.remove_pers_soch button').remove();
                            }

                            for (var q = 0; q < data['dates'].length; q++) {
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
                                } else {
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
                                for (var g in data['frozenDates'][i]) {
                                    if (data['dates'][q] == data['frozenDates'][i][g]) {
                                        color_freeze = 1;
                                    }
                                }
                                if (data['dates'][q] == data['personStart'][i]) {
                                    start_date = 1;
                                }
                                if (data['dates'][q] == data['personStop'][i]) {
                                    stop_date = 1;
                                }
                                if (color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 1) {
                                    $('#td' + i + '_' + q).after("<td id=td" + i + "_" + (parseInt(q) + 1) + " class=' attendence_mark before_person_start_mark color'>" + match + "</td>");
                                    //	before_person_start_mark
                                } else if (color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0) {
                                    $('#td' + i + '_' + q).after("<td  id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark color'>" + match + "</td>");
                                } else if (color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0 && after_person_stop == 1) {
                                    $('#td' + i + '_' + q).after("<td  id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark before_person_start_mark color'>" + match + "</td>");
                                    //	before_person_start_mark
                                } else if (color == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0) {
                                    $('#td' + i + '_' + q).after("<td  id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark color'>" + match + "</td>");
                                } else if (color == 1 && start_date == 1) {
                                    $('#td' + i + '_' + q).after("<td  bordercolor='#0000FF' id='td" + i + "_" + (q + 1) + "' class=' attendence_mark person_start_mark color'>" + match + "</td>");
                                }
                                // color_freeze
                                else if (color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 1) {
                                    $('#td' + i + '_' + q).after("<td id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark before_person_start_mark color_freeze'>" + match + "</td>");
                                    //	before_person_start_mark
                                } else if (color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0) {
                                    $('#td' + i + '_' + q).after("<td  id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark color_freeze'>" + match + "</td>");
                                } else if (color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0 && after_person_stop == 1) {
                                    $('#td' + i + '_' + q).after("<td  id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark before_person_start_mark color_freeze'>" + match + "</td>");
                                    //	before_person_start_mark
                                } else if (color_freeze == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0) {
                                    $('#td' + i + '_' + q).after("<td  id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark color_freeze'>" + match + "</td>");
                                } else if (color_freeze == 1 && start_date == 1) {
                                    $('#td' + i + '_' + q).after("<td  bordercolor='#0000FF' id='td" + i + "_" + (q + 1) + "' class=' attendence_mark person_start_mark color_freeze'>" + match + "</td>");
                                }
                                // /color_freeze

                                else if (color == 0 && start_date == 1) {
                                    $('#td' + i + '_' + q).after("<td id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark person_start_mark'>" + match + "</td>");
                                } else if (color == 1 && stop_date == 1) {
                                    // console.log(color, stop_date);
                                    $('#td' + i + '_' + q).after("<td  bordercolor='#0000FF' id='td" + i + "_" + (q + 1) + "' class=' attendence_mark person_stop_mark color'>" + match + "</td>");
                                }
                                // color_freeze
                                else if (color_freeze == 1 && stop_date == 1) {
                                    // console.log(color, stop_date);
                                    $('#td' + i + '_' + q).after("<td  bordercolor='#0000FF' id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark person_stop_mark color_freeze'>" + match + "</td>");
                                }
                                // /color_freeze
                                else if (color == 0 && stop_date == 1) {
                                    $('#td' + i + '_' + q).after("<td id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark person_stop_mark'>" + match + "</td>");
                                } else if (color == 0 && before_person_start == 1) {
                                    $('#td' + i + '_' + q).after("<td id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark before_person_start_mark'>" + match + "</td>");
                                    //	before_person_start_mark
                                } else if (color == 0 && after_person_stop == 0 && before_person_start == 0) {
                                    $('#td' + i + '_' + q).after("<td id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark'>" + match + "</td>");
                                } else if (color == 0 && after_person_stop == 1) {
                                    $('#td' + i + '_' + q).after("<td id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark before_person_start_mark'>" + match + "</td>");
                                    //	before_person_start_mark
                                } else if (color == 0 && after_person_stop == 0) {
                                    $('#td' + i + '_' + q).after("<td id='td" + i + "_" + (parseInt(q) + 1) + "' class=' attendence_mark'>" + match + "</td>");
                                }
                            }
                        }

                    //---- действия при клике на ячейку даты, находиться здесь так как таблицеа формируется после формирования страницы
                    $('.attendence_mark').not('.before_person_start_mark').not('.color_freeze').click(function () {
                        var thisId = $(this).attr('id');
                        var thisIdNumId = thisId.split("_");
                        var numId = thisIdNumId[0].replace("td", "");
                        var attenedDate = $(this).text();
                        if ($(this).hasClass('color')) {
                            if (confirm('Вы действительно хотите удалить ' + $(this).text() + ' дату посещения')) {
                                $.ajax({
                                    type: 'POST',
                                    url: './Attendance/DeleteAttenedDateFromAttendanceTable',
                                    data: {
                                        id: idArr[numId],
                                        attenedDate: attenedDate,
                                        teacher: teacher,
                                        timetable: timetable,
                                        level_start: level_start,
                                        intensive: intensive
                                    },
                                    success: function (data) {
                                        //console.log(data);
                                        $('.brickWrapper').remove();
                                        building_blocks(teacher, timetable, level_start, intensive);
                                        lgtt_match_fn(teacher, timetable, level_start, intensive);
                                    },
                                    error: function (xhr, str) {
                                        alert('Возникла ошибка: ' + xhr.responseCode);
                                    }
                                });
                            }
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: './Attendance/InsertAttenedDateToAttendanceTable',
                                data: {
                                    id: idArr[numId],
                                    attenedDate: attenedDate,
                                    teacher: teacher,
                                    timetable: timetable,
                                    level_start: level_start,
                                    intensive: intensive
                                },
                                success: function (data) {
                                    //console.log(data);
                                    $('.brickWrapper').remove();
                                    building_blocks(teacher, timetable, level_start, intensive);
                                    lgtt_match_fn(teacher, timetable, level_start, intensive);
                                },
                                error: function (xhr, str) {
                                    alert('Возникла ошибка: ' + xhr.responseCode);
                                }
                            });
                        }
                    });

                        var dayNow = new Date();
                        $('.btn_send_to_archive').prop('disabled', true);
                        if (payed_all == 1 && dayNow.getTime() > new Date(levelStop.replace(/-/g, ",")).getTime()){
                            $('.btn_send_to_archive').prop('disabled', false);
                        }
                    }else{
                            $('.btn_send_to_archive').prop('disabled', true)
                        }

				},
				error:  function(xhr, str){
					alert('Возникла ошибка ajax: ' + xhr.responseCode);
				}
			});

    if (location.pathname == "/bs_mvc/Attendance") {
        $("#new_level_start").datepicker({
            showOn: "button",
            buttonImage: "images/calendar.gif",
            buttonImageOnly: true,
            buttonText: "Select date",
            dateFormat: "yy-mm-dd",
            firstDay: 1,
            gotoCurrent: true,
            defaultDate: level_start
        });
    }

    if ($('*').hasClass('color') || $('*').hasClass('color_freeze')) {
        $('.change_start_date').prop('disabled', true);
    }
}

function level_culc_fn(){
	//var msg = $('#level_culc').serialize();
    var intensive = false;
    var timetable = false;
    var level = false;
    if($('#intensive_choose').attr('checked') == 'checked'){intensive = true;}
    if(intensive) {
        if ($('#level_culc input#teacher_choose')[0]['value'] != "" &&
            $('#level_culc input#level_start_date')[0]['value'] != "") {
            var teacher = $('#level_culc input#teacher_choose')[0]['value'];
            var level_start = $('#level_culc input#level_start_date')[0]['value'];
            $.ajax({
                type: 'POST',
                url: './LevelCalculation/CalculateLevelDates',
                dataType: 'json',
                data: { teacher: teacher, timetable: timetable, level_start: level_start, level: level, intensive: intensive},
                success: function (data) {
                    if (data['wrongTimetable']) {
                        alert("Дата старта уровня не соответствует расписанию");
                    } else {
                        alert("Сочетание создано");
                    }
                },
                error: function (xhr, str) {
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
        } else {
            alert('Заполните все обязательные поля')
        }
    }else {
        if ($('#level_culc input#teacher_choose')[0]['value'] != "" &&
            $('#level_culc input#timetable_choose')[0]['value'] != "" &&
            $('#level_culc input#level_start_date')[0]['value'] != "" &&
            $('#level_culc input#level_choose')[0]['value'] != "") {
            var teacher = $('#level_culc input#teacher_choose')[0]['value'];
            timetable = $('#level_culc input#timetable_choose')[0]['value'];
            var level_start = $('#level_culc input#level_start_date')[0]['value'];
            level = $('#level_culc input#level_choose')[0]['value'];
            $.ajax({
                type: 'POST',
                url: './LevelCalculation/CalculateLevelDates',
                dataType: 'json',
                data: { teacher: teacher, timetable: timetable, level_start: level_start, level: level, intensive: intensive},
                success: function (data) {
                    //console.log(data);
                    //return;
                    if (data['wrongTimetable']) {
                        alert("Дата старта уровня не соответствует расписанию");
                    } else {
                        var origin = window.location.origin;
                        var pathname = "/bs_mvc/BadDays?badDayPage=1";
                        window.location.href = origin + pathname;
                    }
                },
                error: function (xhr, str) {
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
        } else {
            alert('Заполните все обязательные поля')
        }
    }
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
	//var allTeachers= [];
	//var colorArr = [];

	$.ajax({
		type:'POST',
		url: './NumberOfStudents/AllTeachers',
		dataType: 'json',
		success: function(data){
            //console.log(data);
            if(data){
                var allTeachers = data;
                var colorArr = [];
                for (var i in data) {
                    colorArr[i] = "#" + Math.random().toString(16).slice(2, 8);
                }
                $.ajax({
                    type: 'POST',
                    url: './NumberOfStudents/Datepicker',
                    dataType: 'json',
                    data: {from: new_msg[0], to: new_msg[1], teacher: teacher, allTeachers: allTeachers},
                    success: function (data) {
                        //console.log(data);
                        diag(data, allTeachers, colorArr);
                    },
                    error: function (xhr, str) {
                        alert('Возникла ошибка: ' + xhr.responseCode);
                    }
                });
            }else{
                alert('Создайте хотя бы одно сочетание учитель-расписание-дата старта');
            }
		},
		error:  function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});


	//$.ajax({
	//	type:'POST',
	//	url: './NumberOfStudents/Datepicker',
     //   async: 'false',
	//	dataType: 'json',
	//	data: {from:new_msg[0],to:new_msg[1],teacher:teacher,allTeachers:allTeachers},
	//	success: function(data){
	//		console.log(data);
     //       return;
	//		diag(data,allTeachers,colorArr);
	//	},
	//	error:  function(xhr, str){
	//		alert('Возникла ошибка: ' + xhr.responseCode);
	//	}
	//});
}

//---   DIAG
var f = true;
function diag(e,all_teachers,color_arr){
    console.log(e);
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
		url: './AmountOfMoney/AmountOfMoney',
		dataType: 'json',
		data: {from:new_msg[0],to:new_msg[1]},
		success: function(data){
			console.log(data);
            //return;
			//diag_money(data);
            SVGraph(data);
            //return;
			//$("#graphHolder").remove();
			//$("#Graph_money").remove();
			//$('<div id="Graph_money"></div>').prependTo($("#stackedGraph_wrapper"));
			//$("#Graph_money").jqBarGraph({data: data,width: 1500,animate: false});
		},
		error:  function(xhr, str){
			alert('Возникла ошибка !!!: ' + xhr.responseCode);
		}
	});
}

function spu(){
	var msg = $('#widgetField span').get(0).innerHTML;
	var new_msg = msg.split(" ÷ ");

    $.ajax({
		type:'POST',
		url: './SPU/SPU',
		dataType: 'json',
		data: {from:new_msg[0],to:new_msg[1]},
		success: function(data){
            console.log(data);
            if(data['noFittedCombinationInRegion'] == 1){
                alert('Ни один студент не зарезервирован в заданный период вермени.')
            }else {
                SpuSumGraph(data);
                SpuByTeacherGraph(data);
            }
		},
		error:  function(xhr, str){
			alert('Возникла ошибка !!!: ' + xhr.responseCode);
		}
	});
}

function add_discount(teacher,timetable,level_start,i,id,intensive){
    if(intensive == 'false'){
        intensive = false;
    }
    if(intensive == 'true'){
        intensive = true;
    }
    if(intensive == '1'){
        intensive = true;
    }
    if(intensive == '0'){
        intensive = false;
    }
    if(intensive == 'undefined'){
        intensive = false;
    }
    var discountValue = $("#discount_add_"+i).val();
    if(discountValue==""){discountValue=0;}
    $.ajax({
        type: 'POST',
        async: false,
        url: './Person/AddDiscount',
        dataType: 'json',
        data: {teacher:teacher,timetable:timetable,level_start:level_start,discountValue:discountValue,id:id,intensive:intensive},
        success: function(data){
            //console.log(data);
            //return;
            get_person_discount(id,teacher,timetable,level_start,i,intensive);
        },
        error: function(xhr, str){
            alert('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}

function get_person_discount(id,teacher,timetable,level_start,i,intensive){
    if(intensive == 'false'){
        intensive = false;
    }
    if(intensive == 'true'){
        intensive = true;
    }
    if(intensive == '1'){
        intensive = true;
    }
    if(intensive == '0'){
        intensive = false;
    }
    if(intensive == 'undefined'){
        intensive = false;
    }
    $.ajax({
        type:'POST',
        async:false,
        url: "./Person/PersonDiscountReason",
        dataType:'json',
        data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
        success: function(data) {
            //console.log(data);
            if('discount' in data && data['discount'] != 0 && data['discount'] != ""){$('#discount_set_'+i).val(data['discount']);}
            if(data['discount'] == null || data['discount'] == 0 || data['discount'] == ""){$('#discount_set_'+i).val('Нет скидки');}
            if('reason' in data && data['reason'] !=""){$('#reason_set_'+i).val(data['reason']);}
            if(data['reason'] == null){$('#reason_set_'+i).val('Нет причины');}
        },
        error: function(xhr, str){
            alert('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}

function add_person_reason(id,teacher,timetable,level_start,i,intensive){
    if(intensive == 'false'){
        intensive = false;
    }
    if(intensive == 'true'){
        intensive = true;
    }
    if(intensive == '1'){
        intensive = true;
    }
    if(intensive == '0'){
        intensive = false;
    }
    if(intensive == 'undefined'){
        intensive = false;
    }
    var reason = $("#reason_add_"+i).val();
    $.ajax({
        type:'POST',
        async:false,
        url: "./Person/AddDiscountReason",
        dataType:'json',
        data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start,i:i,reason:reason,intensive:intensive},
        success: function(data) {
            console.log(data);
            if(reason!=""){$('#reason_set_'+i).val(reason);}else{$('#reason_set_'+i).val('Нет причины');}
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
            //console.log(data);
            //return;
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
                    var intensive = parseInt(data['allCombinationsOfThisPerson'][i]['intensive']);
                    var level_date = [];
                    var person_start;
                    var person_stop;
                    var numberOfStartLesson;
                    if(intensive){
                        $('#attendance_table').append("<div class='xxx'><p id='stack_" + i + "'>" + teacher + "/" + level_start + "/intensive</p></div>");
                    }else{
                        $('#attendance_table').append("<div class='xxx'><p id='stack_" + i + "'>" + teacher + "/" + timetable + "/" + level_start + "/" + level + "</p></div>");
                    }

                    $('#attendance_table').append("<div class='main_form  main_form_" + i + "'><table width='50%'' class='attendance_table default_table' id='attendance_table_" + i + "'></table></div>")
                    //----- Построение шапки таблицы ;
                    $("#attendance_table_" + i).empty();

                    $("#attendance_table_" + i).html("<tbody><tr id='th_line_" + i + "'><th class='attendance_name_th' id='name_th_" + i + "'><div id='attendance_table_name_" + i + "' class='attendance_table_name'>Имя</div></th></tr></tbody>");
                    $("#attendance_table_" + i).after("<div class='discountChanges'><lebel for='discount_set_" + i + "'>текущая скидка на сочетание</lebel><input type='text' name='discount_set_" + i + "' id='discount_set_" + i + "' class='reason_set' style='border:0px' readonly /><br /><lebel for='discount_add_" + i + "'>изменить текущую скидку на</lebel><br /><input type='text' name='discount_add_" + i + "' id='discount_add_" + i + "'/><button id='btn_add_discount_" + i + "' onclick=add_discount('" + teacher + "','" + timetable + "','" + level_start + "','" + i + "','" + id + "','" + intensive + "')>изменить</button><i class='discountChangeWarning'>Возможно изменить только, если нет проплат за данное сочетания</i></div>");
                    $("#attendance_table_" + i).after("<div class='discountChanges'><lebel for='reason_set_" + i + "'>текущая причина скидки</lebel><input type='text' name='reason_set_" + i + "' id='reason_set_" + i + "' class='reason_set' style='border:0px' readonly /><br /><lebel for='reason_add_" + i + "'>изменить текущую причину на</lebel><br /><input type='text' name='reason_add_" + i + "' id='reason_add_" + i + "'/><button onclick=add_person_reason('" + id + "','" + teacher + "','" + timetable + "','" + level_start + "','" + i + "','" + intensive + "')>изменить</button></div>");
                    get_person_discount(id, teacher, timetable, level_start, i);

                    //console.log(intensive);
					$.ajax({
						type: 'POST',
						async: false,
                        url: './Person/CombinationDatesFittedToTimetable',
						dataType: 'json',
						data: {teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
						success: function(data){
                            //console.log(data);
                            //return;
                            var numberOfStartLesson = 21;
                            if(intensive){numberOfStartLesson = 10;}
                            for (var g = numberOfStartLesson; g > 0; g--) {
                                $('#name_th_' + i).after("<th class='attendance_th'><div class='rotateText'>" + data['sd_' + g] + "</div></th>");
                                level_date[g - 1] = data['sd_' + g];
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
						data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
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
                            var numberOfLessons = 21;
                            if(intensive){numberOfLessons = 10;}
                            for (var q = 0; q < numberOfLessons; q++) {
                                    color = 0;
                                    color_freeze = 0;
                                    start_date = 0;
                                    stop_date = 0;
                                    before_person_start = 1;
                                    after_person_stop = 0;
                                    for (var c in data['datesOfVisit']) {
                                        if (level_date[q] == data['datesOfVisit'][c]) {
                                            match = data['datesOfVisit'][c];
                                            color = 1;
                                        } else {
                                            match = level_date[q];
                                        }	// ДАТЫ ПОСЕЩЕНИЙ (совпадение с датами сочетания)
                                        if (level_date[q] < data['person_start']) {
                                            before_person_start = 1;
                                        } else if (level_date[q] >= data['person_start']) {
                                            before_person_start = 0;
                                        }	// ОПРЕДИЛЕНИЕ ДО/ПОСЛЕ ДАТЫ СТАРТА ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
                                        if (level_date[q] > data['person_stop']) {
                                            after_person_stop = 1;
                                        } else if (level_date[q] <= data['person_stop']) {
                                            after_person_stop = 0;
                                        }	// ОПРЕДИЛЕНИЕ ДО/ПОСЛЕ ДАТЫ СТОП ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
                                    }
                                    for (var g in freeze_dates_arr[i]) {
                                        if (level_date[q] == freeze_dates_arr[i][g]) {
                                            color_freeze = 1;
                                        }
                                    }

                                    if (level_date[q] == data['person_start']) {
                                        start_date = 1;
                                    }
                                    if (level_date[q] == data['person_stop']) {
                                        stop_date = 1;
                                    }	// ОПРЕДИЛЕНИЕ ДАТЫ СТАРТ/СТОП ПЕРСОНЫ НА ДАННОМ СОЧЕТАНИИ
                                    if (color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 1) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td id='td" + y + "_" + (w + 1) + "' class='payment_mark  before_person_start_mark color'>" + match + "</td>");
                                        //	before_person_start_mark
                                    } else if (color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  id='td" + y + "_" + (w + 1) + "' class='payment_mark  color'>" + match + "</td>");
                                    } else if (color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0 && after_person_stop == 1) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  id='td" + y + "_" + (w + 1) + "' class='payment_mark  before_person_start_mark color'>" + match + "</td>");
                                        //	before_person_start_mark
                                    } else if (color == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  id='td" + y + "_" + (w + 1) + "' class='payment_mark  color'>" + match + "</td>");
                                    } else if (color == 1 && start_date == 1) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  bordercolor='#0000FF' id='td" + y + "_" + (w + 1) + "' class='payment_mark  person_start_mark color'>" + match + "</td>");
                                    }
                                    // color_freeze
                                    else if (color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 1) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td id='td" + y + "_" + (w + 1) + "' class='payment_mark  before_person_start_mark color_freeze'>" + match + "</td>");
                                        //	before_person_start_mark
                                    } else if (color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  id='td" + y + "_" + (w + 1) + "' class='payment_mark  color_freeze'>" + match + "</td>");
                                    } else if (color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0 && after_person_stop == 1) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  id='td" + y + "_" + (w + 1) + "' class='payment_mark  before_person_start_mark color_freeze'>" + match + "</td>");
                                        //	before_person_start_mark
                                    } else if (color_freeze == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  id='td" + y + "_" + (w + 1) + "' class='payment_mark  color_freeze'>" + match + "</td>");
                                    } else if (color_freeze == 1 && start_date == 1) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  bordercolor='#0000FF' id='td" + y + "_" + (w + 1) + "' class='payment_mark  person_start_mark color_freeze'>" + match + "</td>");
                                    }
                                    // /color_freeze

                                    else if (color == 0 && start_date == 1) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td id='td" + y + "_" + (w + 1) + "' class='payment_mark  person_start_mark'>" + match + "</td>");
                                    } else if (color == 1 && stop_date == 1) {
                                        // console.log(color, stop_date);
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  bordercolor='#0000FF' id='td" + y + "_" + (w + 1) + "' class='payment_mark  person_stop_mark color'>" + match + "</td>");
                                    }
                                    // color_freeze
                                    else if (color_freeze == 1 && stop_date == 1) {
                                        // console.log(color, stop_date);
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  bordercolor='#0000FF' id='td" + y + "_" + (w + 1) + "' class='payment_mark  person_stop_mark color_freeze'>" + match + "</td>");
                                    }
                                    // /color_freeze
                                    else if (color == 0 && stop_date == 1) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td id='td" + y + "_" + (w + 1) + "' class='payment_mark  person_stop_mark'>" + match + "</td>");
                                    } else if (color == 0 && before_person_start == 1) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td id='td" + y + "_" + (w + 1) + "' class='payment_mark  before_person_start_mark'>" + match + "</td>");
                                        //	before_person_start_mark
                                    } else if (color == 0 && after_person_stop == 0 && before_person_start == 0) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td id='td" + y + "_" + (w + 1) + "' class='payment_mark '>" + match + "</td>");
                                    } else if (color == 0 && after_person_stop == 1) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td id='td" + y + "_" + (w + 1) + "' class='payment_mark  before_person_start_mark'>" + match + "</td>");
                                        //	before_person_start_mark
                                    } else if (color == 0 && after_person_stop == 0) {
                                        $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td id='td" + y + "_" + (w + 1) + "' class='payment_mark '>" + match + "</td>");
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
                    checkForPayedLessonToManipulateWithBtnAddDiscountAbilities();

				}
				function markAllPayedDates(){
					var person_start_arr =[];
                    var nameQuoted= "'"+name+"'";
                    var idQuoted= "'"+id+"'";
                    var teacherQuoted = "'"+teacher+"'";
                    var timetableQuoted = "'"+timetable+"'";
                    var level_startQuoted = "'"+level_start+"'";
                    var levelQuoted = "'"+level+"'";
                    var intensive = data['allCombinationsOfThisPerson'][i]['intensive'];
                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: './Person/NumPayedNumReservedCostOfOneLessonWithDiscount',
                        dataType: 'json',
                        data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
                        success: function(data) {
                            //console.log(data);
                            var cell_now = numberOfStartLesson;
                            $('#stack_'+i).after("<p>Оплачено "+data['num_payed']+" ("+(data['num_payed']*data['CostOfOneLessonWithDiscount']).toFixed(2)+") из "+data['num_reserved']+" ("+(data['num_reserved']*data['CostOfOneLessonWithDiscount']).toFixed(2)+")</p><p>Осталось оплатить: "+((data['num_reserved']-data['num_payed'])*data['CostOfOneLessonWithDiscount']).toFixed(2)+"</p>");
                            $('.main_form_'+i).prepend('<div class="removePersonFromCombo"><button onClick="removePersonFromCombo('+nameQuoted+','+idQuoted+','+teacherQuoted+','+timetableQuoted+','+level_startQuoted+','+levelQuoted+','+intensive+')">Удалить студента с данного сочентания X </button></div>');
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

                function checkForPayedLessonToManipulateWithBtnAddDiscountAbilities(){
                    if($('#attendance_table_'+i).find('tbody #tr0 td').hasClass('payed_lesson')){
                        //alert('true');
                        $("#btn_add_discount_"+i).prop('disabled', true);
                    }else{
                        //alert('false');
                        $("#btn_add_discount_"+i).prop('disabled', false);
                    }
                }

				//pay3();
				//pay2();
			}else{$('#attendance_table').append("<p> Студент не учится ни на одном сочетании</p>");}
		},
		error: function(xhr, str){
			alert('Возникла ошибка: ' + xhr.responseCode);
		}
	});

	if(location.pathname == "/bs_mvc/person"){
		$('.payment_mark').not('.before_person_start_mark').not('.color_freeze').click(function(){
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
            //console.log(Combo);
            // console.log(stack);
			// return false;
            var intensive = 0;
            var teacher = false;
            var timetable = false;
            var level_start = false;
            //console.log(Combo);
            if(Combo[2] == 'intensive'){
                teacher = Combo[0];
                level_start = Combo[1];
                intensive = 1;
            }else{
                teacher = Combo[0];
                timetable = Combo[1];
                level_start = Combo[2];
            }
            //console.log(teacher,timetable,level_start,intensive,pay_flag);
			if(pay_flag == 0){
				var addOrRemove = 1; // добавляем занятие, отнимаем из баланса
				$.ajax({
					type:'POST',
					url: './Person/AddOrRemovePayedDate',
					data: {id:id,addOrRemove:addOrRemove,teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
					success: function(data){
                        //console.log(data);
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
					data: {id:id,addOrRemove:addOrRemove,teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
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

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
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
            var freeze_dates_arr = data['frozenDatesOfStudentOnEachCombination'] ;
			$('#attendance_table').html("<div class='table_title'><h3>"+data['name']+"</h3></div>");
            if(data['numOfLessonsOnEachCombination']){
                for(var i = 0;i<data['numOfLessonsOnEachCombination'].length;i++){
                    var teacher = data['combinations'][i]['teacher'];
                    var timetable = data['combinations'][i]['timetable'];
                    var level_start = data['combinations'][i]['level_start'];
                    var level = data['combinations'][i]['level'];
                    var intensive = parseInt(data['combinations'][i]['intensive']);

                    if(intensive){
                        $('#attendance_table').append("<p id='stack_" + i + "'>" + teacher + "/" + data['combinations'][i]['level_start'] + "/intensive</p>");
                    }else{
                        $('#attendance_table').append("<p id='stack_" + i + "'>" + teacher + "/" + timetable + "/" + level_start + "/" + level + "</p>");
                    }
                    $('#attendance_table').append("<div class='main_form'><table width='50%'' class='attendance_table default_table' id='attendance_table_"+i+"'></table></div>")
					var level_date = [] ;
					var teacher = data['combinations'][i]['teacher'];
                    var timetable = data['combinations'][i]['timetable'];
                    var level = data['combinations'][i]['level'];
                    var level_start = data['combinations'][i]['level_start'];

					//----- Построение шапки таблицы ;
					$("#attendance_table_"+i).empty();
                    $("#attendance_table_"+i).html("<tbody><tr id='th_line_"+i+"'><th class='attendance_name_th' id='name_th_"+i+"'><div id='attendance_table_name_"+i+"'>Имя</div></th></tr></tbody>");


                    $.ajax({
                        type: 'POST',
                        async: false,
                        url: './Freeze/CombinationDatesFittedToTimetable',
                        dataType: 'json',
                        data: {teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
                        success: function(data) {
                            var numberOfStartLesson = 21;
                            if(intensive){numberOfStartLesson = 10;}
                            for (var g = numberOfStartLesson; g > 0; g--) {
                                $('#name_th_' + i).after("<th class='attendance_th'><div class='rotateText'>" + data['sd_' + g] + "</div></th>");
                                level_date[g - 1] = data['sd_' + g];
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
					//			warning		if(color == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark color'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark color'>"+match+"</td>");
					//					}else if(color == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark color'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark color'>"+match+"</td>");
					//					}else if(color == 1 && start_date == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td bgcolor='#0033FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark person_start_mark color'>"+match+"</td>");
					//					}
                    //
					//					else if(color_freeze == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){ // color_freeze
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark color_freeze'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark color_freeze'>"+match+"</td>");
					//					}else if(color_freeze == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark color_freeze'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td bgcolor='#0033FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark color_freeze'>"+match+"</td>");
					//					}else if(color_freeze == 1 && start_date == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark person_start_mark color_freeze'>"+match+"</td>");
					//					} // /color_freeze
                    //
					//					else if(color == 0 && start_date == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark person_start_mark'>"+match+"</td>");
					//					}else if(color == 1 && stop_date == 1){
					//						// console.log(color, stop_date);
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td bgcolor='#0033FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark person_stop_mark color'>"+match+"</td>");
					//					}
					//					else if(color_freeze == 1 && stop_date == 1){  // color_freeze
					//						// console.log(color, stop_date);
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td bgcolor='#0033FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark person_stop_mark color_freeze'>"+match+"</td>");
					//					} // /color_freeze
                    //
					//					else if(color == 0 && stop_date == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark person_stop_mark'>"+match+"</td>");
					//					}else if(color == 0 && before_person_start == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color == 0 && after_person_stop == 0 && before_person_start == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark '>"+match+"</td>");
					//					}else if(color == 0 && after_person_stop == 1){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark'>"+match+"</td>");
					//						//	before_person_start_mark
					//					}else if(color == 0 && after_person_stop == 0){
					//						$('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark '>"+match+"</td>");
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
                        url: './Freeze/StudentNameAndDates',
                        dataType: 'json',
                        data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
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
                            //console.log(level_date);
                            var numberOfLessons = 21;
                            if(intensive){numberOfLessons = 10;}
                            for(var q=0;q<numberOfLessons;q++){
                                color = 0;
                                color_freeze = 0;
                                start_date = 0;
                                stop_date = 0;
                                before_person_start = 1;
                                after_person_stop = 0;
                                match = level_date[q];
                                for(var c in data['datesOfVisit']) {
                                    if (level_date[q] == data['datesOfVisit'][c]) {
                                        match = data['datesOfVisit'][c];
                                        color = 1;
                                    }	// ДАТЫ ПОСЕЩЕНИЙ (совпадение с датами сочетания)
                                }

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
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark color'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark color'>"+match+"</td>");
                                }else if(color == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark color'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark color'>"+match+"</td>");
                                }else if(color == 1 && start_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark person_start_mark color'>"+match+"</td>");
                                }
                                // color_freeze
                                else if(color_freeze == 1 && start_date == 0 && stop_date === 0  && before_person_start == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark color_freeze'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && before_person_start == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark color_freeze'>"+match+"</td>");
                                }else if(color_freeze == 1 && start_date == 0  && stop_date === 0 && before_person_start == 0 && after_person_stop == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark color_freeze'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color_freeze == 1 && start_date == 0 && stop_date === 0 && after_person_stop == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  id='td"+y+"_"+(w+1)+"' class='freeze_mark color_freeze'>"+match+"</td>");
                                }else if(color_freeze == 1 && start_date == 1) {
                                    $('#attendance_table_' + i + ' #td' + y + '_' + w).after("<td  bordercolor='#0000FF' id='td" + y + "_" + (w + 1) + "' class='freeze_mark person_start_mark color_freeze'>" + match + "</td>");
                                }

                                // /color_freeze

                                else if(color == 0 && start_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark person_start_mark'>"+match+"</td>");
                                }else if(color == 1 && stop_date == 1){
                                    // console.log(color, stop_date);
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark person_stop_mark color'>"+match+"</td>");
                                }
                                // color_freeze
                                else if(color_freeze == 1 && stop_date == 1){
                                    // console.log(color, stop_date);
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td  bordercolor='#0000FF' id='td"+y+"_"+(w+1)+"' class='freeze_mark person_stop_mark color_freeze'>"+match+"</td>");
                                }
                                // /color_freeze
                                else if(color == 0 && stop_date == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark person_stop_mark'>"+match+"</td>");
                                }else if(color == 0 && before_person_start == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 0 && after_person_stop == 0 && before_person_start == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark '>"+match+"</td>");
                                }else if(color == 0 && after_person_stop == 1){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark before_person_start_mark'>"+match+"</td>");
                                    //	before_person_start_mark
                                }else if(color == 0 && after_person_stop == 0){
                                    $('#attendance_table_'+i+' #td'+y+'_'+w).after("<td id='td"+y+"_"+(w+1)+"' class='freeze_mark '>"+match+"</td>");
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
                        url: './Person/NumPayedNumReservedCostOfOneLessonWithDiscount',
                        dataType: 'json',
                        data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start},
                        success: function(data) {
                            //console.log(data);
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
            var intensive = 0;
            var teacher = false;
            var timetable = false;
            var level_start = false;
            if(combination[2] == 'intensive'){
                teacher = combination[0];
                level_start = combination[1];
                intensive = 1;
            }else{
                teacher = combination[0];
                timetable = combination[1];
                level_start = combination[2];
            }

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
                data: {isPayed:isPayed,isFrozen:isFrozen,id:id,date:date,teacher:teacher,timetable:timetable,level_start:level_start,costOfOneLessonWithDiscount:costOfOneLessonWithDiscount,intensive:intensive},
                success: function(data){
                    //console.log(data);
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
    var teacherQuoted = "'" + teacher + "'";
    if($('#IntensiveCheckIn').attr('checked') == undefined){
        if ($('.timetable_soch')) {
            $('.timetable_soch').remove();
        }
        if ($('.level_start_soch')) {
            $('.level_start_soch').remove();
        }
        if ($('.level_soch')) {
            $('.level_soch').remove();
        }
        if ($('.person_start_soch')) {
            $('.person_start_soch').remove();
        }
        if ($('.person_stop_soch')) {
            $('.person_stop_soch').remove();
        }
        if (teacher == "choose_teacher") {
            if ($('.timetable_soch')) {
                $('.timetable_soch').remove();
            }
        } else {
            $.ajax({
                type: 'POST',
                url: './Main/Timetables',
                dataType: 'json',
                data: {teacher: teacher},
                success: function (data) {
                    $('.timetable_soch').remove();
                    $('.teacher_soch').after('<div class="item timetable_soch"><label for="timetable_sel">Расписание:</label><select name="timetable_sel" id="timetable_sel" class="add_form_select" onchange="get_level_start(' + teacherQuoted + ',this.value)"></select></div>');
                        $('#timetable_sel').append('<option value="choose_timetable">Выберите расисание</option>');
                    for (var i in data) {
                        $('#timetable_sel').append('<option value="' + data[i] + '">' + data[i] + '</option>');
                    }
                },
                error: function (xhr, str) {
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
        }
    }
    if($('#IntensiveCheckIn').attr('checked') == 'checked'){
        var id_person = "'" + $('input#id_person').val() + "'";
        if ($('.level_start_soch')) {
            $('.level_start_soch').remove();
        }
        $.ajax({
            type: 'POST',
            url: './Main/IntensiveLevelStart',
            dataType: 'json',
            data: {teacher: teacher},
            success: function (data) {
                $('.timetable_soch').remove();
                $('.teacher_soch').after('<div class="item level_start_soch"><label for="level_start_sel">Дата старта уровня:</label><select name="level_start_sel" id="level_start_sel" class="add_form_select"  onchange="get_level(' + teacherQuoted + ',undefined,this.value,' + id_person + ')" ></select></div>');
                //onchange="get_level_start(' + teacherQuoted + ',this.value)"
                if(data.length > 0){
                    $('#level_start_sel').append('<option value="choose_level_start">Выберите дату старта уровня</option>');
                }else{
                    $('#level_start_sel').append('<option value="choose_level_start">Нет интенсивов с данным преподавателем</option>');
                }
                for (var i in data) {
                    $('#level_start_sel').append('<option value="' + data[i] + '">' + data[i] + '</option>');
                }
            },
            error: function (xhr, str) {
                alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });
    }
}

function get_level_start(teacher,timetable){
    timetable = typeof timetable !== 'undefined' ?  timetable : false;
    //console.log($('#IntensiveCheckIn').attr('checked'));
    if($('#IntensiveCheckIn').attr('checked') == undefined) {
        var intensive = 0;
        if ($('.level_start_soch')) {
            $('.level_start_soch').remove();
        }
        if ($('.level_soch')) {
            $('.level_soch').remove();
        }
        if ($('.person_start_soch')) {
            $('.person_start_soch').remove();
        }
        if ($('.person_stop_soch')) {
            $('.person_stop_soch').remove();
        }
        if (timetable == "choose_timetable") {
            if ($('.level_start_soch')) {
                $('.level_start_soch').remove();
            }if ($('.person_start_soch')) {
                $('.person_start_soch').remove();
            }if ($('.person_stop_soch')) {
                $('.person_stop_soch').remove();
            }
        } else {
            var teacherQuoted = "'" + teacher + "'";
            var timetableQuoted = "'" + timetable + "'";
            var id_person = "'" + $('input#id_person').val() + "'";
            $.ajax({
                type: 'POST',
                url: './Main/LevelStart.php',
                dataType: 'json',
                data: {teacher: teacher, timetable: timetable, intensive:intensive},
                success: function (data) {
                    $('.level_start_soch').remove();
                    $('.timetable_soch').after('<div class="item level_start_soch"><label for="level_start_sel">Дата старта уровня:</label><select name="level_start_sel" id="level_start_sel" class="add_form_select" onchange="get_level(' + teacherQuoted + ',' + timetableQuoted + ',this.value,' + id_person + ')"></select></div>');
                    $('#level_start_sel').append('<option value="choose_level_start">Выберите дату старта уровня</option>');
                    for (var i in data) {
                        $('#level_start_sel').append('<option value="' + data[i] + '">' + data[i] + '</option>');
                    }
                },
                error: function (xhr, str) {
                    alert('Возникла ошибка: ' + xhr.responseCode);
                }
            });
        }
    }
    //if($('#IntensiveCheckIn').attr('checked') == 'checked'){
    //
    //}
}

function get_level(teacher,timetable,level_start,id){
    timetable = typeof timetable !== 'undefined' ?  timetable : false;
    //console.log(timetable);
    //return;
    var intensive = 0;
    if(!timetable){intensive = 1;}
    if(intensive){
        $('.person_start_soch').remove();
        $('.person_stop_soch').remove();
    }
    //console.log(intensive);
    //return;
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
			data: {teacher:teacher,timetable:timetable,level_start:level_start,id:id,intensive:intensive},
			success: function(data) {
                var AreAnyPayedOrAttenedOrFrozenLessonsExist = data;
                $.ajax({
                    type: 'POST',
                    url: './Main/LevelCombinationDates',
                    dataType: 'json',
                    data: {teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
                    success: function(data){
                        if(!intensive){var CombinationLevel = data['combinationLevel'][0][0];}
                        var CombinationDates = data['combinationDates'][0];
                        if(AreAnyPayedOrAttenedOrFrozenLessonsExist) {
                            //console.log(333333);
                            if (!intensive){
                                $('.level_soch').remove();
                                $('.level_start_soch').after('<div class="item level_soch"><label for="level_soch">Уровень:</label> <input class="add_form_select" type="text" id="level_soch" name="level_soch" value=' + CombinationLevel + ' style="border:none;" readonly></div>');
                            }
                            $('.person_start_soch').remove();
                            if(intensive){
                                $('.level_start_soch').after('<div class="item person_start_soch"><label for="person_start_soch">Дата старта студента:</label><select name="person_start_sel" id="person_start_sel" class="add_form_select" onchange="fix_person_stop(this.value)"></select></div>');
                            }else{
                                $('.level_soch').after('<div class="item person_start_soch"><label for="person_start_soch">Дата старта студента:</label><select name="person_start_sel" id="person_start_sel" class="add_form_select" onchange="fix_person_stop(this.value)"></select></div>');
                            }

                            for(var i in CombinationDates){
                                if( i == 0){$('#person_start_sel').append('<option value="'+CombinationDates[i]+'" selected>'+CombinationDates[i]+'</option>');}else{$('#person_start_sel').append('<option value="'+CombinationDates[i]+'">'+CombinationDates[i]+'</option>');}
                            }
                            $('.person_start_soch').hide();

                            $('.person_stop_soch').remove();
                            $('.person_start_soch').after('<div class="item person_stop_soch"><label for="person_stop_soch">Дата финиша студента:</label><select name="person_stop_sel" id="person_stop_sel" class="add_form_select" onchange="fix_person_start(this.value)"></select></div>');
                            for(var i in CombinationDates){
                                if( i == CombinationDates.length-1){$('#person_stop_sel').append('<option value="'+CombinationDates[i]+'" selected>'+CombinationDates[i]+'</option>');}else{$('#person_stop_sel').append('<option value="'+CombinationDates[i]+'">'+CombinationDates[i]+'</option>');}
                            }
                            $('.person_stop_soch').hide();
                            $('.add_form_btn').prop('disabled',true);
                            $('.warning').show();
                        }else{
                            //console.log(444444);
                            $('.warning').hide();
                            if(!intensive){
                                $('.level_soch').remove();
                                $('.level_start_soch').after('<div class="item level_soch"><label for="level_soch">Уровень:</label> <input class="add_form_select" type="text" id="level_soch" name="level_soch" value='+CombinationLevel+' style="border:none;" readonly></div>');
                            }

                            $.ajax({
                                type: 'POST',
                                url: './Main/StudentStartStop',
                                dataType: 'json',
                                data: {teacher: teacher, timetable: timetable, level_start:level_start,id:id,intensive:intensive},
                                success: function (data) {
                                    //console.log(data);
                                    $('.person_start_soch').remove();
                                    $('.person_stop_soch').remove();
                                    if(intensive) {
                                        $('.level_start_soch').after('<div class="item person_start_soch"><label for="person_start_soch">Дата старта студента:</label><select name="person_start_sel" id="person_start_sel" class="add_form_select" onchange="fix_person_stop(this.value)"></select></div>');
                                    }else{
                                        $('.level_soch').after('<div class="item person_start_soch"><label for="person_start_soch">Дата старта студента:</label><select name="person_start_sel" id="person_start_sel" class="add_form_select" onchange="fix_person_stop(this.value)"></select></div>');
                                    }
                                    for(var i in CombinationDates){
                                        if( CombinationDates[i] == data['studentStart']){
                                            $('#person_start_sel').append('<option value="'+CombinationDates[i]+'" selected>'+CombinationDates[i]+'</option>');
                                        }else{
                                            $('#person_start_sel').append('<option value="'+CombinationDates[i]+'">'+CombinationDates[i]+'</option>');
                                        }
                                    }

                                    $('.person_start_soch').after('<div class="item person_stop_soch"><label for="person_stop_soch">Дата финиша студента:</label><select name="person_stop_sel" id="person_stop_sel" class="add_form_select" onchange="fix_person_start(this.value)"></select></div>');
                                    for(var i in CombinationDates){
                                        if( CombinationDates[i] == data['studentStop']){
                                            $('#person_stop_sel').append('<option value="'+CombinationDates[i]+'" selected>'+CombinationDates[i]+'</option>');
                                        }else{
                                            if(!data['studentStop'] && i == CombinationDates.length-1){
                                                $('#person_stop_sel').append('<option value="'+CombinationDates[i]+'" selected>'+CombinationDates[i]+'</option>');
                                            }else{
                                                $('#person_stop_sel').append('<option value="'+CombinationDates[i]+'">'+CombinationDates[i]+'</option>');
                                            }
                                        }
                                    }
                                    if(data['studentStop']){fix_person_start(data['studentStop']);}
                                    if(data['studentStart']){fix_person_stop(data['studentStart']);}


                                },
                                error: function (xhr, str) {
                                    alert('Возникла ошибка: ' + xhr.responseCode);
                                }
                            });
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

function remove_combination(teacher,timetable,level_start,intensive){
    if(intensive == 'false'){
        intensive = false;
    }
    if(intensive == 'true'){
        intensive = true;
    }
    if(intensive == '1'){
        intensive = true;
    }
    if(intensive == '0'){
        intensive = false;
    }
    if(intensive == 'undefined'){
        intensive = false;
    }
    var $timetableOrIntensive = timetable;
    if(intensive){$timetableOrIntensive = intensive}
	if(confirm("Вы действительно хотите удалить сочетание: "+teacher+"/"+$timetableOrIntensive+"/"+level_start+" ?")){
		$.ajax({
			type: 'POST',
			url: './Attendance/RemoveCombination.php',
			dataType: 'json',
			data: {teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
			success: function(data) {
				$(".brick[style*='rgb(0, 0, 255)']").parents('.brickWrapper').remove();
				var teacher = $('.peresent_combinations .brick:first').children('[name=teacher_choose]').val();
                var timetable = false;
                var intensive = false;
                if($('.peresent_combinations .brick:first').children('[name=intensive]') == 'intensive'){
                    intensive = true;
                };
                if(!intensive){
                    timetable = $('.peresent_combinations .brick:first').children('[name=timetable_choose]').val();
                }
				var level_start = $('.peresent_combinations .brick:first').children('[name=level_start_choose]').val();
				$('.brick:first').css('borderColor','rgb(0, 0, 255)');
                //console.log(teacher,timetable,level_start,intensive);
				lgtt_match_fn(teacher,timetable,level_start,intensive);
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});
	}
}

function change_start_date(teacher,timetable,level_start,intensive){
    //contentType: 'application/json',
    if(intensive == 'false'){
        intensive = false;
    }
    if(intensive == 'true'){
        intensive = true;
    }
    if(intensive == '1'){
        intensive = true;
    }
    if(intensive == '0'){
        intensive = false;
    }
    if(intensive == 'undefined'){
        intensive = false;
    }
    //console.log(intensive,typeof(intensive));
    //return;
    var newLevelStart = $('#new_level_start').val();
    if(newLevelStart==""){
        alert('Введите новую дату старта');
    }else{
        $.ajax({
            type: 'POST',
            url: './Attendance/ChangeLevelStartDate.php',
            dataType: 'json',
            data: {teacher:teacher,timetable:timetable,level_start:level_start,new_level_start:newLevelStart,intensive:intensive},
            success: function(data) {
                console.log(data);
                if(data!=null){
                    if(data['wrongTimetable']!=null) {
                        alert('Данная дата не совпадает с расписанием(не тот день недели');
                    }else{
                    lgtt_match_fn(teacher,timetable,newLevelStart,intensive);
                    $('.brickWrapper').remove();
                    building_blocks(teacher,timetable,newLevelStart,intensive);
                    }
                }
            },
            error: function(xhr, str){
                alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });
    }
}

function send_to_archive(teacher,timetable,level_start,intensive){
    if(intensive == 'false'){
        intensive = false;
    }
    if(intensive == 'true'){
        intensive = true;
    }
    if(intensive == '1'){
        intensive = true;
    }
    if(intensive == '0'){
        intensive = false;
    }
    if(intensive == 'undefined'){
        intensive = false;
    }
    var $timetableOrIntensive = timetable;
    if(intensive){$timetableOrIntensive = intensive}
	if(confirm("Вы действительно хотите отправить в архив сочетание: "+teacher+"/"+$timetableOrIntensive+"/"+level_start+" ?")){

		$.ajax({
			type: 'POST',
			url: './Attendance/ToArchive',
			dataType: 'json',
			data: {teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
			success: function(data) {
				console.log();
                $('.brickWrapper').remove();
                building_blocks(teacher,timetable,level_start,intensive);
                if(intensive){
                    $(":input[value*='" + teacher + "']").siblings(":input[value*='" + intensive + "']").siblings(":input[value*='" + level_start + "']").parent().attr('style', 'border-color: rgb(0,0,255);');
                    $('.change_start_date').prop('disabled', true);
                }else {
                    $(":input[value*='" + teacher + "']").siblings(":input[value*='" + timetable + "']").siblings(":input[value*='" + level_start + "']").parent().attr('style', 'border-color: rgb(0,0,255);');
                    $('.change_start_date').prop('disabled', true);
                }

				//$('.past_combinations').append('<div class="brick"><input class="brick_input" type="text" name="teacher_choose" id="teacher_choose_archive" disabled /><input class="brick_input" type="text" name="timetable_choose" id="timetable_choose_archive" disabled /><input class="brick_input" type="text" name="level_start_choose" id="level_start_choose_archive" disabled /><input class="brick_input" type="text" name="level_choose" id="level_choose_archive" disabled /></div>')
				//$('#teacher_choose_archive').val(r);
				//$('#timetable_choose_archive').val(t);
				//$('#level_start_choose_archive').val(u);
                //
				//$.ajax({
				//	type: 'POST',
				//	url: './oldphpfiles/get_level.php',
				//	dataType: 'json',
				//	data: {teacher:teacher_quoted,timetable:timetable_quoted,level_start:level_start_quoted},
				//	success: function(data) {
				//		console.log();
				//		$('#level_choose_archive').val(data[0]);
				//		$(".brick[style*='rgb(0, 0, 255)']").remove();
				//		$('.brick:first').css('borderColor','rgb(0, 0, 255)');
				//		// lgtt_match_fn(r,t,u);
				//	},
				//	error: function(xhr, str){
				//		alert('Возникла ошибка: ' + xhr.responseCode);
				//	}
				//});
			},
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});

	}
}

function removePersonFromCombo(name,id,teacher,timetable,level_start,level,intensive){
    if(intensive == 'false'){
        intensive = false;
    }
    if(intensive == 'true'){
        intensive = true;
    }
    if(intensive == '1'){
        intensive = true;
    }
    if(intensive == '0'){
        intensive = false;
    }
    if(intensive == 'undefined'){
        intensive = false;
    }
    var changeableVariable = timetable;
    if(intensive){changeableVariable = 'intensive';}
    if(confirm("Вы действительно хотите удалить студента: "+name+" с сочетания : "+teacher+"/"+changeableVariable+"/"+level_start+" ?")){
		var notExistFlag = 0;
		$.ajax({
			type: 'POST',
			async: false,
			url: './Person/AreAnyPayedOrAttenedOrFrozenLessonsExist',
			dataType: 'json',
			data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
			success: function(data) {
                //console.log(data);
                //return;
                if(data){
                    alert('Для удаления студента с сочетания, удалите все проплаты, посещения либо заморозки студента на данном сочетании.');
                }else{
                    notExistFlag = 1;
                }
            },
			error: function(xhr, str){
				alert('Возникла ошибка: ' + xhr.responseCode);
			}
		});

		if(notExistFlag==1){
			$.ajax({
				type: 'POST',
				async: false,
				url: './Person/RemovePersonOnThisCombinationFromLevelsPersonAndPayedLessons',
				dataType: 'json',
				data: {id:id,teacher:teacher,timetable:timetable,level_start:level_start,intensive:intensive},
				success: function(data) {
					$('p').each(function(){
                        if(intensive){
                            if($(this).text()==teacher+"/"+level_start+"/"+intensive) {
                                $(this).parent().next().empty();
                                $(this).parent().empty();
                            }
                        }else{
                            if ($(this).text() == teacher + "/" + timetable + "/" + level_start + "/" + level) {
                                $(this).parent().next().empty();
                                $(this).parent().empty();
                            }
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
    //console.log(msg);
    //return;
    $.ajax({
        type: 'POST',
        url: './Main/SaveUpdateStudentCombination',
        dataType: 'json',
        data: msg,
        success: function(data){
            //console.log(data);
            //return;
            $('.level_person_form').hide();
            $("#level_person_form")[0].reset();
            if(data['state'] == 'insert' && data['intensive'] == 0){alert( data['fio_person']+" зарегистрирован на сочетании:\n " + data['teacher'] +"/" + data['timetable'] + "/" + data['level_start'] + "/" +data['level']);}
            if(data['state'] == 'insert' && data['intensive'] == 1){alert( data['fio_person']+" зарегистрирован на сочетании:\n " + data['teacher'] +"/intensive/" + data['level_start']);}
            if(data['state'] == 'update' && data['intensive'] == 0){alert("Для студена: " + data['fio_person']  + ",\nучащегося на сочетании: " + data['teacher'] +"/" + data['timetable'] + "/" + data['level_start'] + "\nдаты старт/стоп изменены соответственно на: " + data['personStart'] +"/" + data['personStop']);}
            if(data['state'] == 'update' && data['intensive'] == 1){alert("Для студена: " + data['fio_person']  + ",\nучащегося на сочетании: " + data['teacher'] +"/intensive/" + data['level_start'] + "\nдаты старт/стоп изменены соответственно на: " + data['personStart'] +"/" + data['personStop']);}
            $('.back_gray').hide();
        },
        error:  function(xhr, str){
            alert('Возникла ошибка: ' + xhr.responseCode);
        }
    });
}

function SVGraph(data) {

    var options = new Object();

    options.chart = new Object();
    options.chart.renderTo = 'container';
    options.chart.type = 'line';

    options.title = new Array();
    options.title = new Object();
    options.title.text = 'Money chart';

    options.series = new Array();
    options.series[0] = new Object();
    options.series[0].name = 'Money';
    options.series[0].data = data['amount'];

    options.xAxis = new Array();
    options.xAxis[0] = new Object();
    options.xAxis[0].categories = data['weekRange'];

    var chart = new Highcharts.Chart(options);

    $("text:contains('Highcharts.com')").remove();
}

function SpuByTeacherGraph(data) {

    var options = new Object();

    options.chart = new Object();
    options.chart.renderTo = 'containerSpuByTeacher';
    options.chart.type = 'line';

    options.title = new Array();
    options.title = new Object();
    options.title.text = 'SPU by teacher';

    options.yAxis = new Array();
    options.yAxis = new Object();
    options.yAxis.title = new Object();
    options.yAxis.title.text = 'SPU';

    options.legend = new Array();
    options.legend = new Object();
    options.legend.layout = 'vertical';
    options.legend.align = 'right';
    options.legend.verticalAlign = 'middle';

    var count = 0;

    options.series = new Array();

    for(var i in data['teachers']) {
        options.series[count] = new Object();
        options.series[count].name = i;
        options.series[count].data = data['teachers'][i]['amount'];
        count++;
    }

    options.xAxis = new Array();
    options.xAxis[0] = new Object();
    options.xAxis[0].categories = data['weekRange'];

    var chart = new Highcharts.Chart(options);

    $("text:contains('Highcharts.com')").remove();

    /*
     var chart1 = new Highcharts.Chart(options);

     options.series.push({
     name: 'John',
     data: [3, 4, 2]
     })

     return;

     var chart = new Highcharts.Chart({
     chart: {
     renderTo: 'container'
     },
     series: [{
     data: [data['weekRange']]
     }]
     });

     chart.series.push({

     });

     $('#container').highcharts({
     title: {
     text: 'Monthly Average Temperature',
     x: -20 //center
     },
     subtitle: {
     text: 'Source: WorldClimate.com',
     x: -20
     },
     xAxis: {
     categories: [data['weekRange']]
     },
     yAxis: {
     title: {
     text: 'Temperature (°C)'
     },
     plotLines: [{
     value: 0,
     width: 1,
     color: '#808080'
     }]
     },
     tooltip: {
     valueSuffix: '°C'
     },
     legend: {
     layout: 'vertical',
     align: 'right',
     verticalAlign: 'middle',
     borderWidth: 0
     },
     series: [{
     name: 'Tokyo',
     data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
     }, {
     name: 'New York',
     data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
     }, {
     name: 'Berlin',
     data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 28.6, 17.9, 14.3, 9.0, 3.9, 1.0]
     }, {
     name: 'London',
     data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
     }]
     });

     //options.series.push({
     //    name: 'John',
     //    data: [3, 4, 2]
     //})

     //graph.xAxis.push({
     //    categories:['ready','steady','go']
     //});
     //console.log(graph);
     */
}

function SpuSumGraph(data) {

    var options = new Object();

    options.chart = new Object();
    options.chart.renderTo = 'containerSpuSum';
    options.chart.type = 'line';

    options.title = new Array();
    options.title = new Object();
    options.title.text = 'SPU Sum';

    options.yAxis = new Array();
    options.yAxis = new Object();
    options.yAxis.title = new Object();
    options.yAxis.title.text = 'SPU';

    options.legend = new Array();
    options.legend = new Object();
    options.legend.layout = 'vertical';
    options.legend.align = 'right';
    options.legend.verticalAlign = 'middle';

    var count = 0;

    options.series = new Array();

    options.series[0] = new Object();
    options.series[0].name = 'Sum';
    options.series[0].data = data['sum']['amount'];


    options.xAxis = new Array();
    options.xAxis[0] = new Object();
    options.xAxis[0].categories = data['weekRange'];

    var chart = new Highcharts.Chart(options);

    $("text:contains('Highcharts.com')").remove();
}