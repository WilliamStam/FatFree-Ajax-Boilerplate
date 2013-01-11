/* =============================================================
 * bootstrap-typeahead.js v2.0.1
 * http://twitter.github.com/bootstrap/javascript.html#typeahead
 * =============================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================ */

!function( $ ){

  "use strict"

  var Typeahead = function ( element, options ) {
    this.$element = $(element)
    this.options = $.extend({}, $.fn.typeahead.defaults, options)
    this.onSelect = this.options.select
    this.matcher = this.options.matcher || this.matcher
    this.sorter = this.options.sorter || this.sorter
    this.highlighter = this.options.highlighter || this.highlighter
    this.$menu = $(this.options.menu).appendTo('body')
	this.$list = (this.$menu.hasClass("typeahead")) ? this.$menu : this.$menu.find(".typeahead")
    this.shown = false
    this.objectSource = true
    if (this.options.source.length > 0 && typeof this.options.source[0] != 'object') {
      this.objectSource = false
      this.options.source = this.normalizeItems(this.options.source)
    }
    this.source = this.options.source
    this.listen()
  }

  Typeahead.prototype = {

    constructor: Typeahead


  , select: function () {
      var item = this.$list.find('.active').data('item')

      this.$element.val(item.value?item.value:item.label)
      this.$element.trigger('selected')
		  this.onSelect.call(this, item);
		 // this.onSelect()
      return this.hide()
    }

  , show: function () {
      var pos = $.extend({}, this.$element.offset(), {
        height: this.$element[0].offsetHeight
      })

      this.$menu.css({
        top: pos.top + pos.height
      , left: pos.left
      })

      this.$menu.show()
      this.shown = true
      return this
    }

  , hide: function () {
      this.$menu.hide()
      this.shown = false
      return this
    }

  , lookup: function (event) {
      var that = this
        , items
        , q

      this.query = this.$element.val()

      if (!this.query) {
        return this.shown ? this.hide() : this
      }


      items = $.grep(this.source, function (item) {

        if (that.matcher(item)) return item
      })

      items = this.sorter(this.objectSource ? items : this.labelsFor(items))
      if (!this.objectSource) items = this.normalizeItems(items)

      if (!items.length) {
        return this.shown ? this.hide() : this
      }

      return this.render(items.slice(0, this.options.items)).show()
    }

  , matcher: function (item) {

		 var query = this.query.toLowerCase();
		  if (this.objectSource){
		  var ret = false;
		  $.each(item, function (index, value) {

			  if (typeof(value) == 'string'){
				  if (~value.toLowerCase().indexOf(query)) ret = true
			  }


		  })
		  return ret
		  } else {
			  return ~item.toLowerCase().indexOf(query)
		  }


    }

  , sorter: function (items) {
      var beginswith = []
        , caseSensitive = []
        , caseInsensitive = []
        , item
        , label

      while (item = items.shift()) {
        label = this.objectSource ? item.label : item
        if (!label.toLowerCase().indexOf(this.query.toLowerCase())) beginswith.push(item)
        else if (~label.indexOf(this.query)) caseSensitive.push(item)
        else caseInsensitive.push(item)
      }

      return beginswith.concat(caseSensitive, caseInsensitive)
    }

  , highlighter: function (value) {

      return value.replace(new RegExp('(' + this.query + ')', 'ig'), function ($1, match) {
        return '<strong>' + match + '</strong>'
      })
    }

  , render: function (items) {
      var that = this

      items = $(items).map(function (i, item) {
        i = $(that.options.item).attr('data-value', item.label).data('item', item)


	    var rend = i.html()
	      $.each(item,function(index,value){

		      rend = rend.replace("{" + index + "}", (typeof(value) == 'string')?that.highlighter(value):value)
	      })


	      i.html(rend)

        return i[0]
      })

      items.first().addClass('active')

	this.$list.html(items)


      return this
    }

  , next: function (event) {
      var active = this.$list.find('.active').removeClass('active')
        , next = active.next()
      if (!next.length) {
		next = $(active.parent().children()).first()

      }
      $(next).addClass('active')
    }

  , prev: function (event) {
      var active = this.$list.find('.active').removeClass('active')
        , prev = active.prev()

      if (!prev.length) {
        prev = $(active.parent().children()).last()
      }

      prev.addClass('active')
    }

  , listen: function () {
      this.$element
        .on('focus',     $.proxy(this.focus, this))
        .on('blur',     $.proxy(this.blur, this))
        .on('keypress', $.proxy(this.keypress, this))
        .on('keyup',    $.proxy(this.keyup, this))

      if ($.browser.webkit || $.browser.msie) {
        this.$element.on('keydown', $.proxy(this.keypress, this))
      }

      this.$list
        .on('click', $.proxy(this.click, this))
        .on('mouseenter', 'tr,li', $.proxy(this.mouseenter, this))
    }

  , keyup: function (e) {
      e.stopPropagation()
		  if (e.keyCode!=9)e.preventDefault();


      switch(e.keyCode) {
        case 40: // down arrow
        case 38: // up arrow
          break

        //case 9: // tab
        case 13: // enter
          if (!this.shown) return
          this.select()
          break

        case 27: // escape
          this.hide()
          break

        default:
          this.lookup()
      }

  }

  , keypress: function (e) {
      e.stopPropagation()
      if (!this.shown) return

      switch(e.keyCode) {
       // case 9: // tab
        case 13: // enter
        case 27: // escape
          e.preventDefault()
          break

        case 38: // up arrow
	 if (e.type != 'keydown') break
          e.preventDefault()
          this.prev()
          break

        case 40: // down arrow
	 if (e.type != 'keydown') break
          e.preventDefault()
          this.next()
          break
      }
    }

  , blur: function (e) {
      var that = this
      e.stopPropagation()
      e.preventDefault()
      setTimeout(function () { that.hide() }, 150)
    }

  , click: function (e) {
      e.stopPropagation()
      e.preventDefault()
      this.select()
    }

  , mouseenter: function (e) {
      this.$list.find('.active').removeClass('active')
      $(e.currentTarget).addClass('active')
    }
, focus: function(e) {
		  var items = $(this.$list.find('.active').parent().children()).length
		  if (items) this.show();
	  }
  , normalizeItems: function(items) {
      return $.makeArray($(items).map(function (i, item) {
        return { label: item, value: item }
      }))
    }

  , labelsFor: function(items) {
      return $.makeArray($(items).map(function (i, item) {
        return item.label
      }))
    }

  }


  /* TYPEAHEAD PLUGIN DEFINITION
   * =========================== */

  $.fn.typeahead = function ( option ) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('typeahead')
        , options = typeof option == 'object' && option
      if (!data) $this.data('typeahead', (data = new Typeahead(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.typeahead.defaults = {
    source: []
  , items: 8
  , menu: '<ul class="typeahead dropdown-menu"></ul>'
  , item: '<li><a href="#">{label}</a></li>'
  , select: function(){}

  }

  $.fn.typeahead.Constructor = Typeahead


 /* TYPEAHEAD DATA-API
  * ================== */

  $(function () {
    $('body').on('focus.typeahead.data-api', '[data-provide="typeahead"]', function (e) {
      var $this = $(this)
      if ($this.data('typeahead')) return
      e.preventDefault()
      $this.typeahead($this.data())
    })
  })

}( window.jQuery )
