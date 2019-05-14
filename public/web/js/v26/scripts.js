$(document).ready(function()
            {
				$(".tab-content").hide(); //Hide all content
	$(".tab-nav li:first").addClass("active").show(); //Activate first tab
	$(".tab-content:first").show(); //Show first tab content


				//$(".tab-nav>li>a").click(function() {
												  //alert('hi');
				//$(".tab-nav li").removeClass("active"); //Remove any "active" class
				//$(this).addClass("active"); //Add "active" class to selected tab
				//$(".tab-content").hide(); 
				
 				var $panel2 = $('#panel2');
                var $panel1 = $('#panel1');
				$('.btn-2').toggle(
				function()
                {
                  if ($panel1.width() == 730)
                    {
						//alert('1');
						$(".tab-nav li:last").addClass("active"); //Activate first tab
						$(".tab-nav li:first").removeClass("active");
						 $('#panel1').css('width' ,'730px');
						$('#panel2').css('width' ,'0px');
                       /* $('#panel1').animate({width: '730px'}, 300);
						$('#panel2').animate({width:'0px'},300);*/
						 $("#panel1").show("fast");
						  $("#panel2").hide("fast");
						$('#panel1').css('display','block');
						$('#panel2').css('display','none');
						
                    }
					else
					{
						$(".tab-nav li:last").addClass("active"); //Activate first tab
						$(".tab-nav li:first").removeClass("active");
						 $('#panel1').css('width' ,'730px');
						$('#panel2').css('width' ,'0px');
						/*$('#panel1').animate({width:'730px'},300);
						 $('#panel2').animate({width: "0px"}, 300);*/
						 $("#panel1").show("fast");
						  $("#panel2").hide("fast");
						$('#panel1').css('display','block');
						$('#panel2').css('display','none');
					}
					 },
                
                function()
                {
                    if ($panel2.width() == 210)
                    {
						//alert('2');
						$(".tab-nav li:last").addClass("active"); //Activate first tab
						$(".tab-nav li:first").removeClass("active");
						 $('#panel1').css('width' ,'730px');
						$('#panel2').css('width' ,'0px');
						/*$('#panel1').animate({width:'730px'},300);
						 $('#panel2').animate({width: "0px"}, 300);*/
						 $("#panel1").show("slow");
						  $("#panel2").hide("fast");
						$('#panel1').css('display','block');
						$('#panel2').css('display','none');
                       
                    }
					else
					{
						$(".tab-nav li:last").addClass("active"); //Activate first tab
						$(".tab-nav li:first").removeClass("active");
						 $('#panel1').css('width' ,'730px');
						$('#panel2').css('width' ,'0px');
                       /* $('#panel1').animate({width: '730px'}, 300);
						$('#panel2').animate({width:'0px'},300);*/
						 $("#panel1").show("slow");
						  $("#panel2").hide("fast");
						$('#panel1').css('display','block');
						$('#panel2').css('display','none');
					}
                   
                });
                $('.btn-1').toggle(
                function()
                {
                    if ($panel1.width() == 730)
                    {
						
						//alert('3');
						$(".tab-nav li:first").addClass("active"); //Activate first tab
						$(".tab-nav li:last").removeClass("active");
						$('#panel2').css('width' ,'210px');
						 $('#panel1').css('width' ,'0px');  
						/*$('#panel2').animate({width:'210px'},300);
						 $('#panel1').animate({width: '0px'}, 300);  */
						 $("#panel2").show("slow");
						  $("#panel1").hide("fast");
						$('#panel2').css('display','block');
						$('#panel1').css('display','none');
                       
					 }
					 else
					 {
						$(".tab-nav li:first").addClass("active");
						$(".tab-nav li:last").removeClass("active");//Activate first tab
						$('#panel2').css('width' ,'210px');
						 $('#panel1').css('width' ,'0px');  
                        /*$('#panel2').animate({width: '210px'}, 300);
						$('#panel1').animate({width:'0px'},300);*/
						$("#panel2").show("slow");
						  $("#panel1").hide("fast");
						$('#panel2').css('display','block');
						$('#panel1').css('display','none');
					 }
                    
                },
                function()
                {
                    if ($panel2.width() == 210)
                    {
						//alert('4');
						$(".tab-nav li:first").addClass("active");
						$(".tab-nav li:last").removeClass("active");//Activate first tab
						$('#panel2').css('width' ,'210px');
						 $('#panel1').css('width' ,'0px');  
                        /*$('#panel2').animate({width: '210px'}, 300);
						$('#panel1').animate({width:'0px'},300);*/
						$("#panel2").show("slow");
						  $("#panel1").hide("fast");
						$('#panel2').css('display','block');
						$('#panel1').css('display','none');
						 
                    }
					else
					{
						$(".tab-nav li:first").addClass("active"); //Activate first tab
						$(".tab-nav li:last").removeClass("active");
						$('#panel2').css('width' ,'210px');
						 $('#panel1').css('width' ,'0px');  
						/*$('#panel2').animate({width:'210px'},300);
						 $('#panel1').animate({width: '0px'}, 300);  */
						 $("#panel2").show("slow");
						  $("#panel1").hide("fast");
						$('#panel2').css('display','block');
						$('#panel1').css('display','none'); 
					}
                   
                });     
            });
			//	});
