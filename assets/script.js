jQuery( window ).on( 'elementor/frontend/init', function() {
    //hook name is 'frontend/element_ready/{widget-name}.{skin} - i dont know how skins work yet, so for now presume it will
    //always be 'default', so for example 'frontend/element_ready/slick-slider.default'
    //$scope is a jquery wrapped parent element


    /**
     * sparklestore pro banner slider
    */
    elementorFrontend.hooks.addAction( 'frontend/element_ready/sparkle-buy-now-button.default', function($scope, $){
            
        var btn = $scope.find('.sparkle-buy-now-btn');
        var content = $scope.find('.sparkle-buy-now-content-wrapper');
        content.find('.spk-close').click(function(){
            content.hide();
        })
        btn.click(function(){
            content.show();
        })


        jQuery('.edd_download_purchase_form input[type="radio"]').change(function() {
            jQuery(".spk-theme-price-dynamic").text( jQuery(this).parent().find('.edd_price_option_price').text() );
        });

    });
} );