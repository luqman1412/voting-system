
<?php 
$label = array( "ali", "abu", "salman");
$votepercentage =array(10,3,3);
$name =array("ali"=>10,"abu"=>3,"salman"=>3);
 ?>
<!DOCTYPE html>
<html>
<head>
    <title></title>

</head>
<body>
    <h1>chart below</h1>
<canvas id="myChart"></canvas>

</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'doughnut',

    // The data for our dataset
    data: {
        labels: <?php echo json_encode($label);?> ,
        datasets: [{
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
            data: <?php echo json_encode($votepercentage, JSON_NUMERIC_CHECK);?>
        }]
    },

    // Configuration options go here
    options: {}
});
</script>
</html>