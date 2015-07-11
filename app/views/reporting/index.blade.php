@section('reporting_link') uk-active @stop

@section('content')
<script type="text/javascript" src="/js/datepicker/picker.js"></script>
<script type="text/javascript" src="/js/datepicker/picker.date.js"></script>
<script type="text/javascript" src="/js/datepicker/picker.time.js"></script>
<article class="uk-article">
	<div class="uk-grid" id="reporting_grid">
		<div class="uk-width-1-6">
			<h1 class="uk-article-title">Reporting</h1>
		</div>
		<div class="uk-width-1-6"></div>
		<div class="uk-width-4-6">
			<div class="uk-grid" id="reporting_grid">
				<div class="uk-width-2-10 nos-reporting-shift" style="text-align: right;">
					<label for="dr1" style="position: relative; top: 3px; font-weight: bold; cursor: pointer;">Date Range</label>
				</div>
				<div class="uk-width-4-10 nos-reporting-shift">
					<form class="uk-form">
						<input id="dr1" type="text" placeholder="Start Date" value="<?= date('F j, Y', strtotime('monday this week')) ?>">
					</form>
				</div>
				<div class="uk-width-4-10 nos-reporting-shift">
					<form class="uk-form">
						<input id="dr2" type="text" placeholder="End Date" value="<?= date('F j, Y') ?>">
					</form>
				</div>
			</div>
		</div>
	</div>
	<h2>Risk</h2>
	<div class="uk-grid">
		<div class="uk-width-3-3" id="riskline" style="height: 200px;">
			<svg></svg>
		</div>
	</div>
	<div class="uk-grid">
		<div class="uk-width-1-3" style="background: pink; height: 100px;">
			Error
		</div>
		<div class="uk-width-1-3" style="background: pink; height: 100px;">
			Error
		</div>
		<div class="uk-width-1-3" style="background: pink; height: 100px;">
			Error
		</div>
	</div>
	<h2>Managed Node Topography</h2>
	<div class="uk-grid" style="margin-bottom: -30px;">
		<div class="uk-width-6-6">
			<div id="topography">
	
			</div>
		</div>
	</div>
	
	<div class="uk-grid">
		<div class="uk-width-1-5" style="height: 100px;">
			<h3>Asset Count By Region</h3>
		</div>
		<div id="node_count_regional" class="uk-width-1-5">

		</div>
		<div class="uk-width-1-5">

		</div>
		<div id="node_risk_regional" class="uk-width-1-5" style="height: 100px;">
			
		</div>
		<div class="uk-width-1-5" style="text-align: right;">
			<h3>Asset Risk By Region</h3>
		</div>
	</div>
	
	<script type="text/javascript" src="/js/components/d3.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="/js/nv.d3.min.js"></script>
	<script type="text/javascript" src="/js/components/topojson.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="/js/components/datamaps.world.min.js"></script>
	<script type="text/javascript">
	$(function(ev) {
		$('#dr1, #dr2').pickadate({
			format: 'mmmm dd, yyyy',
		});
		
		$("body").on("change", "#dr1, #dr2", function(ev) {
			if($("#dr1").val() == "" || $("#dr2").val() == "") {
				// nothing
			} else {
				// something
				
			}
			
		});
		
		var basic = new Datamap({
		  element: document.getElementById("topography"),
			geographyConfig: {
				popupOnHover: false,
			  highlightOnHover: false
			},
			
			fills: {
				defaultFill: '#C5C5C5',
				defaultRadius: '#2BA3D4'
			}
			
		});
		
		<?php $count = sizeof($magnitude_info); ?>
		<?php $loop_count = 0; ?>
		basic.bubbles([<?php foreach($magnitude_info as $name => $magnitude) { ?>
			{name: '<?= $name; ?>', fillKey: 'defaultRadius', radius: <?= $magnitude['magnitude']; ?>, latitude: <?= $magnitude['lat']; ?>, longitude: <?= $magnitude['lon']; ?>, sp_details: "<?= $magnitude['flat_count']; ?> <?= ($magnitude['flat_count'] > 1) ? 'Nodes' : 'Node'; ?>"}<?php $loop_count++; if($loop_count != $count) {print(",");}} ?>]
			, {popupTemplate: function(geo, data) {
					return '<div class="hoverinfo">' + data.name + '<br />' + data.sp_details;
			  }
				
			}
			
		);
		
		function renderBar(inDiv, data) {
			//Width and height
			var w = $("#" + inDiv).width();
			var h = $("#" + inDiv).height();
			var barPadding = 3;
		
			var dataset = data;
			
			//Create SVG element
			var svg = d3.select("#" + inDiv)
						.append("svg")
						.attr("width", $("#" + inDiv).width())
						.attr("height", $("#" + inDiv).height());

			svg.selectAll("rect")
			  .data(dataset)
			  .enter()
			  .append("rect")
			  .attr("x", function(d, i) {
			  	return i * (w / dataset.length);
			  })
			  .attr("y", function(d) {
			  	return h - (d * 4);
			  })
			  .attr("width", w / dataset.length - barPadding)
			  .attr("height", function(d) {
			  	return d * 4;
			  })
			  .attr("fill", function(d) {
					return "rgb(95, 185, 220)";
			  });

				svg.selectAll("text")
					.data(dataset)
				  .enter()
				  .append("text")
				  .text(function(d) {
				  	return d;
				  })
				  .attr("text-anchor", "middle")
				  .attr("x", function(d, i) {
				  	return i * (w / dataset.length) + (w / dataset.length - barPadding) / 2;
				  })
				  .attr("y", function(d) {
				  	return h - (d * 4) + 14;
				  })
				  .attr("font-family", "sans-serif")
				  .attr("font-size", "11px")
				  .attr("fill", "white");
		}
		
		renderBar("node_count_regional", [ 5, 10, 13, 19, 21]);
		renderBar("node_risk_regional", [ 18, 10, 13, 19, 5 ]);
		
	});
	

	
	</script>
	<script>
	/*These lines are all chart setup.  Pick and choose which chart features you want to utilize. */
	$(function(ev) {
		nv.addGraph(function() {
		  var chart = nv.models.lineChart()
		                .margin({left: 61})  //Adjust chart margins to give the x-axis some breathing room.
		                .useInteractiveGuideline(true)  //We want nice looking tooltips and a guideline!
		                .showLegend(true)       //Show the legend, allowing users to turn on/off line series.
		                .showYAxis(true)        //Show the y-axis
		                .showXAxis(true)        //Show the x-axis
		  ;

		  chart.xAxis     //Chart x-axis settings
		      .axisLabel('Time (ms)')
		      .tickFormat(d3.format(function(d) {return d3.time.format('%b %d')(new Date(d));}));

		  chart.yAxis     //Chart y-axis settings
		      .axisLabel('Risk (r)')
		      .tickFormat(d3.format('.02f'));

		  /* Done setting the chart up? Time to render it!*/
		  var myData = [
			    {
			      values: [{x: 1436624206737, y: 180}, {x: 1436624206738, y: 50}, {x: 1436624206739, y: 60}, {x: 1436624216739, y: 69}],      //values - represents the array of {x,y} data points
			      key: 'High Risk Issues', //key  - the name of the series.
			      color: '#ff7f0e'  //color - optional: choose your own line color.
			    },
			    {
			      values: [{x: 1436624206737, y: 160}, {x: 1436624206738, y: 55}, {x: 1436624206739, y: 20}, {x: 1436624216739, y: 56}],
			      key: 'Low Risk Issues',
			      color: '#2ca02c'
			    },
			    {
			      values: [{x: 1436624206737, y: 120}, {x: 1436624206738, y: 50}, {x: 1436624206739, y: 50}, {x: 1436624216739, y: 20}],
			      key: 'Total Risk Level',
			      color: '#0078EF'
			    }
			  ];   //You need data...

		  d3.select('#riskline svg')    //Select the <svg> element you want to render the chart in.   
		      .datum(myData)         //Populate the <svg> element with chart data...
		      .call(chart);          //Finally, render the chart!

		  //Update the chart when window resizes.
		  nv.utils.windowResize(function() { chart.update() });
		  return chart;
		});
		
	});
	
	</script>
</article>
@stop