/*
    Tests access to an addon with Bootstrap.
*/
function check_access_addon_bs (addon_name, output_div, control_element, addon_data) {
    var addon_active = false;

    if (addon_data.enabled) {
        addon_active = true;
    };
    if (!addon_data.enabled) {
        $('#' + output_div).collapse();

        // Allow use the addon on trial.
        if (!addon_data.trial) {
            setTimeout(function(){$('#' + control_element).prop( "checked", false);}, 50);
        }
    }
    return addon_active;
}
