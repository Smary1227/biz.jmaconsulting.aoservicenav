<div id="first-child-asd">
  <fieldset><legend>Child 1</legend></fieldset>
  <div class="crm-section"><div class="label">{$form.child_first_name.1.label}</div> <div class="content">{$form.child_first_name.1.html}</div></div>
  <div class="crm-section"><div class="label">{$form.child_last_name.1.label}</div> <div class="content">{$form.child_last_name.1.html}</div></div>
  <div class="crm-section"><div class="label">{$form.child_birth_date.1.label}</div> <div class="content">{$form.child_birth_date.1.html}</div></div>
  <div class="crm-section"><div class="label">{$form.child_gender.1.label}</div> <div class="content">{$form.child_gender.1.html}</div></div>
  <div class="crm-section"><div class="label">{$form.child_is_registered.1.label}</div> <div class="content">{$form.child_is_registered.1.html}</div></div>
  <div class="clear"></div>
</div>
<div id="children-with-asd">
{section name='i' start=2 loop=6}
    {assign var='rowNumber' value=$smarty.section.i.index}
    <div id="add-item-row-{$rowNumber}" class="child-row hiddenElement">
      <fieldset><legend>Child {$rowNumber}</legend></fieldset>
      <div class="crm-section"><div class="label">{$form.child_first_name.$rowNumber.label}</div> <div class="content">{$form.child_first_name.$rowNumber.html}</div></div>
      <div class="crm-section"><div class="label">{$form.child_last_name.$rowNumber.label}</div> <div class="content">{$form.child_last_name.$rowNumber.html}</div></div>
      <div class="crm-section"><div class="label">{$form.child_birth_date.$rowNumber.label}</div> <div class="content">{$form.child_birth_date.$rowNumber.html}</div></div>
      <div class="crm-section"><div class="label">{$form.child_gender.$rowNumber.label}</div> <div class="content">{$form.child_gender.$rowNumber.html}</div></div>
      <div class="crm-section"><div class="label">{$form.child_is_registered.$rowNumber.label}</div> <div class="content">{$form.child_is_registered.$rowNumber.html}</div></div>
      <div class="crm-section"><div class="label"><a href=# class="remove_item crm-hover-button" title='Remove Child'><i class="crm-i fa-times"></i></a></div></div>
      <div class="clear"></div>
    </div>
{/section}
</div>
<span id="add-another-item" class="crm-hover-button" style="font-weight:bold;padding:10px;"><a href=#>{ts}Add another child{/ts}</a></span>

{literal}
<script type="text/javascript">
CRM.$(function($) {
  $('#add-another-item').insertAfter($('#editrow-email-Primary'));
  $('#children-with-asd').insertAfter($('#editrow-email-Primary'));
  $('#first-child-asd').insertAfter($('#editrow-email-Primary'));

  var submittedRows = $.parseJSON('{/literal}{$childSubmitted}{literal}');

  // after form rule validation when page reloads then show only those line-item which were chosen and hide others
  $.each(submittedRows, function(e, num) {
    isSubmitted = true;
    $('#add-item-row-' + num).removeClass('hiddenElement');
  });

  $('#add-another-item').on('click', function(e) {
    e.preventDefault();
    if ($('div.child-row').hasClass("hiddenElement")) {
      var row = $('#children-with-asd div.hiddenElement:first');
      $('div.hiddenElement:first, #children-with-asd').fadeIn("slow").removeClass('hiddenElement');
    }
    else {
      $('#add-another-item').hide();
    }
  });

  $('.remove_item').on('click', function(e) {
    e.preventDefault();
    var row = $(this).closest('div.child-row');
    $('#add-another-item').show();
    $('input[id^="child_first_name"]', row).val('');
    $('input[id^="child_last_name"]', row).val('');
    $('input[id^="child_birth_date"]', row).next('input').next('a').trigger('click');
    $('select[id^="child_gender"]', row).select2('val', '');
    $('input[name^="child_is_registered"]', row).prop('checked', false);
    row.addClass('hiddenElement').fadeOut("slow");
  });

});
</script>
{/literal}