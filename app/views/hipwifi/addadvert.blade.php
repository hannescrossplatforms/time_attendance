@extends('layout')
<?php $edit = $data["edit"] ?>

@section('content')

<body class="hipWifi">
	<div class="container-fluid">
      		<div class="row">
		@include('hipwifi.sidebar')
			<div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">
				<h3 class="page-header">Advert Manager</h3>
	   @if ($errors->has())
              <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                      {{ $error }}<br>        
                  @endforeach
                  </div>
                 @endif
                        @if($edit)
    	                 {{ Form::open(array('url'=>"hipwifi_editadvertmediasave", 'files' => true))}}
                            {{ Form::hidden('id', $data['mediaid'])}}
                            {{ Form::hidden('brandid', $data['brandid'])}}
                        @else
                        {{ Form::open(array('url' => "hipwifi_addadvertsave", 'files' => true))}}
                        @endif

		  {{ Form::label('campaign', 'Campaign'); }}<br>
		 @if ($data['edit'] == true)
                       {{ Form::text('campaign', $data['campaign'], ['class' => 'form-control', 'required' => 'required']); }}<br>
                       {{ Form::label('target', 'Target'); }}<br>
                       {{ Form::text('target', $data['target'], ['class' => 'form-control', 'disabled' =>'disabled']); }}<br>
                     
                       {{ Form::label('media', 'Media: '. $data['type']); }}<br>
                       <hr/>
                      {{--  //$assetsserver->value . 'hipwifi/images/' --}}
                       <img src="{{$data['media']}}" height="600px" width="300px"/>
                       <br/>
                       <hr/>
                     {{--   {{ Form::text('media', $data['media'], ['class' => 'form-control', 'disabled' =>'disabled']); }} --}}<br/>
                         @if($data['type'] == "video")
                                    {{ Form::label('image', 'Upload New Video (max 100 Mb)')}}
                                    {{ Form::file('video') }}
                                    <br/>
                                 @else                                 
                                    {{ Form::label('image', 'Upload New Image (max 100 Kb)')}}
                                    {{ Form::file('image') }}
                                     <br/>
                          @endif 
                             

                       

                      @else
                      {{ Form::text('campaign', null, ['class' => 'form-control', 'required' => 'required']); }}<br>
                      {{ Form::label('brand', 'Brand')}}<br>
                      {{ Form::text('brand', $data['brandname'] , ['class' => 'form-control', 'readonly'=> 'readonly'] )}}<br>
                     
				
				


				{{ Form::checkbox('target', 'target', null, ["id" => "target"]) }}
				{{ Form::label('target', "Choose a single venue for advert?")}}

				
				<div class="panel panel-default" id="targetting">
				     	<div class="panel-heading">Targetting</div>
					<div class="panel-body">
						
						

						{{Form::label('country', 'Country')}} <br>
						{{ Form::text('country', $data['brandcountry'] , ['class' => 'form-control'] )}}<br>
						
						{{ Form::hidden('countrie_id', $data['brandcountrieid'] )}}

						{{ Form::label('province', 'Province/State') }} <br>
						<select id="provincelist" name="province_id" class="form-control no-radius" ></select>
						<br>


						{{ Form::label('citie', 'City') }}<br>
						<select id="citieslist" name="city_id" class="form-control no-radius" ></select><br>

						{{ Form::label('venue', 'Venue') }}<br>
						<select id="venueslist" name="venue_id" class="form-control no-radius" ></select><br>
					</div> 
				</div>

				<div class="panel panel-default">
				     	<div class="panel-heading">Advert Content</div>
					<div class="panel-body">
					<div class="row">
						<div class='col-md-8'>
						
						 {{ Form::checkbox('video', 'image', null, ['id' => 'imagecheck']) }}
						 {{ Form::label('image', 'Image (max 100 Kb)')}}
                              
						<br><br>
						

                               
				    		 {{ Form::checkbox('video', 'video', null, ['id' => 'videocheck']) }}
						 {{ Form::label('video', 'Video (max 100 Mb)')}}
                                
						 </div>
						 <div class='col-md-4'>
						 <div class="panel panel-default" id="imageorvideo">
						     	<div class="panel-heading" id='imageorvideoheading'>Image</div>
							<div class="panel-body">
							{{ Form::file('image') }}
							</div>
						 </div>
						 </div>
					   </div>
						
					</div> 

				</div>
                        @endif
                    

				
							
						
				{{Form::submit('Submit', ["class" => 'btn btn-primary'])}}
                      <a class="btn btn-default" href="{{url('hipwifi_showsinglebrandmedia/'.$data['brandid'])}}">Cancel</a>



				{{ Form::close() }}
			</div>
		</div>
	</div>

	 <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    
    <script src="/js/jquery.form.js"></script>
    <script src="/js/prefixfree.min.js"></script> 

    <script type="text/javascript">
    	
    	$(document).ready(function(){
    		var  countryId= <?php echo $data['brandcountryid']; ?>;
    		//alert(countryId);
    		$(':checkbox:checked').prop('checked',false);
    		listProvinces(countryId);
    		$('#imageorvideo').hide();
    		$('#targetting').hide();
    		

    		
    		function listProvinces(countryId) {
    			var  countryId= <?php echo $data['brandcountryid']; ?>;
    			//alert(countryId);

    			$.ajax({
    				type: 'GET',
    				dataType: 'json',
    				contentType: "application/json",
    				url: "{{url('lib_getprovinces/" + countryId +"');}}",
    				success: function(provinces) {
			              var provincesjson = JSON.parse(provinces); 
			              console.log("Provinces : " + provinces);

			              openSelect = '<select id="provincelist" name="countrie_id" class="form-control">';
			              options = '<option selected="selected">Please select</option>';
			              $.each(provincesjson, function(index, value) {
			                  options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';
			              });
			              closeSelect = '</select>';

			              selectHtml = openSelect + options + closeSelect;

			              $( "#provincelist" ).html( selectHtml );
			          }
            			
    			});




    		}



    	});

    	$('#provincelist').click(function(){
    		listCities();
    		function listCities($provinceid){

    			var provinceId = $('#provincelist').val();

    			$.ajax({
    				type: 'GET',
    				dataType: 'json',
    				contentType: "application/json",
    				url: "{{url('lib_getcities/" + provinceId +"');}}",
    				success: function(cities) {
			              var citiesjson = JSON.parse(cities); 
			              console.log("Cities: " + cities);

			              openSelect = '<select id="citieslist" name="cities_id" class="form-control">';
			              options = '<option selected="selected">Please select</option>';
			              $.each(citiesjson, function(index, value) {
			                  options = options + '<option value="' + value["id"] + '">' + value["name"] + '</option>';

			              });
			              closeSelect = '</select>';

			              selectHtml = openSelect + options + closeSelect;

			              $( "#citieslist" ).html( selectHtml );
			          }
            			
    			});

    		}
    	});

    	$('#citieslist').click(function(){
    		listVenues();
    	
    	function listVenues() {
    		var brandid = {{ $data['brandid'];}};
    		var citieid = $('#citieslist').val();

    		$.ajax({
    			type: 'GET',
    			dataType: 'json',
    			url: "{{url('lib_getvenues/');}}",
    			data: {
    				"brand_id": brandid,
    				"citie_id": citieid,
    			},
    			success: function(venues){
    				var venuesjson = JSON.parse(venues); 
    				openSelect = '<select id="venueslist" name="venue_id" class="form-control">';
    				options = '<option selected="selected"> Please select</option>';
    				$.each(venuesjson, function(index, value){
    					options = options + '<option value="' + value["id"] + '">' + value["sitename"] + '</option>';
    				});
    				closeSelect = '</select>';
    				selectHtml = openSelect + options + closeSelect;

    				$('#venueslist').html(selectHtml);

    			}
    		});
    	}
    });

   
    
    $('#imagecheck').click(function(){
    	//$('#imageorvideo').hide();
    	if (this.checked){
	 	$('#videocheck').prop('checked',false);
	 	$('#imageorvideoheading').text('Upload Image');
	    	$('#imageorvideo').show();
    	}
    	else{
    		$('#imageorvideo').hide();
    	}
    
});
     $('#videocheck').click(function(){
     	if(this.checked){
	     	$('#imagecheck').prop('checked',false);
	 	$('#imageorvideoheading').text('Upload Video');
	    	$('#imageorvideo').show();
    	}
    	else{
    		$('#imageorvideo').hide();
    	}
    
});

     $('#target').click(function(){
     	if(this.checked){
     		$('#targetting').show();
     		$('#provincelist').attr('required', true);
     		$('#citieslist').attr('required', true);
     		$('#venueslist').attr('required', true);
     	}
   	else{
   		$('#targetting').hide();
     		$('#provincelist').removeAttr('required');
     		$('#citieslist').removeAttr('required');
     		$('#venueslist').removeAttr('required');
   	}
     	
     	});
     	

    


    </script>
   
</body>


@endsection