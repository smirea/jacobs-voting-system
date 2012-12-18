var DOMElement = Class.extend({
  element: null,
  init: function __construct (type) {
    if (type === undefined) {
      console.warn('[DOMElement] Must specify a type');
      return null;
    }
    this.element = jq_element(type);
  },
  toString: function toString() {
    return this.element;
  }
});

var Poll = DOMElement.extend({
  structure: null,
  init: function __construct (_options) {
    this._super('form');
    var options = $.extend({
      id: null,
      url: null,
      method: 'GET',
      fields: {},
      title: null,
      subtitle: null
    }, _options);

    var key;

    this.structure = {
      wrapper: this.element,
      title: options.title ? jq_element('h3').html(options.title) : null,
      subtitle: options.subtitle ? jq_element('h4').html(options.subtitle) : null,
      id: options.id ? jq_element('input').attr({name: 'id', type: 'hidden'}).val(options.id) : null,
      poll: jq_element('table'),
      submit: jq_element('input').attr({type:'submit', value:'Vote'})
    };

    for (key in options.fields) {
      options.fields[key] = this.parse_input(key, options.fields[key]);
    }

    for (key in options.fields) {
      if (!(options.fields[key] instanceof Input)) {
        console.warn('[Poll] Not an instance of Input', options.fields[key]);
        continue;
      }
      this.structure.poll.append(
        jq_element('tr').append(
          jq_element('th').html(key),
          jq_element('td').append(options.fields[key].element)
        )
      );
    }

    this.structure.wrapper.
      attr({
        action: options.url,
        method: options.method,
        'class': 'poll'
      }).append(
        this.structure.title,
        this.structure.subtitle,
        this.structure.id,
        this.structure.poll,
        this.structure.submit
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
  }
});

var Input = DOMElement.extend({
  init: function __construct (name, attributes) {
    this._super('input');
    attributes = attributes || {};
    this.element.attr({
      name: name
    }).attr(attributes);
  },
  val: function val (new_value) {
    if (new_value === undefined) {
      return this.value;
    }
    this.element.val(new_value);
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
      return !!this.element.attr('checked');
    }
    var val = !!new_value;
    this.element.attr({
      checked: val,
      value: val
    });
    return this;
  }
});

var RadioInput = CheckboxInput.extend({
  init: function __construct (name, attributes) {
    this._super(name, attributes);
    this.element.attr('type', 'radio');
  },
  val: function val (new_value) {
    if (new_value === undefined) {
      return !!this.element.attr('checked');
    }
    this.element.attr({
      checked: !!new_value
    });
  }
});