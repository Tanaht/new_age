/**
 * Created by tanna on 25/03/2017.
 */
module.exports = function($log, $uibModal) {
    return {
        /**
         *
         * @param identity {string}: un identifiant du modal de préférence aucun caractère particulier
         * @param modalParameters {object} List of parameters to configures this modal (scope is the only one required, see UI-Bootstrap about modal)
         * @param errorParameters {object} List of parameters to match errorModalContentWrapper directive scope.
         * @returns {object|Window} A modal Instance (Doc on UI-Bootstrap about modal Instance API ($uibModalInstance)
         */
        errorModalInstance : function(identity, modalParameters, errorParameters) {
            if(angular.isUndefined(modalParameters.scope)) {
                $log.error("[Service:modals] errorModalInstance: Invalid modalParameters (scope not found)");
                return undefined;
            }

            let wrapper = angular.element(document.createElement("error-modal-content-wrapper"));

            let $$modals = [];
            $$modals[identity] = {};

            if(angular.isDefined(errorParameters.scope)) {
                $$modals[identity].scope = errorParameters.scope;
                wrapper.attr('data-scope', '$$modals[\'' + identity + '\'].scope');
            }

            if(angular.isDefined(errorParameters.templateUrl)) {
                $$modals[identity].templateUrl = errorParameters.templateUrl;
                wrapper.attr('data-template-url', '$$modals[\'' + identity + '\'].templateUrl');
            }

            if(angular.isDefined(errorParameters.dismissedReasons)) {
                $$modals[identity].dismissedReasons = errorParameters.dismissedReasons;
                wrapper.attr('data-dismissed-reasons', '$$modals[\'' + identity + '\'].dismissedReasons');
            }

            if(angular.isDefined(errorParameters.footerTemplate)) {
                $$modals[identity].footerTemplate = errorParameters.footerTemplate;
                wrapper.attr('data-footer-template', '$$modals[\'' + identity + '\'].footerTemplate');
            }

            if(angular.isDefined(errorParameters.error)) {
                $$modals[identity].error = errorParameters.error;
                wrapper.attr('data-error', '$$modals[\'' + identity + '\'].error');
            }

            if(angular.isDefined(errorParameters.footerTemplateUrl)) {
                $$modals[identity].footerTemplateUrl = errorParameters.footerTemplateUrl;
                wrapper.attr('data-footer-template-url', '$$modals[\'' + identity + '\'].footerTemplateUrl');
            }

            modalParameters.template  = wrapper.prop('outerHTML');
            modalParameters.scope.$$modals = $$modals;

            return $uibModal.open(modalParameters);


        },

        /**
         * @param modalParameters {object}
         * @returns {*|Window}
         */
        modalInstance: function(modalParameters) {
            return $uibModal.open(modalParameters);
        }
    };
};