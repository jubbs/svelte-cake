<?php $this->layout = 'reset'; ?>
<div class="row">
    <div class="col-xs-12">
        <div class="module">
            <div class="module-content">
            <?= $this->Flash->render() ?>
            <?php if (!$reset): ?>
                <?php echo $this->Form->create(null, array('class'=>'form-horizontal')); ?>
                    <?php echo $this->Form->input('username', array('label'=>'Username', 'between'=>'<div class="col-lg-6">', 'after'=>'</div>')); ?>
                    <div class="form-group">
                        <div class="col-lg-offset-4">
                            <?php echo $this->Form->button(__('Password Reset'), array('class'=>'btn btn-lg btn-primary', 'name'=>'action', 'value'=>'submit')); ?>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            <?php else: ?>
                <?php echo $this->Form->create($reset_user, array('class'=>'form-horizontal')); ?>
                    <?php echo $this->Form->input('newpass', array('label'=>'Password', 'type'=>'password','between'=>'<div class="col-lg-4">', 'after'=>'</div>', 'placeholder'=>'New Password')); ?>
                    <?php echo $this->Form->input('newpassconfirm', array('label'=>' ', 'between'=>'<div class="col-lg-offset-4 col-lg-4">', 'type'=>'password', 'after'=>'</div>', 'placeholder'=>'New Password Confirm')); ?>
                    <?php echo $this->Form->input('reset_hash', array('type'=>'hidden', 'value'=>$reset_user->reset_hash)); ?>
                    <?php echo $this->Form->input('id', array('type'=>'hidden', 'value'=>$reset_user->id)); ?>
                    <div class="form-group">
                        <div class="col-lg-offset-5">
                            <?php echo $this->Form->button(__('Password Reset'), array('class'=>'btn btn-lg btn-primary', 'name'=>'action', 'value'=>'newpass')); ?>
                        </div>
                    </div>
                <?php echo $this->Form->end(); ?>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>