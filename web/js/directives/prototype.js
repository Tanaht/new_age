/**
 * Created by Antoine on 12/02/2017.
 */
/*
 * angular directive for data-prototype on Symfony Form Collection type
 */
module.exports = function($log) {
    return {
        restrict: 'A',
        scope: {
            prototype:"@",
        },
        link: function(scope, element, attributes){
            $log.debug(scope.prototype);
            $log.debug(element);
        }
    }
};