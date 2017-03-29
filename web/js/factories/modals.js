/**
 * Created by tanna on 25/03/2017.
 */
module.exports = function($q, $log, errorManager, $uibModal, config) {
    return {

        /**
         * @param modalParameters {object}
         * @returns {*|Window}
         */
        modalInstance: function(modalParameters) {
            return $uibModal.open(modalParameters);
        },

        /**
         * Open a modal who can manage a persistentObject.
         * Return a promise who is resolved when the persistentObject has to be saved and rejected if not.
         * Resolve and Rejected options: ['saveAll', 'save', 'return']
         * @param persistentObject
         * @param extendedModalOptions
         * @returns {promise|jQuery.promise}
         */
        persistentObjectModal: function(persistentObject, extendedModalOptions) {
            let deferred = $q.defer();
            let modalParameters = {
                bindToController: true,
                scope: persistentObject.scope,
                templateUrl: config.base_uri + '/js/tpl/modal/modal_wrapper.tpl.html',
                controllerAs: 'modalCtrl',
                controller: ['$scope', '$log', 'config', function($scope, $log, config) {

                    if(angular.isUndefined($scope.errm)) {
                        $log.warn('[factories:modals] errm is not defined on binded scope');
                        $scope.errm = errorManager;
                    }

                    $scope.persistentObjectUrl = persistentObject.templateUrl;
                    $scope.persistError = persistentObject.error;
                        $log.debug("modalCtrl ?", this, $scope);

                }],
            };

            $uibModal.open(angular.extend(modalParameters, extendedModalOptions)).result.then(undefined, function(reason) {
                if(angular.equals(reason, 'return'))
                    deferred.reject(reason);
                deferred.resolve(reason);
            });


            return deferred.promise;
        }
    };
};