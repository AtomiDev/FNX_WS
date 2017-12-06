<?php
	$_POST['data'] = '{
		"Outcome":"Success",
		"LastStep":"/thanks",
		"Policy":"Car",
		"PolicyName":"Young",
		"WizardName":"fnxonline",
		"Age":22,
		"Gender":"male",
		"EndDate":"20170310",
		"UID":"102030",
		"LeadType":"call",
		"CID":"1000000",
		"Term":"car",
		"Campaign":"1000000",
		"Source":"1000000",
		"Medium":"1000000",
		"Content":"1000000",
		"GCLID":"1000000",
		"lpurl":"1000000",
		"Label":"testlabel"
	}';
	
	
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<div class="container">
<!--<div class="jubmotron">
	JSON: <div id="js" style="word-break: break-all"></div>
	<br />
	Response: <div id="res" style="word-break: break-all"></div>
</div>-->
<form action='https://insurance.fnx.co.il/CarWizardSrvc/atomi_api.php' method='post'>
	<table class="table table-bordered">
		<?
		foreach(json_decode($_POST['data']) as $k=>$val) {
		?> <tr>
			<th><?php echo $k; ?></th>
			<td><input type="text" name="<?php echo $k; ?>" value="<?php echo $val; ?>" class="form-control"></td>
		</tr> <?
		}
		?>
	</table>
	<input type="submit" class="btn btn-success" id="go">
</form>
</div>
<script>
	// $(document).ready(function (e) {
		// e.preventDefault();
		// $("#go").on("click", function () {
			// var data = {};
			// $("form").serializeArray().map(function (item) {
				// data[item.name] = item.value;
			// });
			// $("#js").html(JSON.stringify(data));
			// $.ajax({
				// url: "atomi_api.php",
				// type: "post",
				// data: data,
				// dataType: "json",
				// complete: function (el) {
					// $("#res").html(el.responseText);
				// }
 			// })
		// });
		
	// });
</script>