@section('reporting_link') uk-active @stop

@section('content')
<article class="uk-article">
	<h1 class="uk-article-title">Reporting</h1>
	<div class="uk-grid">
		<div class="uk-width-1-4"><h2>Updates</h2><div id="wrapper"></div></div>
	</div>
	
	<script type="text/javascript">
	var dataset = [ 5, 10, 15, 20, 25 ];
  d3.select("#wrapper").selectAll("div")
      .data(dataset)
      .enter()
      .append("div")
      .attr("class", "bar")
      .style("height", function(d) {
          var barHeight = d * 5;
          return barHeight + "px";
      });
	</script>
</article>
@stop