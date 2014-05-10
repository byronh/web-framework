<h1>Todo List</h1>

<?php foreach ($Todos as $Todo): ?>
<p><?php if ($rank >= 5): ?><a href="/admin/tododelete?id=<?php echo $Todo->Todo_id; ?>" onclick="return confirm('Delete this item?');"><?php echo icon('note'); ?></a><?php else: echo icon('note'); endif; ?> <?php echo $Todo->User->User_name; ?>: <?php echo $Todo->Todo_item; ?></p>
<?php endforeach; ?>
<br />
<hr />