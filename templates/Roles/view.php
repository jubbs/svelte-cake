<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Role $role
 */
?>
<?php $this->extend('/layout/TwitterBootstrap/dashboard'); ?>

<?php $this->start('tb_actions'); ?>
<li><?= $this->Html->link(__('Edit Role'), ['action' => 'edit', $role->id], ['class' => 'nav-link']) ?></li>
<li><?= $this->Form->postLink(__('Delete Role'), ['action' => 'delete', $role->id], ['confirm' => __('Are you sure you want to delete # {0}?', $role->id), 'class' => 'nav-link']) ?></li>
<li><?= $this->Html->link(__('List Roles'), ['action' => 'index'], ['class' => 'nav-link']) ?> </li>
<li><?= $this->Html->link(__('New Role'), ['action' => 'add'], ['class' => 'nav-link']) ?> </li>
<?php $this->end(); ?>
<?php $this->assign('tb_sidebar', '<ul class="nav flex-column">' . $this->fetch('tb_actions') . '</ul>'); ?>

<div class="roles view large-9 medium-8 columns content">
    <h3><?= h($role->name) ?></h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <th scope="row"><?= __('Name') ?></th>
                <td><?= h($role->name) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Id') ?></th>
                <td><?= $this->Number->format($role->id) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Level') ?></th>
                <td><?= $this->Number->format($role->level) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Created') ?></th>
                <td><?= h($role->created) ?></td>
            </tr>
            <tr>
                <th scope="row"><?= __('Modified') ?></th>
                <td><?= h($role->modified) ?></td>
            </tr>
        </table>
    </div>
    <div class="text">
        <h4><?= __('Notes') ?></h4>
        <?= $this->Text->autoParagraph(h($role->notes)); ?>
    </div>
    <div class="text">
        <h4><?= __('Auth Matrix') ?></h4>
        <?= $this->Text->autoParagraph(h($role->auth_matrix)); ?>
    </div>
</div>
