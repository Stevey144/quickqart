window.meNotify = function (message, icon, type = 'success', title = '') {
    const is_error = type === 'error';
    $.alert({
        title: title ? title : (is_error ? 'Error' : 'Notification'),
        content: message,
        icon: icon,
        animation: 'scale',
        closeAnimation: 'scale',
        type: is_error ? 'red' : 'green',
        buttons: {
            okay: {
                text: 'Dismiss',
                btnClass: 'btn-' + (is_error ? 'red' : 'green')
            }
        }
    });
};

const toast = {
    error: (message, title = '') => {
        return meNotify(message, 'fa fa-exclamation-triangle', 'error', title);
    },
    success: (message, title = '') => {
        return meNotify(message, 'fa fa-check', 'success', title);
    },
    info: (message, title = '') => {
        return meNotify(message, 'fa fa-exclamation-triangle', 'error', title);
    },
    closeOthers: () => {
    },
    confirm: (title, message, callback, data = {cancelButtonText: 'Cancel', okButtonText: 'Continue'}) => {
        $.confirm({
            title: title,
            content: message,
            type: 'green',
            typeAnimated: true,
            icon: 'fa fa-question-circle',
            buttons: {
                okayButton: {
                    text: data.okButtonText || 'Continue',
                    btnClass: 'btn-green',
                    action: function () {
                        callback(true);
                    }
                },
                close: function () {
                    callback(false);
                }
            }
        });
    }
};

export default toast;
