/**
 * Created by Antoine on 21/03/2017.
 */
module.exports = function($log, persistedQueue, config) {
    return {
        restrict: 'E',
        templateUrl: config.base_uri + '/js/tpl/persisted_state_view.tpl.html',
        controller: function($scope) {

            $scope.popoverTemplate = config.base_uri + "/js/tpl/persisted_state_view_popover.tpl.html";
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
                if ($scope.queue.size() == 0) {
                    return 'btn-default';
                }

                if ($scope.queue.hasNext() && $scope.queue.first().state == config.persistentStates.ERROR_PERSIST) {
                    return 'btn-danger';
                }

                if ($scope.queue.hasNext() && $scope.queue.first().state == config.persistentStates.UN_PERSISTED) {
                    return 'btn-primary';
                }

                return 'btn-success';
            };



            $scope.persist = function() {
                $scope.icon = 'refresh';
                $scope.queue.persist(function() {
                    $scope.icon = "floppy-saved";
                }, function() {
                    $log.debug("remove floppy");
                    $scope.icon = "floppy-remove";
                });
            }
        }
    }
};