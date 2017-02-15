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
            allowAdd:"=",
            allowDelete:"=",
            what:"=",
        },
        link: function(scope, element, attributes){
            let what = "";
            if(angular.isDefined(scope.what))
                what = scope.what;

            let removeItemButton = '<button type="button" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-remove-sign"></span></button>';

            if(angular.isDefined(scope.allowDelete)) {
                //TODO: script to delete item in collections
                /*$log.debug(scope.allowDelete);

                angular.forEach(element.find(angular.element(scope.prototype).prop('tagName')), function(value, key) {
                    $log.debug(value);
                    value.prepend()
                });*/
            }

            if(angular.isDefined(scope.allowAdd)) {
                let addItemButton = '<button type="button" class="btn btn-sm btn-success"><span class="glyphicon glyphicon-plus"></span></button>';

                element.append(addItemButton);

                element.find('button').on('click', function (event) {
                    let clone = angular.element(scope.prototype).clone().html();
                    clone = clone
                        .replace(/__name__label__/g, what)
                        .replace(/__name__/g, element.find('input').length)
                    ;
                    element.find(angular.element(scope.prototype).prop('tagName')).last().after(clone);
                })
            }
        }
    }
};