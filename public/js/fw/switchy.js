!function ($) {

  "use strict"; // jshint ;_;


 /* SWITCHY CLASS DEFINITION
  * ====================== */

  var Switchy = function (element, options) {
    this.options = options;
    this.$element = $(element);
    this.$container = $("<div class='switchy-container'></div>");
    this.$bar = $("<div class='switchy-bar'></div>");
    this.$slider = $("<div class='switchy-slider' draggable='true'></div>");
    this.$options = $(element).children('option');
    this.numberOfOptions = this.$options.length;
    this.initialOptionIndex = this.$options.filter('[value="'+$(element).val()+'"]').index(); 
    this.init();
  }

  Switchy.prototype = {
    constructor: Switchy,

    lastSliderPosition: null,

    init: function(){
      var that = this;
      // hide original select
      this.$element.css({ position: 'absolute', top: '-9999px' })
      // Prepare the slider for the DOM
      this.$container.append(this.$bar.append(this.$slider));
      // Append the slider to the DOM
      this.$element.after(this.$container);

      this.lastSliderPosition = this.initialOptionIndex;
      var barGrid = this.$bar.innerHeight() / (this.numberOfOptions - 1);
	
      // Position slider to initial value
      this.$slider.css({
        top: that.sliderPosition(barGrid, this.initialOptionIndex)
      });

      // When original select is updated
      this.$element.on('change', function(e){
        var nextOptionIndex = that.$options.filter('[value="'+that.$element.val()+'"]').index();

        if (that.lastSliderPosition != nextOptionIndex){
          that.moveSliderTo(barGrid, nextOptionIndex, false);
        }
      });

      if (this.$slider.drag != undefined && this.options.draggable == true){
        this.$slider.
          drag('end', function(ev, dd){
            var currentSliderPosition = that.$slider.position().top + (that.$slider.outerHeight(true) / 2),
                currentOptionIndex = Math.round(currentSliderPosition / barGrid);
            
            that.moveSliderTo(barGrid, currentOptionIndex, true);
          }).
          drag(function(ev, dd){
            var limit = {
              top: 0,
              bottom: that.$bar.innerHeight() - that.$slider.outerHeight(true)
            }
            $(this).css({
              top: Math.min(limit.bottom, Math.max(limit.top, dd.offsetY))
            });
          }, { relative: true });
      }

      this.$bar.on('click', function(e){
        var currentSliderPosition = that.$slider.position().top,
            currentOptionIndex = Math.ceil(currentSliderPosition / barGrid),
            clickPosition = e.pageY - that.$bar.offset().top,
            nextOptionIndex = Math.round(clickPosition / barGrid);

        if (currentOptionIndex != nextOptionIndex){
          // move slider position
          that.moveSliderTo(barGrid, nextOptionIndex, true);
        }
      });
    },

    sliderPosition: function(barGrid, optionIndex){
      var add = null;

      if (optionIndex == 0){
        add = 0;
      } else if (optionIndex == this.numberOfOptions - 1){
        add = -(this.$slider.outerHeight(true));
      } else {
        add = -(this.$slider.outerHeight(true) / 2);
      }
      return (barGrid * optionIndex) + add;
    },

    moveSliderTo: function(barGrid, nextOptionIndex, triggerChange){
      var topPosition = this.sliderPosition(barGrid, nextOptionIndex)
      // move slider position
      if (topPosition != null){
        this.$slider.animate({
          top: topPosition
        }, "fast");
      }
      // update original select value
      this.$options.removeAttr('selected');
      this.$options.eq(nextOptionIndex).prop('selected', 'selected');
      if (triggerChange == true)
        this.$element.trigger('change');
      this.lastSliderPosition = nextOptionIndex;
    }
  }

  /* SWITCHY PLUGIN DEFINITION
   * ======================= */

  $.fn.switchy = function (option) {
    return this.each(function () {
      var $this = $(this),
        options = $.extend({}, $.fn.switchy.defaults, typeof option == 'object' && option);
      new Switchy(this, options);
    })
  }

  $.fn.switchy.defaults = {
    draggable: true
  }

  $.fn.switchy.Constructor = Switchy
}(window.jQuery);