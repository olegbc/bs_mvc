<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/jqBarGraph.js"></script>
</head>
<body>
<div id="stackedGraph_wrapper">
	<div id="stackedGraph_multi"></div>
	<div id="stackedGraph"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	arrayOfDataMulti = new Array(
		[[1,6,2,2,1],'02-01-2006 : 02-01-2006'],
		[[1,8,2,2,3],'02-01-2006 : 02-01-2006'],
		[[2,18,2,1,4],'02-01-2006 : 02-01-2006'],
		[[2,22,3,5,2],'02-01-2006 : 02-01-2006'],
		[[1,22,3,5,2],'02-01-2006 : 02-01-2006']
	);
	
	var first = true; // whether it first time legend_all clicked
	
	build(false,first); // first time, we do need inscriptions and legend
	build2(true);
	init(); // initialization .clickable and legend_all
	
	function init(){	
		$('.clickable').click(function(){
			var r = $(this).attr('id'); 
			var t;
			t = $(this).attr('id').substr(-1);
			
			legend_arr  = new Array(
				[[[3,6,2,2,1][t]],'02-01-2006 : 02-01-2006'],
				[[[3,8,2,2,3][t]],'02-01-2006 : 02-01-2006'],
				[[[4,18,2,1,4][t]],'02-01-2006 : 02-01-2006'],
				[[[4,22,3,5,2][t]],'02-01-2006 : 02-01-2006'],
				[[[4,22,3,5,2][t]],'02-01-2006 : 02-01-2006']
			);
			
			for(i in arrayOfDataMulti){
				legend_arr[i][0][0] = arrayOfDataMulti[i][0][t];
				legend_arr[i][1] = arrayOfDataMulti[i][1];
			} 
			
			$("#stackedGraph_multi").empty();
			$("#stackedGraph_multi").attr('style','');
			$("#stackedGraph_multi").prev('h3').remove();
			
			build_single(t);
			init();
		});
	
		$('#legend_all').click(function(){
			$("#graphHolder4stackedGraph_multi").remove();
			$('<div id="stackedGraph_multi"></div>').prependTo($("#stackedGraph_wrapper"));
			
		//	arrayOfDataMulti = arrayOfDataMulti_main;
		
			build(true,first); // build(all,times)	
			first = false;
			init();	
			
		});
	}		
		

});

/*----------------------------------------- ready -----------------------------------*/
	function build(e,p){
		var t = e;
		var y = p;
		$("#stackedGraph_multi").jqBarGraph({
			all: e,
			first: y,
			single: false,
			data: arrayOfDataMulti,
			colors: ['#242424','#437346','#97D95C','#FF6A00','#7F3300'],
			legends: ['Сергей','Вера','Даниил','Юля','Антон'],
			legend: true,
			width: 1500,
			type: 'multi',
			animate: false,
		//	postfix: ' учеников',
			title: '<h3>Количество учеников  <br /><small>по неделям у каждого преподавателя</small></h3>'
		});
	}
	function build_single(e){
		var m = e;
		//	console.log(m);
		$("#stackedGraph_multi").jqBarGraph({
			all: false,
			single: true,
			single_data: [['#242424','#437346','#97D95C','#FF6A00','#7F3300'][m]],
			data: legend_arr,
			colors: ['#242424','#437346','#97D95C','#FF6A00','#7F3300'],
			legends: ['Сергей','Вера','Даниил','Юля','Антон'],
			legend: true,
			width: 1500,
			type: 'multi',
			animate: false,
		//	postfix: ' учеников',
			title: '<h3>Количество учеников  <br /><small>по неделям у каждого преподавателя</small></h3>'
		});
	} 
	function build2(e){
		var t = e ;
		$("#stackedGraph").jqBarGraph({
			sum: e,
			data: arrayOfDataMulti,
			colors: ['#242424','#437346','#97D95C','#FF6A00','#7F3300'],
			legends: ['Сергей','Вера','Даниил','Юля','Антон'],
			legend: true,
			width: 1500,
			animate: false,
		//	type: 'multi',
		//	postfix: ' учеников',
			title: '<h3><small>Сумма по неделям</small></h3>'
		});
	}
</script>
</body>
</html>