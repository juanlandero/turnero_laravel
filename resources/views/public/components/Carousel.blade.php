<div id="carouselExampleInterval" class="carousel slide carousel-fade" data-ride="carousel">
	<div class="carousel-inner">

		@foreach ($ads as $ad)
			<div class="carousel-item {{ $ad->file_name }}">
				<img src="{{ asset($ad->file_url) }}" class="d-block w-100" alt="...">
		  	</div>
		@endforeach
	  
	</div>
  </div>