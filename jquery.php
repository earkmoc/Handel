<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.13.custom.min.js"></script>

<script>
	$(function() {
		$( "button, input:submit, input:button, a", ".demo" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
</script>
