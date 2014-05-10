<?php

$this->loadheader('Admin');

if ($this->rank() >= 6) {
    $this->loadview('title', array('title' => 'Create Model'))
         ->loadview('link', array('link' => '/admin/modelmanage', 'label' => 'Back to Models'));
    
    $Form = new AjaxForm();
    $Form->add(new TextBox('Model Name', 'required|alpha|minlength[3]|maxlength[20]|classnameavailable'));
    $Form->add(new SubmitButton('Create Model', 'brick_add', 'positive'));
    $Form->add(new CancelButton('/admin/modelmanage'));
    
    if ($Form->handle()) {
        $Folder = new Folder(ROOT.DS.'app'.DS.'models');
        $content = "<?php\n\nclass ".ucfirst($Form->input(0))." extends Model {\n\t\n}\n\n?>";
        $Folder->createfile(strtolower($Form->input(0)).'.php', $content);
        goto('/admin/modelmanage');
    }
    
    $this->loadview($Form);
}

?>