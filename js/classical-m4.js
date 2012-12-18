/* 
 * Classical | Classes for JavaScript
 *
 * Copyright (c) 2008-2009 Tim Cameron Ryan
 * Released under the MIT/X License
 *
 */
 
(function () {
  /*
   * utility functions
   */
   
  function iterate(obj, callback)
  {
    // evaluate all defined properties
    for (var prop in obj)
      if (Object.prototype.hasOwnProperty.call(obj, prop))
        callback(obj[prop], prop);
  
    // IE has dontEnum issues
    /*@cc_on
    var dontenums = 'constructor|toString|valueOf|toLocaleString|isPrototypeOf|propertyIsEnumerable|hasOwnProperty'.split('|');
    for (var prop; prop = dontenums.pop(); )
      if (Object.prototype.hasOwnProperty.call(obj, prop) && !Object.prototype.propertyIsEnumerable.call(obj, prop))
        callback(obj[prop], prop);
    @*/
  }
  
  /*
   * VBScript sandbox
   */
   
  function VBScriptSandbox()
  {   
    // create an iframe sandbox (in head, as not to pollute the body)
    var frame = document.createElement('iframe');
    frame.style.display = 'none';
    document.getElementsByTagName('head')[0].appendChild(frame);
    
    // get variables
    this.global = frame.contentWindow;
    // write document
    this.global.document.write(
'<html><head><title>VBScript Sandbox</title><script type="text/vbscript">\
Class TypeProxy\n\
  Public value\n\
  Public Function load(val)\n\
    If IsObject(val) Then\n\
      Set value = val\n\
    Else\n\
      value = val\n\
    End If\n\
  End Function\n\
End Class\n\
Function execute(code)\n\
  ExecuteGlobal(code)\n\
End Function\n\
Function evaluate(code)\n\
  Dim proxy\n\
  Set proxy = new TypeProxy\n\
  proxy.load(Eval(code))\n\
  If IsObject(proxy.value) Then\n\
    Set evaluate = proxy.value\n\
  Else\n\
    evaluate = proxy.value\n\
  End If\n\
End Function\n\
</script></head><body></body></html>');
    this.global.document.close();
    
    // evaluation functions
    this.evaluate = function (code) { return this.global.evaluate(code); }
    this.execute = function (code) { this.global.execute(code); }
  }
  
  /*
   * defineGetter/Setter scope object
   */
  
  function DefineObjectFactory(definition)
  {
    // generate a new factory
    var Factory = function () { }
    Factory.prototype = definition.properties;
    Factory.definition = definition;
    // generate from scope
    iterate(definition.getters, function (value, prop) {
      Factory.prototype.__defineGetter__(prop, function () { return this[prop + '_get_'](); });
      Factory.prototype[prop + '_get_'] = value;
    });
    iterate(definition.setters, function (value, prop) {
      Factory.prototype.__defineSetter__(prop, function (val) { this[prop + '_set_'](val); });
      Factory.prototype[prop + '_set_'] = value;
    });
    return Factory;
  }
  
  /*
   * vbscript scope object
   */
  
  var VBScriptObjectFactoryID = 0;
  
  function VBScriptObjectFactory(definition)
  {
    // getters/setters need suffixes
    var suffixes = {getters: '_get_', setters: '_set_', properties: ''};
  
    // code to be evaluated
    var code = '', className = 'definition' + VBScriptObjectFactoryID++;
    // generate code from definition
    iterate(definition.properties, function (value, prop) {
      code += 'Public ' + prop + '\n';
    });
    iterate(definition.getters, function (value, prop) {
      code += 'Public ' + prop + suffixes.getters + '\n\
Public Property Get ' + prop + '\n\
  Dim proxy\n\
  Set proxy = new TypeProxy\n\
  proxy.load(' + prop + '_get_(me))\n\
  If IsObject(proxy.value) Then\n\
    Set ' + prop + ' = proxy.value\n\
  Else\n\
    ' + prop + ' = proxy.value\n\
  End If\n\
End Property\n';
    });
    iterate(definition.setters, function (value, prop) {
      code += 'Public ' + prop + suffixes.setters + '\n\
Public Property Let ' + prop + '(val)\n\
  Call ' + prop + '_set_(val)\n\
End Property\n\
Public Property Set ' + prop + '(val)\n\
  Call ' + prop + '_set_(val)\n\
End Property\n';
    });
    
    // cache sandbox generation
    var sandbox = VBScriptObjectFactory.sandbox || (VBScriptObjectFactory.sandbox = new VBScriptSandbox());
    // evaluate class
//[TODO] there has to be a way to detect and notify about bad property names
    sandbox.execute('Class ' + className + '\n' + code + '\nEnd Class');

    var Factory = (function () {
      // create the object
      var obj = sandbox.evaluate('New ' + className);
      // set prototype properties
      iterate(definition, function (props, type) {
        iterate(props, function (value, prop) {
          obj[prop + suffixes[type]] = (typeof value != 'function') ? value :
              function () { return value.apply(obj, arguments); };
        });
      });
      return obj;
    });
    Factory.definition = definition;
    return Factory;
  }

  /*
   * Definition api
   */
  
  // determine a getter/setter profile
//[TODO] support defineProperty
  var ObjectDefinitionFactory;
  if (Object.prototype.__defineGetter__ && Object.prototype.__defineSetter__)
    ObjectDefinitionFactory = DefineObjectFactory;
  else if (/*@cc_on!@*/0)
    ObjectDefinitionFactory = VBScriptObjectFactory;
  else 
    throw new Error('The current browser does not support getters/setters.');

  // class constructor
  window.Definition = function (properties)
  {
    // create a new definition
    var definition = { getters: {}, setters: {}, properties: {} };    
    // parse property defintion
    iterate(properties, function (value, prop) {
      // validate property name
      if (!prop.match(/^([gs]et\s)?[a-zA-Z][\w\$_]*$/))
        throw new Error('Invalid property name "' + prop + '"');
      
      // add property to definition
      definition[prop.match(/^[gs]et\s/) ? prop.substr(0, 1) + 'etters' : 'properties']
          [prop.replace(/^([gs]et\s)?/, '')] = value;
    });
    
    // create object generator
    return new ObjectDefinitionFactory(definition);
  }
  
  /*
   * JavaScript sandbox
   */
   
  function evalInGlobalScope(__code)
  {   
    // do some stuff
    return eval(__code);
  }
  
  /*
   * Class constructor
   */
   
  // create js sandbox  
  window.Class = function Class(classID, properties, Ancestor) {
    // extract constructor, cast
    var magic = {'constructor': function () { uber && uber.apply(this, arguments); }, 'cast': function () { uber ? uber.apply(this, arguments) : null; }};
    if (Object.prototype.hasOwnProperty.call(properties, '[constructor]')) {
      magic.constructor = properties['[constructor]'];
      delete properties['[constructor]'];
    }
    if (Object.prototype.hasOwnProperty.call(properties, '[cast]')) {
      magic.cast = properties['[cast]'];
      delete properties['[cast]'];
    }
  
    // split implementations
    var implementation = {'public': {}, 'private': {}, 'static': {}};
    iterate(properties, function (value, prop) {
      implementation
          [prop.match(/^(private|public|static) /) ? prop.match(/^(private|public|static) /)[1] : 'public']
          [prop.replace(/^(public|private|static) /, '')] = value;
    });
    
    // inheritance
    if (Ancestor) {
      // augment unadded values onto this implementation
      var pDef = Ancestor.implementation['public'], def = implementation['public'];
      iterate(pDef, function (value, prop) {
        if (!Object.prototype.hasOwnProperty.call(def, prop))
          def[prop] = typeof value == 'function' ? null : value;
      });
    }
    // initialize object-specific properties
    implementation['private'].constructor__ = null;
    implementation['public']['get constructor'] = function () { return constructor__; };
    // toString
    if (!Object.prototype.hasOwnProperty.call(implementation['public'], 'toString'))
      implementation['public'].toString = new Function('return "[object ' + classID + ']";');       
    
    // create factories
    var definitions = {'public': new Definition(implementation['public']), 'private': new Definition(implementation['private'])};

    // get function serializations
    var serializations = [];
    for (var v in definitions)
      iterate(implementation[v], function (value, prop) {
        if (typeof value == 'function') {
          serializations.push('(function () { var uber = __scope.uberScope && __scope.uberScope["public"].' + prop.replace(/^([gs]et) (.*)$/, '$2_$1_') + '; ');
          serializations.push(prop.replace(/^([gs]et) (.*)$/, '$2_$1_') + ' = (' + value.toString().replace(/^function \(/, 'function ' + prop.replace(/^([gs]et) (.*)$/, '$2_$1_') + '(') + ');');
          serializations.push('})();');
        }
      });
    // add the magic that holds the sky up from the ground
    serializations.push('(function () { var uber = __scope.uberScope && __scope.uberScope.constructor; ');
    serializations.push('__scope.constructor = (' + magic.constructor.toString() + ');');
    serializations.push('})();');
    serializations.push('(function () { var uber = __scope.uberScope && __scope.uberScope.cast; ');
    serializations.push('__scope.cast = (' + magic.cast.toString() + ');');
    serializations.push('})();');

    // constructor
    var Factory = function () {
      // instantiate scope and object
      var scope = createScope();
      // object-specific properties
      scope['private'].constructor__ = Factory;
      // call constructor or cast, return object
      (this != window ? scope.constructor : scope.cast).apply(scope['public'], arguments);
      return scope['public'];
    }
    // create scope factory
    var createScope = evalInGlobalScope("0, (function (__self, __ancestor) { return (function (__scope) {\
      var __scope = { public: __scope ? __scope['public'] : new __self.definitions['public'](), private: new __self.definitions['private'](), uberScope: null, constructor: null, cast: null}; \
      if (__ancestor) { __scope.uberScope = __ancestor.createScope(__scope); } \
      with (__scope['private']) { with (__scope['public']) {" + serializations.join('\n') + "} }\
      return __scope;\
    }); })")(Factory, Ancestor);
    
    // add statics
    iterate(implementation['static'], function (value, prop) {
      Factory[prop] = value;
    });
    // sugar
    Factory.classID = classID;
    Factory.toString = function () { return classID + '()'; }
    // add properties for inheritance
    Factory.createScope = createScope;
    Factory.implementation = implementation;
    Factory.definitions = definitions;
    Factory.ancestor = Ancestor || null;
    // inheritance inspection
    Factory.inherits = Ancestor ? [classID].concat(Ancestor.inherits) : [classID];
    Factory.hasInstance = function (obj) {
      if (!obj || !obj.constructor || !obj.constructor.inherits)
        return false;
      for (var i = 0; i < obj.constructor.inherits.length; i++)
        if (obj.constructor.inherits[i] == Factory.classID)
          return true;
      return false;
    }
  
    // define class
    window[classID] = Factory;
  };
})();