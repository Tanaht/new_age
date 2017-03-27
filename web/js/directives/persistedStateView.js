/**
 * Created by Antoine on 21/03/2017.
 */
module.exports = function($log, modals, persistedQueue, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/persisted_state_view.tpl.html',
        controller: function($scope) {
            $scope.errorModalReason = {
                persistAll : 'persist-all',
                persistThis: 'persist-this',
                noPersist: 'no-persist',
            };

            $scope.popoverOpened = false;

            $scope.popoverTemplate = config.base_uri + "/js/tpl/popover/persisted_state_view.tpl.html";
            $scope.queue = persistedQueue;
            $scope.count = persistedQueue.size();
            $scope.icon = 'floppy-saved';//refresh, floppy-disk, floppy-saved, floppy-remove;

            $scope.$watch('queue.size()', function(newValue) {
                if(angular.isDefined(newValue)) {
                    $scope.count = newValue;
                    if (newValue > 0) {
                        $scope.icon = 'floppy-disk';
                    }
                }
            });

            $scope.classState = function() {
                if (angular.equals($scope.queue.size(), 0)) {
                    return 'btn-default';
                }

                if ($scope.queue.hasNext() && angular.equals($scope.queue.first().state, config.persistentStates.ERROR_PERSIST)) {
                    return 'btn-danger';
                }

                if ($scope.queue.hasNext() && angular.equals($scope.queue.first().state, config.persistentStates.UN_PERSISTED)) {
                    return 'btn-primary';
                }

                return 'btn-success';
            };


            /**
             *
             * @param persistentObject
             */
            $scope.persistOne = function(persistentObject) {
                $scope.popoverOpened = true;
                $scope.icon = 'refresh';


                $scope.queue.persistOne(persistentObject).then(function() {
                    $scope.icon = "floppy-saved";
                    $scope.popoverOpened = false;
                }, function() {
                    $scope.icon = "floppy-remove";
                    $scope.popoverOpened = true;
                });
            };

            $scope.persist = function($event) {
                if(angular.isDefined($event))
                    $event.stopPropagation();
                $scope.popoverOpened = true;
                $scope.icon = 'refresh';

                $scope.queue.persist().then(function() {
                    $scope.icon = "floppy-saved";
                    $scope.popoverOpened = false;
                }, function() {
                    $scope.icon = "floppy-remove";
                    $scope.popoverOpened = true;
                });
            };


            /*$scope.openErrorModal = function(po) {

                if(po.persistErrorHandled) {
                    let modalInstance = modals.errorModalInstance('myModal', {
                        scope: $scope,
                        size: 'lg'
                    }, {
                        error: po.error,
                        scope: po.scope,
                        templateUrl: po.templateUrl,
                        footerTemplate: '<button data-ng-click="$modal.$dismiss($reasons.persistAll)" class="btn btn-success">Réessayer et continuer la sauvegarde</button>' +
                        '<button data-ng-click="$modal.$dismiss($reasons.persistThis)"  class="btn btn-success">Réessayer</button>' +
                        '<button data-ng-click="$modal.$dismiss($reasons.noPersist)"  class="btn btn-warning">Retour</button>',
                        dismissedReasons: $scope.errorModalReason
                    });


                    modalInstance.result.then(undefined, function (reason) {
                        switch (reason) {
                            case $scope.errorModalReason.persistAll:
                                $scope.persist();
                                break;
                            case $scope.errorModalReason.persistThis:
                                $scope.persistOne(po);
                                break;
                            case $scope.errorModalReason.noPersist:
                                break;
                        }
                    });
                }
            }*/
        }
    }
};