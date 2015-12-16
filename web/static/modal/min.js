/**
 * Created by wukezhan on 14/10/22.
 */
(function(){
    var template = {
        base: '<div class="modal {effect}" aria-hidden="true">\
    <div class="modal-dialog">\
        <div class="modal-content">\
            <div class="modal-header">\
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>\
                <h4 class="modal-title">{title}</h4>\
            </div>\
            <div class="modal-body">{body}</div>\
            <div class="modal-footer">{buttons}</div>\
        </div>\
    </div>\
</div>',
        buttons: {
            submit: '<input type="button" class="btn btn-primary" modal-role="submit" value="{submit}" />',
            cancel: '<input type="button" class="btn btn-default" modal-role="cancel" value="{cancel}" />'
        },
        render: function(tpl, opts, prebuild){
            if(!prebuild){
                opts = $.extend({
                    'submit': '&nbsp;&nbsp;OK&nbsp;&nbsp;',
                    'cancel': 'Cancel',
                    'effect': 'fade sweet'
                }, opts);
            }
            for(var n in opts){
                tpl = tpl.replace('{'+n+'}', opts[n]);
            }
            return tpl;
        }
    };

    var $z = window.$z||{};
    $z.modal = function(opts){
        //console.log(opts);
        opts.events = $.extend({
            'submit': function(){},
            'cancel': function(){}
        }, opts.events);
        tpl = template.render(template.base, {buttons: opts.buttons}, 1);
        var $modal = $(template.render(tpl, opts.data));
        $modal.on('hidden.bs.modal', function(){
            $(this).remove();
        });
        for(var n in opts.events){
            switch(n){
                case 'submit':
                case 'cancel':
                    break;

                default:
                    $modal.on(n+'.bs.modal', function(){
                        opts.events[n]($modal);
                    });
            }
        }
        $modal.delegate('[modal-role=submit]', 'click', function(){
            opts.events.submit($modal);
            $modal.modal('hide');
        });
        $modal.delegate('[modal-role=cancel]', 'click', function(){
            opts.events.cancel($modal);
            $modal.modal('hide');
        });
        $modal.modal('show');
    };
    $z.alert = function(data, events){
        $z.modal($.extend(
            true,
            {data: {title: 'Alert!'}},
            {data: data, events: events},
            {buttons: template.buttons.submit},
            {
                events: {
                    shown: function($m){
                        $m.find('[modal-role=submit]').focus();
                    }
                }
            }
        ));
    };
    $z.confirm = function(data, events){
        $z.modal($.extend(
            true,
            {data: {title: 'Confirm!'}},
            {
                events: {
                    shown: function($m){
                        $m.find('[modal-role=submit]').focus();
                    }
                }
            },
            {data: data, events: events},
            {buttons: template.buttons.cancel+' '+template.buttons.submit}
        ));
    };
    $z.to_json = function(str){
        var json = {};
        try{
            json = $.parseJSON(str);
        }catch(e){
            json = {};
        }
        return json;
    }
    window.$z = $z;
})();
