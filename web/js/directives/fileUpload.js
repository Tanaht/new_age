module.exports = function ($log) {

    return {
        restrict: 'A',
        link: function(scope, element, attributes){
            if(!scope.isInputFileNode(element)) {
                scope.log('error',"Attempt to apply a file upload directive to a wrong node");
                return;
            }
            element.addClass('hide');
            let hasError = element.parent().hasClass('has-error');
            let uploadFileButton =
                "<button type='button' id='directives-file-upload-button' class='btn btn-file btn-block " + (hasError ? 'btn-danger' : '') + "'>" +
                    "<span class='glyphicon glyphicon-cloud-upload'></span>" +
                    " Modifier Image" +
                "</button>"


            element.after(uploadFileButton);

            element.parent()
                .find('#directives-file-upload-button')
                .on('click', element, scope.triggerUploadWindow)
            ;

            element.change(element, scope.autoSubmitForm);
            scope.log('debug', "File Upload Enabled");
        },
        controller:  function($scope) {
            $scope.log = function(type, message) {
                let prefix = "[directives:fileUpload]";
                switch (type) {
                    case 'warn':
                        $log.warn(prefix + message);
                        break;
                    case 'debug':
                        $log.debug(prefix + message);
                        break;
                    case 'info':
                        $log.info(prefix + message);
                        break;
                    case 'error':
                        $log.error(prefix + message);
                        break;
                    default:
                        $log.log(prefix + message);
                }
            };

            $scope.isInputFileNode = function(node) {
                return node.prop('tagName') == 'INPUT' && node.prop('type') == 'file';
            };

            $scope.triggerUploadWindow = function(event) {
                if(!$scope.isInputFileNode(event.data))
                    $scope.log('error', 'Attempt to apply a file upload directive to a wrong node');
                event.data.trigger('click');
            };

            $scope.autoSubmitForm = function(event) {
                if(!$scope.isInputFileNode(event.data))
                    $scope.log('error', 'Attempt to apply a file upload directive to a wrong node');

                //TODO: if this auto submit functionality is used in more than input type it can be useful to detach from it.
                $scope.log('debug', 'auto submit form requested');

                event.data.closest('form').submit();

            };
        },
    }
}