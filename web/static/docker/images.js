/**
 * Created by wukezhan on 14/10/26.
 */
$(function(){
    'use strict';
    var $z = window.$z;

    $('tr').delegate('[data-click=images-delete]', 'click', function(){
        var $tr = $(this).parent().parent();
        var id = $tr.attr('image-id');
        $z.confirm({
            body: 'Confirm to delete？'
        }, {
            submit: function(){
                $.ajax({
                    url: '/docker/api/images/'+id,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(data){
                        $z.alert({
                            body: 'Image `'+id.substr(0, 12)+'` has been removed!'
                        });
                        $tr.remove();
                    }
                });
            }
        });
    });
});