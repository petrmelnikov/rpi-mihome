$(".btn-refresh").click(function(event) {
    event.preventDefault();
    let button = $(this);
    let url = button.attr('href');
    $.get(url, function( data ) {
        let powerStates = '';
        let brightnesses = '';
        dataArray = JSON.parse(data);
        dataArray.forEach(function(value) {
            powerStates += value.powerState+'/';
            brightnesses += value.brightness+'/';
        });
        button.parent().parent().find('a.power-state').text(powerStates.slice(0,powerStates.length-1));
        button.parent().parent().find('a.brightness').text(brightnesses.slice(0,brightnesses.length-1));
    });
});
$(".btn-on-off, .btn-set-brightness").click(function(event) {
    event.preventDefault();
    let button = $(this);
    let url = button.attr('href');
    $.get(url, function() {});
});
$(".btn-refresh-all").click(function(event) {
    event.preventDefault();
    $(".btn-refresh.power-state").click();
});