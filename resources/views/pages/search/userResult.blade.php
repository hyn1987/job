<div class="result-box">
	<div class="result">
		<div class="search_section margin-bottom-10px">
		  <div id="content-box" >
		    @foreach ($users as $user)
		      @include ('pages.search.userInfo')
		    @endforeach
		  </div>  
		</div>  
	</div>
</div>