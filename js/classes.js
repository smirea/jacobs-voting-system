var JQueryElement = Class.extend(
  Object.create($.fn, {
    length: {
      configurable: true,
      writeable: true,
      value: 0
    },
    constructor: {
      configurable: true,
      value: JQueryElement
    },
    init: {
      writeable: true,
      configurable: true,
      value: function init (type) {
        if (!(this instanceof JQueryElement)) {
          console.log('not an instance', arguments);
          return new JQueryElement(); // fix wrong constructor invocations
        }
        if (this.length) { // shouldn't be set yet, so another odd invocation:
          console.log('has a length', this, arguments);
          var empty = Object.create(JQueryElement.prototype); // a new, but *empty* instance
          empty.length = 0; // explicitly set length
          return empty;
        }
        this.context = undefined; // not sure whether
        this.selector = ""; // we need those two lines
        this.length = 1; // but the length is obligatory
        this[0] = document.createElement(type);
        this.data('instance', this);
      }
    }
  })
);

var Poll = JQueryElement.extend({
  structure: null,
  init: function __construct (_options) {
    var that = this;
    var options = $.extend({
      id: null,
      url: null,
      method: 'GET',
      fields: {},
      title: null,
      subtitle: null
    }, _options);

    var key;

    that._super('form');

    that.structure = {
      title: options.title ? jq_element('h3').html(options.title) : null,
      subtitle: options.subtitle ? jq_element('h4').html(options.subtitle) : null,
      id: options.id ? jq_element('input').attr({name: 'id', type: 'hidden'}).val(options.id) : null,
      poll: jq_element('table'),
      fields: options.fields,
      submit: jq_element('input').attr({type:'submit', value:'Vote'}),
      revote: jq_element('input').attr({type:'reset', value:'Re-Vote :)'}),
    };

    for (key in options.fields) {
      that.structure.fields[key] = that.parse_input(key, that.structure.fields[key]);
      that.structure.fields[key].addClass('poll-option');
    }

    for (key in that.structure.fields) {
      if (!(that.structure.fields[key] instanceof Input)) {
        console.warn('[Poll] Not an instance of Input', that.structure.fields[key]);
        continue;
      }
      that.structure.poll.append(
        jq_element('tr').append(
          jq_element('th').html(key),
          jq_element('td').
            attr('field-key', key).
            append(that.structure.fields[key])
        )
      );
    }

    that.structure.revote.
      css('margin-left', 15).
      on('click.show_poll', function on_click_show_poll(){ that.show_poll(); });

    that.attr({
        action: options.url,
        method: options.method,
        'class': 'poll'
      }).append(
        that.structure.title,
        that.structure.subtitle,
        that.structure.id,
        that.structure.poll,
        that.structure.submit,
        that.structure.revote
      );
  },
  parse_input: function parse_input (label, data) {
    if (!data) {
      return null;
    }
    if (data instanceof Input) {
      return data;
    }
    if (data.constructor === Object) {

    }
    if (typeof data == 'string') {
      switch (data) {
        case 'checkbox':
          return new CheckboxInput('options['+label+']');
        default:
          return null;
      }
    }
    return null;
  },
  show_poll: function show_poll () {
    for (var key in this.structure.fields) {
      $(this.structure.fields[key]).parent().find('.progress').remove();
      this.structure.fields[key].show();
    }
  },
  show_results: function show_results (result_map) {
    var key;
    var total = 0;
    $.map(result_map, function(value, key) { total += value; });
    for (key in this.structure.fields) {
      var value = result_map[this.structure.fields[key].attr('name')] || 0;
      $(this.structure.fields[key]).
        parent().
        find('.progress').
        remove().
        end().
        append(this.result_bar(value, total));
      this.structure.fields[key].hide();
    }
  },
  result_bar: function result_bar (value, total) {
    return jq_element('div').
              addClass('progress progress-striped active').
              css('min-width', 400).
              append(
                jq_element('div').
                  addClass('bar').
                  css('width', (Math.floor(value/total*100)) + '%')
              )
  }
});

var Input = JQueryElement.extend({
  init: function __construct (name, attributes) {
    this._super('input');
    attributes = attributes || {};
    this.attr({
      name: name
    }).attr(attributes);
  },
  val: function val (new_value) {
    if (new_value === undefined) {
      return this.value;
    }
    this.val(new_value);
    return this;
  }
});

var CheckboxInput = Input.extend({
  init: function __construct (name, attributes) {
    attributes = attributes || {};
    attributes.type = 'checkbox';
    this._super(name, attributes);
  },
  val: function val (new_value) {
    if (new_value === undefined) {
      return !!this.attr('checked');
    }
    var val = !!new_value;
    this.attr({
      checked: val,
      value: val
    });
    return this;
  }
});

var RadioInput = CheckboxInput.extend({
  init: function __construct (name, attributes) {
    this._super(name, attributes);
    this.attr('type', 'radio');
  },
  val: function val (new_value) {
    if (new_value === undefined) {
      return !!this.attr('checked');
    }
    this.attr({
      checked: !!new_value
    });
  }
});