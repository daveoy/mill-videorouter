Namespace('App.View', {

	SelectorView:App.View.Abstract.extend({

        rendered:false,
        html: document.getElementById('T_SelectorCtrView').text,
        selector_html: document.getElementById('T_SelectorView').text,


        
         
		initialize:function(_opt){

                        App.View.Abstract.prototype.initialize.call(this);


            this.selectorController = _opt.selectorController;
 			this.$ctr = _opt.$ctr;
            this.id = _opt.id;

            this.rendered = false;
            this.ctrArray = [];
            this.btnViews = [];
            this.paneViews = [];

            this.listenTo(this.selectorController, 'render:view', _.bind(this.draw, this, false));
            this.listenTo(this.selectorController, 'update:view', _.bind(this.draw, this, false));
            this.listenTo(this.selectorController, 'update:view:last', _.bind(this.draw, this, true));

            this.listenTo(this.selectorController, 'update:view', _.bind(this.onSelection, this, false));
            this.listenTo(this.selectorController, 'update:view:last', _.bind(this.onSelectionLast, this, true));



            this.listenTo(this.selectorController, 'on:remove:connected:sign', _.bind(this.onRemoveConnectedSign, this));



            this.render();
 
  		},

        render:function(){
            this.$el = $(_.template(this.html, {}));
            this.$path_ctr = $('._path_ctr', this.$el);
            this.$message_ctr = $('._message_ctr', this.$el);
            this.$pane_ctr = $('._pane_ctr', this.$el);
            this.$ctr.append(this.$el);


            this.breadcrumbs = new App.View.BreadcrumbsView({
                selectorController: this.selectorController,
                $ctr: this.$path_ctr
            });

            this.selectorMessageView = new App.View.SelectorMessageView({
                selectorController: this.selectorController,
                $ctr: this.$message_ctr
            });


         },
        
    

        onSelection:function(){
            this.$el.removeClass('black');
        },

        onSelectionLast:function(){
            this.$el.addClass('black');
        },

        onRemoveConnectedSign:function(){
            if(this.paneViews.length == 0){
                this.addQueue(_.bind(this.removeConnectedSign, this));
            }else{
                this.removeConnectedSign();
            }
        },

        removeConnectedSign:function(){
            console.log("removeConnectedSign");
            _.each(this.paneViews, _.bind(function(_v){
                _v.removeConnectedSign();
            }, this));
        },

        draw:function(_last_boo, _data, _direction){



            if(this.ctrArray.length > 0){

                var z_pos = (_direction == 'in') ? 5 : -5;

                TweenLite.to(this.ctrArray[0], 0.2, {z:z_pos, opacity:0, onComplete: _.bind(this.onPreviousFadeout, this, _last_boo, _data, _direction)});
 
            }else{

                this.resetViews();

                var $ctr = $(_.template(this.selector_html, {}));
                this.$pane_ctr.append($ctr);


                if(_last_boo){

                    var pane = new App.View.SingleItemView({
                        id: this.id,
                        selectorController: this.selectorController,
                        model: _data
                    });

                    $ctr.append(pane.render());
                    pane.init();

                    this.paneViews.push(pane);
                    this.runQueue();

                    this.ctrArray.push($ctr);

                    var z_pos = (_direction == 'in') ? -5 : 5;

                    TweenLite.set($ctr, {transformPerspective:100, transformOrigin:"50% 50%", z:z_pos, opacity:0});
                    TweenLite.to($ctr, 0.3, {z:0, opacity:1});

                }else{

                    var btnClass;
                    var count = _.size(_data.data);
                    if(count <= 6) btnClass = 'two';
                    if(count > 6 && count <= 12 ) btnClass = 'three';
                    if(count > 12) btnClass = 'three';

                       _.each(_data.data, _.bind(function(_d, _key){
                       // console.log(_d);
                           var btn = new App.View.SingleBtnView({
                            groupClass: _d.class_name,
                            btnClass: btnClass,
                            selectorController: this.selectorController,
                            data: _d,
                            key: _key
                        });
                        $ctr.append(btn.render());


                        this.btnViews.push(btn);

                    }, this));

                    this.ctrArray.push($ctr);

                    var z_pos = (_direction == 'in') ? -5 : 5;

                    TweenLite.set($ctr, {transformPerspective:100, transformOrigin:"50% 50%", z:z_pos, opacity:0});
                    TweenLite.to($ctr, 0.3, {z:0, opacity:1});

                }
            }
            

        },


        resetViews:function(){
            _.each(this.btnViews, function(_v){_v.destroy();});
            _.each(this.paneViews, function(_v){_v.remove();});

            this.btnViews = [];
            this.paneViews = [];
        },

        onPreviousFadeout:function(_last_boo, _data, _direction){
            this.ctrArray[0].remove();
            this.ctrArray.shift();
            this.draw(_last_boo, _data, _direction);
        } 
		

		
	})

});