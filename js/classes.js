Class('DOMElement', {
  'private element_': null,
  '[constructor]': function __construct (type) {
    if (type === undefined) {
      console.warn('[DOMElement] Must specify a type');
      return null;
    }
    element_ = jq_element(type);
  },
  'public get element': function get_element () {
    return element_;
  },
  'public set element': function set_element (new_element) {
    console.warn('[DOMElement] Element immutable');
    return null;
  },
  toString: function toString() {
    return element;
  }
});

Class('Poll', {
  'private structure': null,
  '[constructor]': function __construct (_options) {
    uber('form');
    var options = $.extend({
      id: null,
      url: null,
      method: 'GET',
      fields: {},
      title: null,
      subtitle: null
    }, _options);
    var key;

    structure = {
      wrapper: element,
      title: options.title ? jq_element('h3').html(options.title) : null,
      subtitle: options.subtitle ? jq_element('h3').html(options.subtitle) : null,
      id: options.id ? jq_element('input').attr({name: 'id', type: 'hidden'}).val(options.id) : null,
      poll: jq_element('table'),
      submit: jq_element('input').attr({type:'submit', value:'Vote'})
    };

    for (key in options.fields) {
      options.fields[key] = parse_input(key, options.fields[key]);
    }

    for (key in options.fields) {
      if (!Input.hasInstance(options.fields[key])) {
        console.warn('[Poll] Not an instance of Input', options.fields[key]);
        continue;
      }
      structure.poll.append(
        jq_element('tr').append(
          jq_element('td').html(key),
          options.fields[key].element
        )
      );
    }

    structure.wrapper.
      attr({
        action: options.url,
        method: options.method,
        'class': 'poll'
      }).append(
        structure.title,
        structure.subtitle,
        structure.id,
        structure.poll,
        structure.submit
      );
  },
  parse_input: function parse_input (label, data) {
    if (!data) {
      return null;
    }
    if (Input.hasInstance(data)) {
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
}, DOMElement);

Class('Input', {
  '[constructor]': function __construct (name, attributes) {
    uber('input');
    attributes = attributes || {};
    element.attr({
      name: name
    }).attr(attributes);
  },
  val: function val (new_value) {
    if (new_value !== undefined) {
      this.value = new_value;
      return this;
    } else {
      return this.value;
    }
  },
  'public set value': function set_value (new_value) {
    element.val(new_value);
  },
  'public get value': function get_value () {
    return element.val();
  }
}, DOMElement);

Class('CheckboxInput', {
  '[constructor]': function __construct (name, attributes) {
    attributes = attributes || {};
    attributes.type = 'checkbox';
    uber(name, attributes);
    element.on('change', function on_change () {
      this.value = !!$(this).attr('checked');
    });
    value = !!attributes.checked;
  },
  'public set value': function set_value (new_value) {
    var val = !!new_value;
    element.attr({
      checked: val,
      value: val
    });
  },
  'public get value': function get_value () {
    return !!element.attr('checked');
  }
}, Input);