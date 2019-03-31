{if $isEnews}
<div class="crm-section enews-section form-item" id="enews">
  <div class="label">{$form.is_enews.label}
  </div>
  <div class="content">{$form.is_enews.html}</div>
  <div class="clear"></div>
</div>

  {literal}
    <script type="text/javascript">
      CRM.$(function($) {
        $('#enews').insertBefore($('.crm-submit-buttons'));
      });
    </script>
  {/literal}
{/if}
<div class="crm-section second_parent-section form-item" id="secondparent">
  <div class="label">{$form.second_parent_first_name.label}
  </div>
  <div class="content">{$form.second_parent_first_name.html}</div>
  <div class="clear"></div>
  <div class="label">{$form.second_parent_last_name.label}
  </div>
  <div class="content">{$form.second_parent_last_name.html}</div>
  <div class="clear"></div>
</div>
<div id="first-child-asd">
  <fieldset><legend>Child 1</legend></fieldset>
  <div class="crm-section"><div class="label">{$form.child_diagnosis.1.label}</div> <div class="content">{$form.child_diagnosis.1.html}</div></div>
  <div class="crm-section"><div class="label">{$form.child_first_name.1.label}</div> <div class="content">{$form.child_first_name.1.html}</div></div>
  <div class="crm-section"><div class="label">{$form.child_last_name.1.label}</div> <div class="content">{$form.child_last_name.1.html}</div></div>
  <div class="crm-section"><div class="label">{$form.child_birth_date.1.label}</div> <div class="content">{$form.child_birth_date.1.html}</div></div>
  <div class="crm-section"><div class="label">{$form.child_gender.1.label}</div> <div class="content">{$form.child_gender.1.html}</div></div>
  <div class="clear"></div>
  <div class="crm-section"><div class="label">{$form.child_is_registered.1.label}</div> <div class="content">{$form.child_is_registered.1.html}</div></div>
  <div class="clear"></div>
</div>
<div id="children-with-asd">
{section name='i' start=2 loop=6}
    {assign var='rowNumber' value=$smarty.section.i.index}
    <div id="add-item-row-{$rowNumber}" class="child-row hiddenElement">
      <fieldset><legend>Child {$rowNumber}</legend></fieldset>
      <div class="crm-section"><div class="label">{$form.child_diagnosis.$rowNumber.label}</div> <div class="content">{$form.child_diagnosis.$rowNumber.html}</div></div>
      <div class="crm-section"><div class="label">{$form.child_first_name.$rowNumber.label}</div> <div class="content">{$form.child_first_name.$rowNumber.html}</div></div>
      <div class="crm-section"><div class="label">{$form.child_last_name.$rowNumber.label}</div> <div class="content">{$form.child_last_name.$rowNumber.html}</div></div>
      <div class="crm-section"><div class="label">{$form.child_birth_date.$rowNumber.label}</div> <div class="content">{$form.child_birth_date.$rowNumber.html}</div></div>
      <div class="crm-section"><div class="label">{$form.child_gender.$rowNumber.label}</div> <div class="content">{$form.child_gender.$rowNumber.html}</div></div>
      <div class="clear"></div>
      <div class="crm-section"><div class="label">{$form.child_is_registered.$rowNumber.label}</div> <div class="content">{$form.child_is_registered.$rowNumber.html}</div></div>
      <div class="crm-section"><div class="label"><a href=# class="remove_item crm-hover-button" title='Remove Child'><i class="crm-i fa-times"></i></a></div></div>
      <div class="clear"></div>
    </div>
{/section}
</div>
{crmScope extensionKey='biz.jmaconsulting.aoservicenav'}
<span id="add-another-item" class="crm-hover-button" style="font-weight:bold;padding:10px;"><a href=#>{ts}Add another child{/ts}</a></span>
{/crmScope}

{literal}
<script type="text/javascript">
CRM.$(function($) {
  $('#add-another-item').insertAfter($('#editrow-postal_code-Primary'));
  $('#children-with-asd').insertAfter($('#editrow-postal_code-Primary'));
  $('#first-child-asd').insertAfter($('#editrow-postal_code-Primary'));
  $('#secondparent').insertAfter($('#editrow-postal_code-Primary'));

  var submittedRows = $.parseJSON('{/literal}{$childSubmitted}{literal}');

  // after form rule validation when page reloads then show only those line-item which were chosen and hide others
  $.each(submittedRows, function(e, num) {
    isSubmitted = true;
    $('#add-item-row-' + num).removeClass('hiddenElement');
  });

  $('#add-another-item').on('click', function(e) {
    e.preventDefault();
    var hasHidden = $('div.child-row').hasClass("hiddenElement");
    if (hasHidden) {
      var row = $('#children-with-asd div.hiddenElement:first');
      $('div.hiddenElement:first, #children-with-asd').fadeIn("slow").removeClass('hiddenElement');
      hasHidden = $('div.child-row').hasClass("hiddenElement");
    }
    $('#add-another-item').toggle(hasHidden);
  });

  $('.remove_item').on('click', function(e) {
    e.preventDefault();
    var row = $(this).closest('div.child-row');
    $('#add-another-item').show();
    $('input[name^="child_diagnosis"]', row).prop('checked', false);
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
