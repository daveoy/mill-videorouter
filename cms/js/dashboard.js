$(document).ready(function()
{
	// default page setup
	init();

	// bind nav buttons
	$(".input-nav").click(function() {
	 	showInput();
	});

	$(".output-nav").click(function() {
	  showOutput();
	});

});

// default 
function init(jQuery) 
{
    // show input
	$("#inputs").css("display", "block");
	$(".input-nav").addClass("active");


	// hide output
	$("#outputs").css("display", "none");
	$(".output-nav").removeClass("active");
}

function showInput(jQuery)
{
	// show input
	$("#inputs").css("display", "block");
	$(".input-nav").addClass("active");


	// hide output
	$("#outputs").css("display", "none");
	$(".output-nav").removeClass("active");
}

function showOutput(jQuery)
{
	// show input
	$("#outputs").css("display", "block");
	$(".output-nav").addClass("active");


	// hide output
	$("#inputs").css("display", "none");
	$(".input-nav").removeClass("active");
}