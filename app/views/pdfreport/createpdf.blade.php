<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
    <meta name="description" content="">
    <meta name="author" content="">
<script src="{{ asset('js/jquery.min.js')}}"></script>
    </head>
<?php

    
    
    if (strpos($_SERVER['REQUEST_URI'],'public') !== false) {
        $pos = strpos($_SERVER['REQUEST_URI'],'public');
        $portion = substr($_SERVER['REQUEST_URI'], 0, $pos+7);
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $portion;
    } else {
        $url = 'http://' . $_SERVER['SERVER_NAME'].'/';
    }
    

    
    foreach($report_period as $period) {
        $absence    =   $url."createPdfReport?report=absence&period=".$period;
        $lateness   =   $url."createPdfReport?report=lateness&period=".$period;
        $wsproximity=   $url."createPdfReport?report=wsproximity&period=".$period;
        
        $form_absence[]         =   '<form name="" id="" class="submit-auto-pdf" target="_blank" method="post" action="'.$absence.'"></form>';
        $form_lateness[]        =   '<form name="" id="" class="submit-auto-pdf" target="_blank" method="post" action="'.$lateness.'"></form>';
        $form_wsproximity[]     =   '<form name="" id="" class="submit-auto-pdf" target="_blank" method="post" action="'.$wsproximity.'"></form>';
    }
    
    
    foreach($form_absence as $absence) {
        echo $absence;
    }

    foreach($form_lateness as $lateness) {
        echo $lateness;
    }

    foreach($form_wsproximity as $wsproximity) {
        echo $wsproximity;
    } 
       
    
    
?>

<script type="text/javascript">
    
    $(document).ready(function() {
//       if(confirm("Are you sure you want to create pdf report")) {
        var time_delay = 2000;
        $(".submit-auto-pdf").each(function(index, elem){
            time_delay  =   time_delay+3000; 
            setTimeout(function(){ 
                $(elem).submit();
            }, time_delay);
            
        });
        

//        } else {
//            alert("Process cancelled");
//        }
    });
    
</script>
</html>
