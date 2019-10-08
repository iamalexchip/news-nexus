<!-- partial.scripts -->
<script>
$( document ).ready(function() {

	$('.carousel').carousel();
	$('#news-slider').on('slid.bs.carousel', function () {
		let current = $('.carousel-item.active').attr('slide');
		$('.carousel-thumbnails li').removeClass('active');
		$('[data-slide-to="'+current+'"]').addClass('active');
	})

});
</script>
<!-- end partial.scripts -->