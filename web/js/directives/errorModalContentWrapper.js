/**
 * Created by tanna on 25/03/2017.
 * ModalContentWrapper used to show Error Messages
 */
module.exports = function($log, $templateRequest, $compile, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + "/js/tpl/modal/error_modal_content_wrapper.tpl.html",
        scope: {
            templateUrl: '=',
            scope: '='
        },
        link: function(scope, element){
            // TODO: check the needed params of the parent or find a way to complete this.
            // if(angular.isUndefined(scope.$parent.errorModalReason)) {
            //     $log.error("[Directive:ErrorModalContentWrapper] Parents scope ")
            // }
            if(angular.isDefined(scope.scope) && angular.isObject(scope.scope) && angular.isDefined(scope.templateUrl) && angular.isString(scope.templateUrl)) {
                $templateRequest(scope.templateUrl).then(function(html){
                    let template = angular.element(html);
                    element.find('.wrapped-content').append(template);
                    $compile(template)(scope.scope);
                });
            }
        }
    }
};