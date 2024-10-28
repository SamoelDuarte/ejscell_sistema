<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_first_name') . ':', 'first_name', array('class' => 'required')); ?>
    <div class='form_field'>
        <?php echo form_input(
            array(
                'name' => 'first_name',
                'id' => 'first_name',
                'value' => isset($person_info->first_name) ? $person_info->first_name : ''
            )
        ); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_last_name') . ':', 'last_name', array('class' => 'required')); ?>
    <div class='form_field'>
        <?php echo form_input(
            array(
                'name' => 'last_name',
                'id' => 'last_name',
                'value' => isset($person_info->last_name) ? $person_info->last_name : ''
            )
        ); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_document') . ':', 'CPF/CNPJ'); ?>
    <div class='form_field'>
        <?php echo form_input(
            array(
                'name' => 'document',
                'id' => 'document',
                'value' => isset($person_info->document) ? $person_info->document : ''
            )
        ); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_email') . ':', 'email'); ?>
    <div class='form_field'>
        <?php echo form_input(
            array(
                'name' => 'email',
                'id' => 'email',
                'value' => isset($person_info->email) ? $person_info->email : ''
            )
        ); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_phone_number') . ':', 'phone_number'); ?>
    <div class='form_field'>
        <?php echo form_input(array(
            'name' => 'phone_number',
            'id' => 'phone_number',
            'value' => isset($person_info->phone_number) ? $person_info->phone_number : ''
        )); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_address_1') . ':', 'address_1'); ?>
    <div class='form_field'>
        <?php echo form_input(array(
            'name' => 'address_1',
            'id' => 'address_1',
            'value' => isset($person_info->address_1) ? $person_info->address_1 : ''
        )); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_address_2') . ':', 'address_2'); ?>
    <div class='form_field'>
        <?php echo form_input(array(
            'name' => 'address_2',
            'id' => 'address_2',
            'value' => isset($person_info->address_2) ? $person_info->address_2 : ''
        )); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_city') . ':', 'city'); ?>
    <div class='form_field'>
        <?php echo form_input(array(
            'name' => 'city',
            'id' => 'city',
            'value' => isset($person_info->city) ? $person_info->city : ''
        )); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_state') . ':', 'state'); ?>
    <div class='form_field'>
        <?php echo form_input(array(
            'name' => 'state',
            'id' => 'state',
            'value' => isset($person_info->state) ? $person_info->state : ''
        )); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_zip') . ':', 'zip'); ?>
    <div class='form_field'>
        <?php echo form_input(array(
            'name' => 'zip',
            'id' => 'zip',
            'value' => isset($person_info->zip) ? $person_info->zip : ''
        )); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_country') . ':', 'country'); ?>
    <div class='form_field'>
        <?php echo form_input(array(
            'name' => 'country',
            'id' => 'country',
            'value' => isset($person_info->country) ? $person_info->country : ''
        )); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_comments') . ':', 'comments'); ?>
    <div class='form_field'>
        <?php echo form_textarea(
            array(
                'name' => 'comments',
                'id' => 'comments',
                'value' => isset($person_info->comments) ? $person_info->comments : '',
                'rows' => '5',
                'cols' => '17'
            )
        ); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_discount') . ':', 'Desconto'); ?>
    <div class='form_field'>
        <?php echo form_input(
            array(
                'name' => 'discount',
                'id' => 'discount',
                'value' => isset($person_info->desconto) ? $person_info->desconto : 0,
                'type' => 'number',
                'min' => '0',
                'max' => '99'
            )
        ); ?>
    </div>
</div>

<div class="field_row clearfix">
    <?php echo form_label($this->lang->line('common_category') . ':', 'category'); ?>
    <div class='form_field'>
        <?php echo form_input(array(
            'name' => 'category',
            'id' => 'category',
            'value' => isset($person_info->categories) ? implode(', ', $person_info->categories) : '' // Garante que categories seja tratado corretamente
        )); ?>
    </div>
</div>

<div class="field_row clearfix">	
    <?php echo form_label($this->lang->line('customers_account_number') . ':', 'account_number'); ?>
    <div class='form_field'>
        <?php echo form_input(array(
            'name' => 'account_number',
            'id' => 'account_number',
            'value' => isset($person_info->account_number) ? $person_info->account_number : ''
        )); ?>
    </div>
</div>

<div class="field_row clearfix">	
    <?php echo form_label($this->lang->line('customers_taxable') . ':', 'taxable'); ?>
    <div class='form_field'>
        <?php echo form_checkbox('taxable', '1', isset($person_info->taxable) ? (boolean)$person_info->taxable : TRUE); ?>
    </div>
</div>
