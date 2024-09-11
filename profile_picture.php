<?php

class profile_picture extends rcube_plugin
{
    public $task = 'settings';
    private $rc;

    function init()
    {
        $this->rc = rcmail::get_instance();
        $this->add_texts('localization/', true);
        $this->add_hook('preferences_list', array($this, 'preferences_list'));
        $this->add_hook('preferences_save', array($this, 'preferences_save'));
        $this->register_action('plugin.profile_picture_upload', array($this, 'profile_picture_upload'));
        $this->include_script('profile_picture.js');
    }

    function preferences_list($args)
    {
        if ($args['section'] != 'general') {
            return $args;
        }

        $args['blocks']['profile']['name'] = Q($this->gettext('profile_picture'));
        $args['blocks']['profile']['options']['profile_picture'] = array(
            'title' => Q($this->gettext('uploadprofilepicture')),
            'content' => $this->render_upload_form()
        );

        return $args;
    }

    function preferences_save($args)
    {
        if ($args['section'] != 'general') {
            return $args;
        }

        if (!empty($_FILES['profile_picture_file']['tmp_name'])) {
            $upload_dir = $this->rc->config->get('profile_picture_upload_dir');
            $file_info = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $file_info->file($_FILES['profile_picture_file']['tmp_name']);

            // Faylın yalnız JPEG və PNG olmasına icazə verin
            if (in_array($mime_type, ['image/jpeg', 'image/png'])) {
                $filename = $upload_dir . '/' . $_SESSION['username'] . '_' . time() . '.jpg';
                move_uploaded_file($_FILES['profile_picture_file']['tmp_name'], $filename);
            }
        }

        return $args;
    }

    function render_upload_form()
    {
        $input = new html_file(['name' => 'profile_picture_file', 'id' => 'profile_picture_file']);
        $button = new html_inputfield(['type' => 'submit', 'value' => Q($this->gettext('upload'))]);

        return $input->show() . ' ' . $button->show();
    }
}
