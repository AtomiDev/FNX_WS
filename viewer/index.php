<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Leads viewer</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="kendo.common.min.css" />
    <link rel="stylesheet" href="kendo.default.min.css" />
    <link rel="stylesheet" href="kendo.default.mobile.min.css" />
</head>
<body>
	<style>
		/*body {
			font-family: Arial;
		} 
		tbody tr:nth-child(odd) {
			background-color: #eee;
		}
		th {
			cursor: pointer;
		}
		th.headerSortUp, th.headerSortDown {
			background-color: aliceblue;
		}*/

		body {
			padding: 15px;
		}

		#table-filters > div {
			padding-bottom: 10px;
		}
	</style>
		
	<?php 
		$db = mysqli_connect('localhost', 'fnx_webServiceAPI', 'MtRq3RWe2f2rphrS', 'fnx_webServiceAPI');
		mysqli_set_charset($db, "utf8");

		$dateFrom = $_GET['date-from'];
		$dateTo = $_GET['date-to'];

		$queryResult = null;
		if(!empty($dateFrom)){
			$queryResult = mysqli_query($db, "SELECT * FROM  `api-leads` WHERE date BETWEEN '" . $dateFrom . "' AND '" . $dateTo . "' ORDER BY date DESC");
		}
		else {
			$queryResult = mysqli_query($db, "SELECT * FROM  `api-leads` ORDER BY date DESC LIMIT 0, 30");
		}
	?>

	<p>
		<?php
			if(!empty($dateFrom)) {
				?>
				<span id="items-count"></span>
				<?php
			}
			else echo 'Showing latest 30 items. To view more select dates.';
		?>
	</p>
	<form id="main-form">
		<label for="date-from">From: </label><input type="text" placeholder="yyyy-mm-dd" name="date-from" value="<?php echo $dateFrom; ?>">
		<label for="date-to">To: </label><input type="text" placeholder="yyyy-mm-dd" name="date-to" value="<?php echo $dateTo; ?>">
		<button type="submit">Choose dates</button>
		<button type="button" id="clear-dates">Clear dates</button>
	</form>
	<br>

	<br>
	<br>

	<div id="main-table">
		
	</div>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="kendo.all.min.js"></script>
	<script>
		var data = [
			<?php
				$count = 0;	
			    while ($row = mysqli_fetch_assoc($queryResult)) {
			        ?>
			        {
				        id: '<?php echo $row['id']; ?>',
				        date: '<?php echo explode(' ', $row['date'])[0]; ?>',
				        time: '<?php echo date('H:i:s',strtotime(explode(' ', $row['date'])[1])); ?>',
				        outcome: '<?php echo $row['outcome']; ?>',
				        lastStep: '<?php echo $row['lastStep']; ?>',
				        policy: '<?php echo $row['policy']; ?>',
				        policyName: '<?php echo $row['policyName']; ?>',
				        wizardName: '<?php echo $row['wizardName']; ?>',
				        age: '<?php echo $row['age']; ?>',
				        gender: '<?php echo $row['gender']; ?>',
				        endDate: '<?php echo $row['endDate']; ?>',
				        nuid: '<?php echo $row['uid']; ?>',
				        leadType: '<?php echo $row['leadType']; ?>',
				        cid: '<?php echo $row['cid']; ?>',
				        term: '<?php echo $row['term']; ?>',
				        campaign: '<?php echo $row['campaign']; ?>',
				        source: '<?php echo $row['source']; ?>',
				        medium: '<?php echo $row['medium']; ?>',
				        content: '<?php echo $row['content']; ?>',
				        gclid: '<?php echo $row['gclid']; ?>',
				        lpurl: '<?php echo $row['lpurl']; ?>',
				        label: '<?php echo $row['label']; ?>'
			    	},
			        <?php
			        $count += 1;
			    }

			    mysqli_close($db);
			?>
		]; 

		var itemsCount = <?php echo $count; ?>;
		$('#items-count').html('Showing <?php echo $count; ?> items.');
		
		$(function(){
			$.datepicker.setDefaults({
				dateFormat: "yy-mm-dd"
			});
			$('input[type=text]').datepicker();

			$('#clear-dates').click(function(){
				location.href = "//insurance.fnx.co.il/CarWizardSrvc/viewer/";
			});

			$("#main-table").kendoGrid({
				dataSource: new kendo.data.DataSource({
                    data: data,
                    schema: {
                        model: {
                            fields: {
                            	date: { type: "string" },
                                time: { type: "string" },
                                outcome: { type: "string" },
                                lastStep: { type: "string" },
                                policy: { type: "string" },
                                policyName: { type: "string" },
                                wizardName: { type: "string" },
                                age: { type: "string" },
                                gender: { type: "string" },
                                endDate: { type: "string" },
                                nuid: { type: "string" },
                                leadType: { type: "string" },
                                cid: { type: "string" },
                                term: { type: "string" },
                                campaign: { type: "string" },
                                source: { type: "string" },
                                medium: { type: "string" },
                                content: { type: "string" },
                                gclid: { type: "string" },
						        lpurl: { type: "string" },
						        label: { type: "string" }
                            }
                        }
                    }
                }),
                height: 700,
                filterable: {
                    mode: "row"
                },
                sortable: true,
                resizable: true,
                columns: [
                	{ field: "date", title: "date" },
                    { field: "time", title: "time" },
                    { field: "outcome", title: "outcome" },
                    { field: "lastStep", title: "lastStep" },
                    { field: "policy", title: "policy" },
                    { field: "policyName", title: "policyName" },
                    { field: "wizardName", title: "wizardName" },
                    { field: "age", title: "age" },
                    { field: "gender", title: "gender" },
                    { field: "endDate", title: "endDate" },
                    { field: "nuid", title: "uid" },
                    { field: "leadType", title: "leadType" },
                    { field: "cid", title: "cid" },
                    { field: "term", title: "term" },
                    { field: "campaign", title: "campaign" },
                    { field: "source", title: "source" },
                    { field: "medium", title: "medium" },
                    { field: "content", title: "content" },
                    { field: "gclid", title: "gclid" },
                    { field: "lpurl", title: "lpurl" },
                    { field: "label", title: "label" },
                    { command: ["destroy"], title: "&nbsp;", width: "100px" }
                ],
                editable: {
		            mode: 'inline',
		            confirmation: true
		        },
                remove:function(e){ 
					var id = e.model.id;

					$.ajax({
						method: 'POST',
						url: '//insurance.fnx.co.il/CarWizardSrvc/viewer/delete-data.php',
						data: {
							'id': id
						}
					})
					.done(function(){
						$('#items-count').html('Showing ' + itemsCount + ' items.');
					})
					.fail(function(response){
						console.log(response);
					});
		        }
            });
		});
	</script>
</body>
</html>