@section('reporting_link') uk-active @stop

@section('content')
<script type="text/javascript" src="/js/components/datepicker.min.js"></script>
<article class="uk-article">
	<div class="uk-grid" id="reporting_grid">
		<div class="uk-width-1-5">
			<h1 class="uk-article-title">Reporting</h1>
		</div>
		<div class="uk-width-1-5"></div>
		<div class="uk-width-1-5 nos-reporting-shift" style="text-align: right;">
			<label for="dr1" style="position: relative; top: 3px; font-weight: bold; cursor: pointer;">Date Range</label>
		</div>
		<div class="uk-width-1-5 nos-reporting-shift nos-dash-after">
			<form class="uk-form">
				<input id="dr1" type="" placeholder="Start Date" data-uk-datepicker="{format:'MM/DD/YYYY'}">
			</form>
		</div>
		<div class="uk-width-1-5 nos-reporting-shift">
			<form class="uk-form">
				<input id="dr2" type="" placeholder="End Date" data-uk-datepicker="{format:'MM/DD/YYYY'}">
			</form>
		</div>
	</div>
	<h2>Managed Node Topography</h2>
	<div class="uk-grid">
		<div class="uk-width-6-6">
			<div id="topography">
	
			</div>
		</div>
	</div>
	
	<script type="text/javascript" src="/js/components/d3.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="/js/components/topojson.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="/js/components/datamaps.world.min.js"></script>
	<script type="text/javascript">
	$(function(ev) {
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
	{name: '<?= $name; ?>', fillKey: 'defaultRadius', radius: <?= $magnitude['magnitude']; ?>, latitude: <?= $magnitude['lat']; ?>, longitude: <?= $magnitude['lon']; ?>, sp_details: "Rackspace: 1<br />AWS: 1"}<?php $loop_count++; if($loop_count != $count) {print(",");}} ?>], {
	  popupTemplate: function(geo, data) {
return '<div class="hoverinfo">' + data.name + '<br />' + data.sp_details;
	  }
		
		});
	
	});
	
	</script>
</article>
@stop