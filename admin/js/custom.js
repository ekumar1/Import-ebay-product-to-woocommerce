$(document).ready(function(){
    $("input").keyup(function(){
        var ebay_id = $("input").val();
        $.post(ebay_ajax+"?action=ebay_ajax&ebay_id="+ebay_id, {ebay_id: ebay_id}, function(result){
            $("#result").html(result);
        });
    });
});