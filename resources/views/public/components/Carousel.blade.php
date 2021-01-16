<div id="carouselExampleInterval" class="carousel slide carousel-fade" data-ride="carousel">
	<div class="carousel-inner">

		@foreach ($ads as $ad)
			<div class="carousel-item {{ $ad->is_first }}">
				<img src="{{Storage::url($ad->path)}}" class="d-block w-100" alt="{{ $ad->name }}">
		  	</div>
		@endforeach
	  
	</div>
  </div>