/**
 * Created by wukezhan on 14/10/26.
 */
$(function(){
    'use strict';
    var $z = window.$z;
    var buttons = {
        'started': '<button class="btn btn-warning" data-click="stop">Stop</button>',
        'stoped': '<button class="btn btn-success" data-click="start">Start</button>&nbsp;<button class="btn btn-danger" data-click="delete">Delete</button>',
    };

    $('tr').delegate('[data-click=delete]', 'click', function(){
        var $tr = $(this).parent().parent();
        var id = $tr.attr('row-id');
        $z.confirm({
            body: 'Confirm to delete？'
        }, {
            submit: function(){
                $.ajax({
                    url: '/docker/api/containers/'+id,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(data){
                        $z.alert({
                            body: 'Container `'+id.substr(0, 12)+'` has been removed!'
                        });
                        $tr.remove();
                    }
                });
            }
        });
    }).delegate('[data-click=start],[data-click=stop]', 'click', function(){
        var $td = $(this).parent();
        var $tr = $td.parent();
        var id = $tr.attr('row-id');
        var action = $(this).attr('data-click');
        $z.confirm({
            body: 'Confirm to '+action+'?'
        }, {
            submit: function(){
                $.ajax({
                    url: '/docker/api/containers/'+id+'/'+action,
                    type: 'POST',
                    dataType: 'json',
                    success: function(data){
                        $z.alert({
                            body: 'Container `'+id.substr(0, 12)+'` has been '+action+'ed!'
                        });
                        if('start'==action){
                            $td.prev().html('Up 1 seconds');
                        }else{
                            $td.prev().html('Exited (-1) 1 seconds ago')
                        }
                        $td.html(buttons[action+'ed']);
                    }
                });
            }
        });
    });
});