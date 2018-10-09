$( "#countries" ).click(function() {

    $self = $(this);

    if($self.hasClass('active')){
        $( ".translate" ).removeClass("d-block");
        $( ".header-total" ).removeClass("hcem");
        $( ".default-act" ).removeClass("hcem");
        $( ".icon-rotate" ).removeClass("rotai");
        $( "#countries" ).removeClass("white-force");
        $( "#languages" ).removeClass("white-force");
        $( "body" ).removeClass("no-scroll");

        $self.removeClass('active');

    }else{
        $( ".translate" ).addClass("d-block");
        $( ".header-total" ).addClass("hcem");
        $( ".default-act" ).addClass("hcem");
        $( ".icon-rotate" ).addClass("rotai");
        $( "#countries" ).addClass("white-force");
        $( "#languages" ).addClass("white-force");
        $( "body" ).addClass("no-scroll");
        $self.addClass('active');
    }


    setTimeout(function(){
        $( ".translate" ).addClass("translate-color");
    }, 100);

});