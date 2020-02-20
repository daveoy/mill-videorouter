Namespace('App.Facade', (function(){

	return {
		
		ArcLoadingGraphic: Backbone.View.extend({
 			
 
			initialize:function(_opt){

				this.el = document.createElement('div');
				this.width = _opt.width;
				this.height = _opt.height;
				this.strokeWidth = 3;
				this.opacity = 0.8;
				this.stroke = '#00cfbb';

				TweenLite.set(this.el, {autoAlpha:0});


				this.stage = new Kinetic.Stage({
					container: this.el,
					width: this.width,
					height: this.height
				});

				var layer = new Kinetic.Layer();

		 

				var arc = new Kinetic.Arc({
					x: this.width / 2,
					y: this.height / 2,
					innerRadius: this.width/2 - this.strokeWidth/2,
					outerRadius: this.height/2 - this.strokeWidth/2,
					stroke: this.stroke,
					strokeWidth: this.strokeWidth,
					angle: 0,
					opacity:this.opacity,
					rotationDeg: -90 
				});


				layer.add(arc);
				this.stage.add(layer);


				this.anim = new Kinetic.Animation(function(frame) {

					var target_angle = arc.attrs.angle + 8;

					if(target_angle < 360){
						arc.attrs.angle = target_angle;
					}else{
						if(arc.clockwise()){
							arc.clockwise(false);
						}else{
							arc.clockwise(true);
						}

						arc.attrs.angle = 0.01;

					}

				}, layer);

				
	 			this.start();
			},

			render:function(){
				return this.el;
			},

			start:function(){
				TweenLite.to(this.el, 0.3, {autoAlpha:1});
				this.anim.start();
 
			},	

			end:function(_opt){
				var opt = _opt || {};
				TweenLite.to(this.el, 0.3, {autoAlpha:0, onComplete:_.bind(function(){
					this.anim.stop();
				}, this)});
				

				if(opt.onComplete) opt.onComplete.call(this);
			}
 
		})
	}
	
})());



