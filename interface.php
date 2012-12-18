
<html>
  <head>
    <style type="text/css"></style>
    <link rel="stylesheet/less" type="text/css" href="css/voting-system.less">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/less.min.js"></script>
    <script type="text/javascript" src="js/classical-m4.js"></script>
    <script type="text/javascript" src="js/classes.js"></script>
    <script type="text/javascript">
      $(function () {

        /**
         * Extension of the jQuery.serialize to include all fields
         */
        var r20 = /%20/g;
        var rbracket = /\[\]$/;
        var rCRLF = /\r?\n/g;
        var rinput = /^(?:color|date|datetime|datetime-local|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i;
        var rselectTextarea = /^(?:select|textarea)/i;
        jQuery.fn.extend({
          serialize_all: function() {
            return jQuery.param( this.serializeArray_all() );
          },
          serializeArray_all: function(options_) {
            var options = $.extend({
              disabled: true,
              unchecked: true

            }, options_);

            return this.map(function(){
              return this.elements ? jQuery.makeArray( this.elements ) : this;
            })
            .filter(function(){
              return this.name &&
                    (options.disabled || !this.disabled) &&
                    ( (options.unchecked || this.checked) ||
                      rselectTextarea.test( this.nodeName
                    ) ||
                    rinput.test( this.type ) );
            })
            .map(function( i, elem ){
              var val = jQuery( this ).val();

              return val == null ?
                null :
                jQuery.isArray( val ) ?
                  jQuery.map( val, function( val, i ){
                    return { name: elem.name, value: val.replace( rCRLF, "\r\n" ) };
                  }) :
                  { name: elem.name, value: val.replace( rCRLF, "\r\n" ) };
            }).get();
          }
        });

        var drinkathon = new Poll({
          id: 6969,
          url: 'vote.php',
          title: 'Availability for IT Committee Drinkathon',
          subtitle: 'Please mark your dates of availability',
          fields: {
            '18.12.2012': 'checkbox',
            '19.12.2012': 'checkbox',
            '20.12.2012': 'checkbox',
            '21.12.2012': 'checkbox',
            '22.12.2012': 'checkbox'
          }
        });
        drinkathon.structure.poll.find('input[type="checkbox"]').
          on('change.color', function on_change () {
            $(this).parent().css('background', !!$(this).attr('checked') ? 'lightgreen' : '#ffccca');
          }).trigger('change');


        var end_of_the_world_fields = {};
        var days = 'Monday Tuesday Wednesday Thursday Friday Saturday Sunday'.split(' ');
        for (var i=0; i<days.length; ++i) {
          end_of_the_world_fields[days[i]] = new RadioInput('options[day]', {value:days[i]});
        }
        var armageddon = new Poll({
          id: 6900,
          url: 'vote.php',
          title: 'End of the World',
          subtitle: 'On which day do you think the end of the world will happen?',
          fields: end_of_the_world_fields
        });
        armageddon.structure.poll.find('input[type="radio"]').
          on('change.color', function on_change () {
            armageddon.structure.poll.find('input[name="'+this.name+'"]').parent().css('background', '#ffccca');
            if ($(this).attr('checked')) {
              $(this).parent().css('background', 'lightgreen');
            }
          }).trigger('change');


        $('body').append(
          drinkathon.element,
          armageddon.element
        );

        $('form').on('submit.vote', function _on_vote (event) {
          event.preventDefault();
          $.ajax({
            type: "GET",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (response) {
              console.log(response);
            },
            error: function (xhr, ajaxOptions, thrownError) {
              console.warn('Form submit fail!');
              throw thrownError;
            }
          });
        });

      });

      function jq_element (type) {
        return $(document.createElement(type));
      }
    </script>
    <body>
      <!-- <form action="vote.php" method="get" id="vote">
        <input type="submit" value="send" />
      </form> -->
    </body>
  </head>
</html>