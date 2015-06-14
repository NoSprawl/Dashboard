@section('reporting_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Reporting</h1>
	<div class="uk-grid">
		<div class="uk-width-4-4">
			<h2>Topography</h2>
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
				defaultFill: '#6BF9A6',
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