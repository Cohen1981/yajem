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
function switchCheckBox(id) {
    var el = document.getElementById(id);
    if (el.checked)
    {
        $('.sda_switchinputbox').prop("checked", false);
        $('#'+id).prop("checked", true);
        $('.sda_tab').removeClass("sda_active");
        $('#'+id+"_label").addClass("sda_active");
    }
}