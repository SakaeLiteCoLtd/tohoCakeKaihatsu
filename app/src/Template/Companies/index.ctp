<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Company[]|\Cake\Collection\CollectionInterface $companies
 */

 $i = 1;
?>
<?php
    $username = "ログイン中：".$this->request->Session()->read('Auth.User.user_code');
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar" style="width:98%">
    <ul class="side-nav" style="background-color:#afeeee">
      <table align="right" style="background-color:#afeeee">
        <tr>
          <td><?= __($username) ?></td>
          <td><?= $this->Html->link(__('ログアウト'), ['controller' => 'Startmenus', 'action' => 'logout']) ?></td>
        </tr>
      </table>
        <div class="heading">
          <font size="5"><?= __('　会社メニュー') ?></font>
        </div>
        <br>
        <li><font size="4" color=white><?= $this->Html->link(__('　・会社新規登録'), ['action' => 'addform']) ?></font></li>
        <li><font size="4" color=white><?= $this->Html->link(__('　・総合メニューへ戻る'), ['controller' => 'Startmenus', 'action' => 'menu']) ?></font></li>
        <br>
    </ul>
</nav>


<div class="companies index large-9 medium-8 columns content" style="width:100%">
    <h3><?= __('会社一覧') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr bgcolor="#f0e68c">
                <th scope="col"><?= $this->Paginator->sort('No.') ?></th>
                <th scope="col"><?= $this->Paginator->sort('会社名') ?></th>
                <th scope="col"><?= $this->Paginator->sort('所在地') ?></th>
                <th scope="col"><?= $this->Paginator->sort('電話番号') ?></th>
                <th scope="col"><?= $this->Paginator->sort('FAX') ?></th>
                <th scope="col"><?= $this->Paginator->sort('代表者') ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody bgcolor="#FFFFCC">
            <?php foreach ($companies as $company): ?>
            <tr>
                <td><?= h($i) ?></td>
                <td><?= h($company->name) ?></td>
                <td><?= h($company->address) ?></td>
                <td><?= h($company->tel) ?></td>
                <td><?= h($company->fax) ?></td>
                <td><?= h($company->president) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('編集'), ['action' => 'editform', $company->id]) ?>
                    <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $company->id]) ?>
                </td>
            </tr>

          <?php
          $i = $i + 1;
          ?>

            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('最初のページ')) ?>
            <?= $this->Paginator->prev('< ' . __('前へ')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('次へ') . ' >') ?>
            <?= $this->Paginator->last(__('最後のページ') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
