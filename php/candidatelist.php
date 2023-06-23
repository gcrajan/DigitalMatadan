<?php


require("Classes/Classes.php");


if($Latest_Election_Year)
{
    
    $options = $G->AreaOptions($_GET["GPID"]);

    while($option = $options->fetch_assoc())
    {
        echo "<option value='".$option["Election_area"]."'> ".$option["Election_area"]." ".  $option["District"] ." ". $option["Wardno"]."</option>";
    }
    
}





?>