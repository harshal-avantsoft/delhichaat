function wcas_startTimer(duration, display, callback) 
{
    var timer = duration, hours, minutes, seconds, count;
	count = duration;
    counter = setInterval(function () 
	{
        var seconds = timer % 60;
		var minutes = Math.floor(timer / 60);
		var hours = Math.floor(minutes / 60);
		minutes %= 60;
		hours %= 60;
		count --;
		
		hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = hours + ":" + minutes + ":" + seconds;

        if (--timer < 0) {
            timer = duration;
        }
		
		if(count == 0 || count <0)
		{
			clearInterval(counter);
			callback();
			return;
		}
		
    }, 1000);	
}
function wcst_mod(n, m) {
        return Math.floor( ((n % m) + m) % m );
}