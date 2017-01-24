<?php 

function zmianadzien($nazwa) {
    
    switch($nazwa){
        case "Monday":
            return "Poniedziałek";
            break;
        case "Tuesday":
            return "Wtorek";
            break;
        case "Wednesday":
            return "Środa";
            break;
        case "Thursday":
            return "Czwartek";
            break;
        case "Friday":
            return "Piątek";
            break;
        case "Saturday":
            return "Sobota";
            break;
        case "Sunday":
            return "Niedziela";
            break;
    }
}

$glowna_id = get_option('page_on_front'); 
    global $wpdb;

    $nazwatabeli = $wpdb->prefix . "statystyki_dzienny";
    $datapetlam = date('Y-m');
    $datapetlad = date('d')-6;


            for ($i = 0; $i < 7; $i++){
                $datacala = $datapetlam."-".$datapetlad;
                $danewejsciowe = $wpdb->get_var("SELECT COUNT(*) FROM ".$nazwatabeli." WHERE `strona_id` = ".$glowna_id." AND `wejscie_data` = '".$datacala."'");
                $datapetlad++;
                $unixTimestamp = strtotime($datacala);
                $dzienpe = date("l", $unixTimestamp);
                $dniwyjsciowe[] = "".zmianadzien($dzienpe)."";
                $danewyjscie[] = $danewejsciowe;
            }
            $dataod = $_POST["dataod"];
                        $datado = $_POST["datado"];
                        if($dataod == "" && $dataod == ""){
                        $datado = date('Y-m-d');
                        $dataod = date('Y-m-d');
                        
                        }
?>
<?php $actual_link= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
<h1>Statystyki<form name="statystyki" method="POST" style="float:right; padding-right:20px;" action="<?php echo $actual_link ?>">
            <input type="date" name="dataod" value="<?php echo $dataod; ?>"/>
            <input type="date" name="datado" value="<?php echo $datado; ?>"/>
            <input type="submit" class="button"/>
            </form></h1>
<hr>
<div class="wrap" style="max-height:500px;">
    <div class="row" style="width:100%; max-height:408px;">
        <div class="row" style="width:48%; float:left;">
            <h2>Strona główna
            </h2>
            <canvas id="canvas" ></canvas>
        </div>
        <div class="row" style="width:48%; float:left; padding-left:30px; overflow-y:auto; height:463px;">
            <h2>Odsłony <?php $page_ids=get_all_page_ids(); ?>
                <form name="statystyki" method="POST" style="float:right; padding-right:20px;" action="<?php echo $actual_link ?>">
            <input type="hidden" name="dataod" value="<?php echo $_POST["dataod"]; ?>"/>
                <input type="hidden" name="datado" value="<?php echo $_POST["datado"]; ?>"/>
                    <select name="strona">
                
                <option value="">Wszystkie</option>
               <?php foreach($page_ids as $page)
                {
                echo '<option value="'.$page.'">'.get_the_title($page).'</option>';
                } ?>
            </select>
            <input type="submit" class="button"/>
            </form>
            </h2>
            <table>
                <thead style="text-align:left;">
                    <th>Nazwa strony</th>
                    <th style="text-align:right;">Liczba wejść</th>
                </thead>
                <tbody>
                    
                       <?php
                       global $wpdb;
                        $dzis = date('Y-m-d');
                         $wybrana = $_POST["strona"];
                        $nazwatabeli = $wpdb->prefix . "statystyki_dzienny";
                        if ($wybrana == ""){
            
                        $sqls = "SELECT DISTINCT `strona_id` FROM ".$nazwatabeli." WHERE `wejscie_data` BETWEEN '".$dataod."' AND '".$datado."' ";
                        } else{
                        $sqls = "SELECT DISTINCT ".$wybrana." `strona_id` FROM ".$nazwatabeli." WHERE `wejscie_data` BETWEEN '".$dataod."' AND '".$datado."' ";

                        }
                       
                        $tabledata = $wpdb->get_results($sqls);
                        
                           $i = 0;
                            foreach($tabledata as $data) { 
                                echo "<tr>";
                                    $posts[] = get_post($data->strona_id); 
                                    $title[] = $posts[$i]->post_title;
                                    echo '<td><a href="'.$posts[$i]->guid.'" target="_blank">'.$title[$i].'</a></td>';
                                   
                                    
                                       
                                   $datar = $wpdb->get_var("SELECT COUNT(*) FROM ".$nazwatabeli." WHERE `strona_id` = ".$posts[$i]->ID." AND `wejscie_data` BETWEEN '".$dataod."' AND '".$datado."'");
                                    echo '<td style="text-align:right;">'.$datar.'</td>';
                                    $i++;
                                echo "</tr>";
                            }
                       
                       ?>

                </tbody>
            </table>
        </div>
    </div>
</div>




	<script>
         
        var wartosci = <?php echo json_encode($danewyjscie); ?>;
        var dni = <?php echo json_encode($dniwyjsciowe); ?>;

	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
	var lineChartData = {
		labels: dni,
                datasets: [
                    
                    {
                        label: "My Second dataset",
                        fillColor: "rgba(151,187,205,0.2)",
                        strokeColor: "rgba(151,187,205,1)",
                        pointColor: "rgba(151,187,205,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: wartosci
                    }
                ]
            }
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Line(lineChartData, {
			responsive : true
		});
               
                
	}
        
        var data = [
    {
        value: 300,
        color:"#F7464A",
        highlight: "#FF5A5E",
        label: "Red"
    },
    {
        value: 50,
        color: "#46BFBD",
        highlight: "#5AD3D1",
        label: "Green"
    },
    {
        value: 100,
        color: "#FDB45C",
        highlight: "#FFC870",
        label: "Yellow"
    }
];
                var ctxs = document.getElementById("tyg").getContext("2d");
		window.myBar = new Chart(ctxs).Doughnut(data, {
			responsive : false
		});
                
                var datakol = {
    labels: ["January", "February", "March", "April", "May", "June", "July"],
    datasets: [
        {
            label: "My First dataset",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [65, 59, 80, 81, 56, 55, 40]
        },
        {
            label: "My Second dataset",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [28, 48, 40, 19, 86, 27, 90]
        }
    ]
};
var ctxss = document.getElementById("mies").getContext("2d");
		window.myBar = new Chart(ctxss).Line(datakol, {
			responsive : false
		});
	</script>
