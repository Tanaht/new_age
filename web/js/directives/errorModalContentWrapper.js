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
            scope: '=',
            footerTemplate: '=',
            footerTemplateUrl: '=',
        },
        link: function(scope, element){
            //linking the wrapped scope to the errorModalContentWrapper parent to have access to actions $close() and $dismiss() on their own scope under $modal( e.g: $modal.$dismiss())...
            scope.scope.$modal = scope.$parent;

            if(!(angular.isDefined(scope.scope) && angular.isObject(scope.scope))) {
                $log.error("[Directive:ErrorModalContentWrapper] Requested data-scope is not valid");
            }

            scope.hasFooter = true;
            if(angular.isUndefined(scope.footerTemplate) && angular.isUndefined(scope.footerTemplateUrl)) {
                scope.hasFooter = false;
            }
            else if(angular.isDefined(scope.footerTemplateUrl) && angular.isString(scope.footerTemplateUrl)) {
                $templateRequest(scope.templateUrl).then(function(html){
                    let templateV1 = angular.element(html);
                    element.find('.wrapped-footer').append(templateV1);
                    $compile(templateV1)(scope.scope);
                });
            } else if (angular.isDefined(scope.footerTemplate) && angular.isString(scope.footerTemplate)){
                let templateV2 = angular.element(scope.footerTemplate);
                element.find('.wrapped-footer').append(templateV2);
                $compile(templateV2)(scope.scope);
            } else {
                scope.hasFooter = false;
                $log.error("[Directive:ErrorModalContentWrapper] Requested data-footer-template or data-footer-template-url are not valid");
            }

            if(angular.isDefined(scope.templateUrl) && angular.isString(scope.templateUrl)) {
                $templateRequest(scope.templateUrl).then(function(html){
                    let template = angular.element(html);
                    element.find('.wrapped-content').append(template);
                    $compile(template)(scope.scope);
                });
            }
        }
    }
};