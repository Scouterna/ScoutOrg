Joomla.submitbutton = function (task) {
    if (task == '') {
        return false;
    } else {
        Joomla.submitform(task);
        return true;
    }
}