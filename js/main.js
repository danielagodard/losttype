function $m(t){return document.getElementById(t);}
function checkField(field){
	if(field.innerHTML==''){
		$m('submit_btn').style.display="none";
	}else if(field.innerHTML=='<br>'){			//FireFox
		field.innerHTML='';
		$m('submit_btn').style.display="none";
	}else if(field.innerHTML=='<p>&nbsp;</p>'){	//Internet Explorer 9
		field.innerHTML='';
		$m('submit_btn').style.display="none";
	}else{
		$m('submit_btn').style.display="inline";
	}
	$m('msg').value=field.innerHTML;
	$m('rawmsg').value=field.textContent;
}

$m('console').addEventListener("paste",function(e){
    e.preventDefault();
	var text=e.clipboardData.getData("text/plain");
	document.execCommand("insertHTML",false,text);
	$m('submit_btn').style.display="inline";
});

(function(){
    var FX={
        easing:{
            linear:function(progress){return progress;},
            quadratic:function(progress){return Math.pow(progress,2);},
            swing:function(progress){return 0.5-Math.cos(progress*Math.PI)/2;},
            circ:function(progress){return 1-Math.sin(Math.acos(progress));},
            back:function(progress,x){return Math.pow(progress,2)*((x+1)*progress-x);},
            bounce:function(progress){for(var a=0,b=1,result;1;a+=b,b/=2){if(progress>=(7-4*a)/11){return -Math.pow((11-6*a-11*progress)/4,2)+Math.pow(b,2);}}},
            elastic:function(progress,x){return Math.pow(2,10*(progress-1))*Math.cos(20*Math.PI*x/3*progress);}
        },
        animate:function(options){
            var start=new Date;
            var id=setInterval(function(){
                var timePassed=new Date-start;
                var progress=timePassed/options.duration;
                if(progress>1){progress=1;}
                options.progress=progress;
                var delta=options.delta(progress);
                options.step(delta);
                if(progress==1){clearInterval(id);options.complete();}
            }, options.delay||10);
        },
        fadeOut:function(element,options){
            var to=1;
            this.animate({
                duration:options.duration,
                delta:function(progress){progress=this.progress;return FX.easing.swing(progress);},
                complete: options.complete,
                step:function(delta){element.style.opacity=to-delta;}
            });
        }
    };
    window.FX=FX;
})()

