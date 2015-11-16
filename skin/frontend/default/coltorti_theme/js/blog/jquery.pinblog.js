jQuery(function($){
	var $container = $('#blogPostsWrapper');
	$container.infinitescroll({
		  navSelector: '.toolbar',
		  nextSelector: '.toolbar a.next',
		  itemSelector: '.postWrapper',
		  maxPage: $totalPages,
		  loading: { msgText: $msgText, finishedMsg: $finishedMsg, img: $loadingImg }
	  }
	);
});