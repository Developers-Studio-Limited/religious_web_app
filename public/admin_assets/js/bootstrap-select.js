// Class definition

var KTBootstrapSelect = function () {
    
    // Private functions
    var demos = function () {
        // minimum setup
        $('.kt-selectpicker').selectpicker({
            includeSelectAllOption: true,
        });
    }

    return {
        // public functions
        init: function() {
            demos(); 
        }
    };
}();

jQuery(document).ready(function() {
    KTBootstrapSelect.init();
});