$(document).ready(function()
{
	//Here we're telling the script what font families we're loading. 
	//When the fonts have loaded, we're going to reload masonry.
	//This fixes issue with extra spacing between masonry items after fonts have loaded. 
	
	WebFontConfig =
	{
		custom:
		{
			families: [ "bebas-neue", "proxima-nova" ]
		},
		active: function()
		{
			adjust_block_heights();
		}
	};
		
	(function()
	{
		var wf = document.createElement('script');
		wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
		wf.type = 'text/javascript';
		wf.async = 'true';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(wf, s);
	})();
});