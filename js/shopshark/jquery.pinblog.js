jQuery(function($){
	var $container = $('#blogPostsWrapper');
	$container.imagesLoaded(function(){ $container.masonry({itemSelector: '.postWrapper'}); });
	$container.infinitescroll({
		  navSelector: '.toolbar',
		  nextSelector: '.toolbar a.next',
		  itemSelector: '.postWrapper',
		  maxPage: $totalPages,
		  loading: { msgText: $msgText, finishedMsg: $finishedMsg, img: $loadingImg }
	  },
	  function(newElements) {
		var $newElems = $(newElements).css({opacity: 0});
		$newElems.imagesLoaded(function(){
		  $newElems.animate({opacity: 1});
		  $container.masonry('appended', $newElems, true);
		});
	  }
	);
});