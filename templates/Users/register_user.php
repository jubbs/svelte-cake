<?php $this->layout = 'public'; ?>

<h4 class="login-box-msg">Registration</h4>

<div class="users form">
    <?= $this->Flash->render() ?>

    <fieldset>
    <?php echo $this->Form->create(null, array('class'=>'form-horizontal', 'id'=>'reg-form')); ?>
        <?php echo $this->Form->control('email'); ?>
        <?php echo $this->Form->control('password', array('id'=>'reg-form-password')); ?>
        <?php echo $this->Form->control('fname', array('label'=>'First Name')); ?>
        <?php echo $this->Form->control('sname', array('label'=>'Surname')); ?>
        <?php echo $this->Form->control('phone'); ?>
        <?= $this->Form->control('email_opt_in', ['type' => 'checkbox','templates' => 'registerTemplate','label' => ' Allow Emails']) ?>
        <?= $this->Form->control('accepted_terms', ['type' => 'checkbox','templates' => 'registerTemplate','label' => ' Accept Terms']) ?>

        <div class="text-right">


            <?php echo $this->Form->button('Register', array('class'=>'btn btn-success', 'name'=>'action', 'value'=>'register')); ?>
        </div>
        <?php echo $this->Form->end(); ?>

    </fieldset>



</div>



