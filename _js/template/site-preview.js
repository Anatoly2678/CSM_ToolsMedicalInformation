$(document).ready(function(){
	var previewMenu = function () {
		var menu = $('.preview-menu'),
			menuItems = menu.find('.menu-item'),
			preview = $('#preview');

		menu.on('click', '.menu-item', function() {
			var menuItem = $(this),
				className = menuItem[0].className;
			$('body')[0].className = className.replace('menu-item ', '');
			menuItems.removeClass('active');
			menuItem.addClass('active');
		});
	}

	previewMenu();

});