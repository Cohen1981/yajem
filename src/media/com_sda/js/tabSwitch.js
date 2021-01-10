/**
 * labels must have the class "sda_tab" and the id must be the same as the corresponding checkbox with added _label.
 * input checkbox must have the class "sda_switchinputbox"
 *
 * eg.:     <label id="event_label" class="sda_tab" for="event">test</label>
 *          <input type="checkbox" id="event" class="hidden sda_switchinputbox" onchange="switchCheckBox('event')" />
 *
 * Active tab/ label gets the class "sda_active"
 *
 * @param id
 */
$(document).ready(function () {
    var tabcb = $('.sda_switchinputbox');
    tabcb.each(function ()
     {
        $(this).on('change',function () {
            switchCheckBox($(this).prop("id"))
        })
    });
});

function switchCheckBox(id) {
    var el = $('#'+id);
    var state = $('#'+id+'_state');
    if (el.prop("checked"))
    {
        $('#'+id+'_label').addClass("sda_active");
    } else {
        $('#'+id+'_label').removeClass("sda_active");
    }
    if (state.hasClass('fa-angle-double-up')) {
        state.removeClass('fa-angle-double-up');
        state.addClass('fa-angle-double-down');
    } else {
        state.removeClass('fa-angle-double-down');
        state.addClass('fa-angle-double-up');
    }
}