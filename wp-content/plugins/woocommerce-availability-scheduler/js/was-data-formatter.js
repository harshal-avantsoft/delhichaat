function wcsa_data_formatter(d)
{
	var dformat = [ d.getFullYear(),
				   (d.getMonth()+1).padLeft(),
                    d.getDate().padLeft(),
                    ].join('/')+
                    ' ' +
                  [ d.getHours().padLeft(),
                    d.getMinutes().padLeft(),
                    d.getSeconds().padLeft()].join(':');
					
	return dformat;
}
Number.prototype.padLeft = function(base,chr){
   var  len = (String(base || 10).length - String(this).length)+1;
   return len > 0? new Array(len).join(chr || '0')+this : this;
}