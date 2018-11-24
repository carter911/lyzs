(function($){
    $.fn.extend({
        RollTitle: function(opt){
            if(!opt) var opt={};
            var _this = this;
            _this.timer = null;
            _this.lineH = _this.find("p:first").height();
            _this.line=opt.line?parseInt(opt.line,15):parseInt(_this.height()/_this.lineH,10);
            _this.speed=opt.speed,
                _this.timespan=opt.timespan;
            if(_this.line==0) this.line=1;

            _this.scrollUp=function(){
                _this.animate({
                    marginTop:0
                },_this.speed,function(){
                    for(i=1;i<=_this.line;i++){
                        _this.find("p:first").appendTo(_this);
                    }
                    _this.css({marginTop:0});
                });
            }
            _this.hover(function(){
                clearInterval(_this.timer);
            },function(){
                _this.timer=setInterval(function(){_this.scrollUp();},_this.timespan);
            }).mouseout();
        }
    })
})(jQuery);