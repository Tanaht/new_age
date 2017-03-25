/**
 * Created by Antoine on 21/03/2017.
 */
module.exports = function($log, $uibModal, persistedQueue, config) {
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



            $scope.persistOne = function(persistentObject) {
                $scope.popoverOpened = true;
                $scope.icon = 'refresh';
                $scope.queue.persistOne(persistentObject, function() {
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
                $scope.queue.persist(function() {
                    $scope.icon = "floppy-saved";
                    $scope.popoverOpened = false;
                }, function() {
                    $scope.icon = "floppy-remove";
                    $scope.popoverOpened = true;
                });
            };


            $scope.openErrorModal = function(po) {

                $scope.modalScope = po.scope;
                $scope.modalTemplateUrl = po.templateUrl
                if(po.persistErrorHandled) {
                    let modalInstance = $uibModal.open({
                        template: '<error-modal-content-wrapper data-template-url="modalTemplateUrl" data-scope="modalScope"></error-modal-content-wrapper>',
                        scope: $scope,
                        size: 'lg'
                    });


                    modalInstance.result.then(undefined, function(reason) {
                        switch(reason) {
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
            }
        }
    }
};